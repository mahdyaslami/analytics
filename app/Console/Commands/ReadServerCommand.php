<?php

namespace App\Console\Commands;

use App\Models\WebserverLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class ReadServerCommand extends Command
{
    protected $signature = 'server:read';

    protected $description = 'Read server logs';

    public function handle(): void
    {
        $reader = Process::command(['node', base_path('read-server.js')]);
        $reader = $reader->start();

        while ($reader->running()) {
            $output = trim($reader->latestOutput());

            if ($len = strlen($output)) {
                $this->line('Read '.$len.' bytes');
                WebserverLog::parse($output);
            }

            $this->wait();
        }
    }

    private function wait()
    {
        sleep(config('services.webserver.period'));
    }
}
