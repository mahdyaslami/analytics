<?php

namespace App\Http\Controllers;

use App\Http\Requests\HostLogsRequest;
use App\Models\WebserverLog;
use Illuminate\Support\Facades\DB;

class HostController extends Controller
{
    public function index()
    {
        $hosts = WebserverLog::groupBy('server_ip', 'host')
            ->orderByDesc('logs_count', 'desc')
            ->get(['host as title', 'server_ip', DB::raw('count(*) as logs_count')]);

        return inertia('Hosts', compact('hosts'));
    }

    public function logs(string $host, HostLogsRequest $request)
    {
        $logs = WebserverLog::whereHost($host)
            ->when($request->filters, function ($query) use ($request) {
                $request->filters->each(
                    fn ($f) => $query->where($f->column, 'like', $f->search)
                );

                return $query;
            })
            ->orderByDesc('time_local')
            ->paginate(30)
            ->appends($request->query());

        return inertia('Hosts/Logs', compact('logs'));
    }
}
