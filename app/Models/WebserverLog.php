<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebserverLog extends Model
{
    use HasFactory;

    const CREATED_AT = 'time_local';
    const UPDATED_AT = null;

    public static function parse(string $text, $extra = [])
    {
        $lines = explode("\n", $text);

        $records = [];
        foreach ($lines as $line) {
            if ($array = json_decode($line, true)) {
                $records[] = array_merge($array, $extra);
            }
        }

        static::insert($records);
    }
}
