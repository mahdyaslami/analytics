<?php

namespace Database\Seeders;

use App\Models\WebserverLog;
use Illuminate\Database\Seeder;

class WebserverLogSeeder extends Seeder
{
    public function run(): void
    {
        WebserverLog::factory(20)->create();
    }
}
