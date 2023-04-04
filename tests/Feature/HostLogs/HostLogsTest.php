<?php

namespace Tests\Feature\HostLogs;

use App\Models\WebserverLog;
use Tests\TestCase;

class HostLogsTest extends TestCase
{
    private $host;

    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->host = parse_url(fake()->url, PHP_URL_HOST);
    }

    public function test_guest_cant_visit_logs_page(): void
    {
        auth()->logout();

        $this->request()->assertRedirect(route('login'));
    }

    public function test_show_host_logs_page(): void
    {
        $this->request()->assertOk()->assertViewHas('page.component', 'Hosts/Logs');
    }

    public function test_show_thirty_logs_per_page(): void
    {
        $this->request()->assertOk()->assertViewHas('page.props.logs.per_page', 30);
    }

    public function test_show_last_thirty_logs(): void
    {
        $logs = WebserverLog::factory(40)->create(['host' => $this->host])->sortByDesc('time_local');

        $this->request()->assertOk()->assertViewHas('page.props.logs.data', $logs->take(30)->values()->toArray());
    }

    private function request()
    {
        return $this->get(route('hosts.logs', $this->host));
    }
}
