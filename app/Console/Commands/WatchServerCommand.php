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

    public function handle(): void
    {
        $this->init();
        $this->start();

        while ($this->running()) {
            $this->tasks();
            $this->wait();
        }

        $this->printErrors();
    }

    private function init()
    {
        $this->ps['websockets'] = Process::command(['php', base_path('artisan'), 'websockets:serve', '--port='.$this->port()]);
        $this->ps['tail'] = Process::command(['tail', '-f', config('services.webserver.log')]);
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
            $this->info('Read '.$len.' bytes');
            event(new WebserverLog($output));
        }
    }
}
