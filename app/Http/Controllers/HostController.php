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
}
