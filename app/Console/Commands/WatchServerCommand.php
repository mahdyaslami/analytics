<?php

namespace App\Console\Commands;

use App\Events\WebserverLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class WatchServerCommand extends Command
{
    protected $signature = 'server:watch';

    protected $description = 'Watch the state of server';

    protected $ps = [];

    protected $time = 0;

    public function handle(): void
    {
        $this->init();
        $this->start();

        while ($this->running() && !$this->timeout()) {
            $this->tasks();
            $this->wait();
        }

        $this->printErrors();
    }

    public function timeout()
    {
        if ((time() - $this->time) > config('services.webserver.timeout')) {
            $this->error('Timeout');
            return true;
        }

        return false;
    }

    private function init()
    {
        $this->ps['websockets'] = Process::command(['php', base_path('artisan'), 'websockets:serve', '--port='.$this->port()]);
        $this->ps['tail'] = Process::command(['tail', '-F', config('services.webserver.log')]);
    }

    private function port()
    {
        return config('broadcasting.connections.'.config('broadcasting.default').'.options.port');
    }

    private function start()
    {
        foreach ($this->ps as $title => $process) {
            $this->info('Run '.ucfirst($title).' process');
            $this->ps[$title] = $process->start();
        }

        $this->info('Wait 1 seconds for processes');
        sleep(1);
        $this->info('Start');
        $this->time = time();
    }

    public function tasks()
    {
        foreach ($this->ps as $title => $process) {
            $task = $title.'Task';
            if (method_exists($this, $task)) {
                $this->{$task}($process);
            }
        }
    }

    private function printErrors()
    {
        foreach ($this->ps as $title => $process) {
            $output = $process->latestOutput();
            $errorOutput = $process->latestErrorOutput();

            if (strlen($output) > 0 || strlen($errorOutput) > 0) {
                $this->error('Errors for '.ucfirst($title).' process:');
                $this->line($process->latestOutput());
                $this->line($process->latestErrorOutput());
            }
        }
    }

    private function running(): bool
    {
        return array_reduce(
            $this->ps,
            fn ($c, $p) => $c && $p->running(),
            true,
        );
    }

    private function wait()
    {
        sleep(config('services.webserver.period'));
    }

    public function tailTask($tail)
    {
        $output = $this->ps['tail']->latestOutput();

        if ($len = strlen($output)) {
            $this->info(now().' Read '.$len.' bytes');
            event(new WebserverLog($output));
            $this->time = time();
        }
    }
}
