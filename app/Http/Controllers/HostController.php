<?php

namespace App\Http\Controllers;

use App\Models\WebserverLog;

class HostController extends Controller
{
    public function index()
    {
        $hosts = WebserverLog::groupBy('host')->get('host as title')->sortBy('title');

        return inertia('Hosts', compact('hosts'));
    }

    public function logs(string $host)
    {
        $logs = WebserverLog::whereHost($host)->orderByDesc('time_local')->paginate(30);

        return inertia('Hosts/Logs', compact('logs'));
    }
}
