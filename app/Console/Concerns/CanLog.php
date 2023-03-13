<?php

namespace App\Console\Concerns;

use Illuminate\Support\Facades\Log;

trait CanLog
{
    public function infoLog($message)
    {
        $this->info($message);
        Log::info($message);
    }

    public function errorLog($message)
    {
        $this->error($message);
        Log::error($message);
    }
}
