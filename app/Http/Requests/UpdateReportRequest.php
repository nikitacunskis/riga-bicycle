<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [     
            'place_id' => 'required',
            'event_id' => 'required',
            'womens' => 'required',
            'man' => 'required',
            'radway' => 'required',
            'pavement' => 'required',
            'biekpath' => 'required',
            'child_chairs' => 'required',
            'supermobility' => 'required',
            'to_center' => 'required',
            'from_center' => 'required',
            'children_self' => 'required',
            'children_passanger' => 'required',
        ];
    }
}
