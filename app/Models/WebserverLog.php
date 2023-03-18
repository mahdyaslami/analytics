<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebserverLog extends Model
{
    use HasFactory;

    public static function parse(string $text)
    {
        $lines = explode("\n", $text);

        $records = [];
        foreach ($lines as $line) {
            if ($array = json_decode($line, true)) {
                $records[] = $array;
            }
        }

        static::insert($records);
    }
}
