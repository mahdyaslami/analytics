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
        $pendingReader = Process::path(base_path())->command(['node', 'read-server.js']);
        $retry = 0;

        while ($retry++ < 5) {
            $this->info('Starting to read server');
            $invokedReader = $pendingReader->start();

            $this->info('Wait 5 seconds for process');
            sleep(5);

            if ($invokedReader?->running()) {
                $retry = 0;
            } else {
                $this->info($invokedReader->latestOutput());
                $this->info($invokedReader->latestErrorOutput());
            }

            while ($invokedReader?->running()) {
                $output = trim($invokedReader->latestOutput());

                if ($len = strlen($output)) {
                    $this->line('Read '.$len.' bytes');
                    WebserverLog::parse($output);
                }

                $this->wait();
            }
        }
    }

    private function wait()
    {
        sleep(config('services.webserver.period'));
    }
}
