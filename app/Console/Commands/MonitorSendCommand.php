<?php

namespace App\Console\Commands;

use App\Console\Concerns\CanLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use React\EventLoop\Loop;
use React\Socket\ConnectionInterface;

class MonitorSendCommand extends Command
{
    use CanLog;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitor:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read webserver logs and send to client';

    protected $socket;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->socket = new \React\Socket\SocketServer('127.0.0.1:8001');
        $this->socket = new \React\Socket\LimitingServer($this->socket, 1);

        $this->socket->on('connection', function (ConnectionInterface $connection) {
            $this->log($connection, 'connected');

            $timer = $this->setAuthTimeout($connection);

            $connection->once(
                'data',
                function ($data) use ($connection, &$timer) {
                    if ($data != 'token') {
                        $this->log($connection, 'forbidden');
                        $connection->close();
                    } else {
                        Loop::cancelTimer($timer);
                        $this->log($connection, 'accepted');
                        $timer = $this->startSending($connection);
                    }
                }
            );

            $connection->on('close', function () use ($connection, $timer) {
                Loop::cancelTimer($timer);
                $this->log($connection, 'disconnected');
            });
        });

        $this->socket->on('error', fn (\Exception $e) => $this->errorLog($e->getMessage()));
    }

    public function setAuthTimeout($connection)
    {
        return Loop::addTimer(5, function () use ($connection) {
            $this->log($connection->getRemoteAddress(), 'timeout');
            $connection->close();
        });
    }

    public function startSending($connection)
    {
        return Loop::addPeriodicTimer(1, function () {
            foreach ($this->socket->getConnections() as $connection) {
                $connection->write(rand()."\n");
            }
        });
    }

    public function log($connection, $state)
    {
        $address = '';

        if (is_string($connection)) {
            $address = $connection;
        } else {
            $address = $connection->getRemoteAddress();
        }

        $message = "[$address $state]";

        Log::channel('stderr')->info($message);
    }
}
