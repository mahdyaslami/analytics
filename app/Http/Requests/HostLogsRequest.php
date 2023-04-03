<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HostLogsRequest extends FormRequest
{
    const FILTERS_FORMAT = '/(:\w+:[^:]+)/';

    private $matches;

    protected function prepareForValidation()
    {
        $this->matches = str($this->filters)->matchAll(self::FILTERS_FORMAT);
    }

    public function rules(): array
    {
        $isValidFilter = function ($attribute, $value, $fail) {
            if ($this->matches->isEmpty()) {
                $fail('Filters should be in ":name:value" format');
            }
        };

        return [
            'filters' => ['string', $isValidFilter]
        ];
    }

    protected function passedValidation()
    {
        if ($this->exists('filters')) {
            $this->merge([
                'filters' => $this->matches->map(function ($f) {
                    $f = str($f)->trim(': ')->explode(':');
                    return (object)['column' => $f[0], 'search' => $f[1]];
                }),
            ]);
        }
    }
}
