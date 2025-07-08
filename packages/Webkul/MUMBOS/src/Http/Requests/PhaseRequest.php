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
        // On update, ignore the current record’s unique name
        $phaseId = $this->route('phase')?->id;

        return [
            'name'        => "required|string|unique:phases,name,{$phaseId}",
            'share_value' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
        ];
    }
}
