<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebserverLog extends Model
{
    use HasFactory;

    public static function parse(string $logs)
    {
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $logs) as $line){
            if ($log = json_decode($line, true)) {
                static::insert($log);
            }
        } 
    }
}
