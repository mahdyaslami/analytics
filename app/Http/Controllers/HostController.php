<?php

namespace App\Http\Controllers;

use App\Http\Requests\HostLogsRequest;
use App\Models\WebserverLog;
use Illuminate\Support\Facades\DB;

class HostController extends Controller
{
    public function index()
    {
        $hosts = WebserverLog::groupBy('host')->orderByDesc('logs_count', 'desc')->get(['host as title', DB::raw('count(*) as logs_count')]);;

        return inertia('Hosts', compact('hosts'));
    }

    public function logs(string $host, HostLogsRequest $request)
    {
        $logs = WebserverLog::whereHost($host)->orderByDesc('time_local')->paginate(30);

        return inertia('Hosts/Logs', compact('logs'));
    }
}
