<?php

namespace Webkul\MUMBOS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhaseRequest extends FormRequest
{
    public function authorize()
    {
        return true; // or add your authorization logic
    }

    public function rules()
    {
      
        $phaseId = $this->route('phase')?->id;

       return [
        'name'        => 'required|string|max:255',
        'share_value' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'starts_at'   => 'nullable|date',
        'ends_at'     => 'nullable|date',
    ];
    }
}
