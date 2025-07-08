<?php

namespace Webkul\MUMBOS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncentiveRequest extends FormRequest
{
    public function authorize()
    {
        return true; // add any auth logic here
    }

    public function rules()
    {
        return [
            'shareholder_id' => 'required|exists:shareholders,id',
            'type'           => 'required|in:first,second,third,other',
            'description'    => 'nullable|string',
            'units'          => 'required|integer|min:0',
        ];
    }
}
