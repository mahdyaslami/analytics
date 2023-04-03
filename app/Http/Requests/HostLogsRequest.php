<?php

namespace App\Http\Requests;

use App\Rules\FiltersRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class HostLogsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filters' => ['string', new FiltersRule]
        ];
    }
}
