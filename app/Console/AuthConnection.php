<?php

namespace App\Console;

use Illuminate\Support\Facades\Log;
use React\EventLoop\Loop;
use React\Socket\ConnectionInterface;

class AuthConnection
{
    private $connection;

    private $timer;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function onData()
    {
        $this->setAuthTimeout();

        $this->connection->once(
            'data',
            function ($data) {
                if ($this->authenticate($data)) {
                    $this->startSending();
                }

            }
        );

    }

    public function setAuthTimeout()
    {
        $this->timer = Loop::addTimer(5, function () {
            $this->info('timeout');
            $this->connection->close();
        });
    }

    public function authenticate($token)
    {
        if ($token != config('services.monitoring.auth_token')) {
            $this->info('unauthenticated');
            $this->connection->close();
        }

        Loop::cancelTimer($this->timer);
        $this->info('authenticated');
    }

    public function startSending()
    {
        Loop::addPeriodicTimer(1, fn () => $this->connection->write(rand()."\n"));
    }

    public function log($state)
    {
        $address = $connection->getRemoteAddress();

        $message = "[$address $state]";

        Log::channel('stderr')->info($message);
    }
}
