<?php

namespace Tests\Feature;

use App\Models\WebserverLog;
use Tests\TestCase;

class HostsTest extends TestCase
{
    public function test_guest_cant_visit_hosts_page(): void
    {
        $this->request()->assertRedirect(route('login'));
    }

    public function test_index_should_show_hosts_page(): void
    {
        $this->login();

        $hosts = WebserverLog::factory(10)
            ->create()
            ->map(fn ($wl) => ['title' => $wl->host, 'logs_count' => 1])
            ->sortByDesc('title');

        $this->request()
            ->assertOk()
            ->assertViewHas('page.component', 'Hosts')
            ->assertViewHas('page.props.hosts', $hosts->values()->toArray());
    }

    private function request()
    {
        return $this->get(route('hosts.index'));
    }
}
