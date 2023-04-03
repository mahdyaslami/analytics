<?php

namespace Tests\Feature\HostLogs;

use App\Models\WebserverLog;
use Tests\TestCase;

class HostLogsFilterTest extends TestCase
{
    private $host;

    public function setUp(): void
    {
        parent::setUp();

        $this->login();

        $this->host = parse_url(fake()->url, PHP_URL_HOST);
    }

    public function test_filters_should_be_in_colon_column_colon_filter_format(): void
    {
        $invalidFormats = ['foo:bar', 'foobar', 'foo:bar:', ':foobar:', ':foo::bar:'];
        foreach ($invalidFormats as $format) {
            $this->request($format)->assertInvalid('filters');
        }

        $this->request(':foo:bar')->assertValid('filters');
    }

    public function test_filters_can_be_multiple(): void
    {
        $this->request(':foo:bar:foo2:bar2:foo3:bar3')->assertValid('filters');
    }

    public function test_filter_values_can_contains_space_character(): void
    {
        $this->request(':foo:bar & baring:foo2:bar2 on bar:foo3:bar3')->assertValid('filters');
    }

    public function test_filter_logs(): void
    {
        WebserverLog::factory()->create();
        $logs = WebserverLog::factory(5)->create(['host' => $this->host, 'status' => 404])->sortByDesc('time_local');

        $this->request(':status:404')->assertOk()->assertViewHas('page.props.logs.data', $logs->values()->toArray());
    }

    private function request(string $filters = null)
    {
        $filters = urlencode($filters);
        $query = !$filters ?: "?filters=$filters";

        return $this->get(route('hosts.logs', $this->host) . $query);
    }
}
