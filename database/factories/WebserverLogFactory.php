<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WebserverLog>
 */
class WebserverLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'host' => fake()->url,
            'port' => fake()->randomNumber(4),
            'remote_addr' => fake()->ipv4,
            'remote_user' => '',
            'time_local' => fake()->dateTime,
            'request' => 'HEAD ' . fake()->url . ' HTTP/1.1',
            'status' => fake()->randomElement(['200', '400', '500']),
            'body_bytes_sent' => fake()->numberBetween(0, 1000),
            'referer' => '',
            'user_agent' => fake()->userAgent,
        ];
    }
}
