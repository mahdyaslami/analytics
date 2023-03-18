<?php

namespace App\Console\Commands;

use App\Events\WebserverLog;
use Illuminate\Console\Command;

class WatchServerCommand extends Command
{
    protected $signature = 'server:watch';

    protected $description = 'Watch the state of server';

    public function handle(): void
    {
        while (true) {
            event(new WebserverLog(rand()));
            sleep(1);
        }
    }
}
