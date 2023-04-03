<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FiltersRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $matches = str($value)->matchAll('/(:\w+:[^:]+)/');

        if ($matches->isEmpty()) {
            $fail('Filters should be in ":name:value" format');
        }
    }
}
