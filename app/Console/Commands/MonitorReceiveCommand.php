<?php

namespace App\Console\Commands;

use App\Console\Concerns\CanLog;
use Illuminate\Console\Command;

class MonitorReceiveCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:receive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive data sended by server';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $connector = new \React\Socket\Connector();

        $connector->connect('127.0.0.1:8001')->then(function (\React\Socket\ConnectionInterface $connection) {
            $connection->pipe(new \React\Stream\WritableResourceStream(STDOUT));
            $connection->write('token');

            // $connection->on('data', function ($data) {
            //     dump($data);
            // });
        }, function (\Exception $e) {
            echo 'Error: '.$e->getMessage().PHP_EOL;
        });
    }
}
