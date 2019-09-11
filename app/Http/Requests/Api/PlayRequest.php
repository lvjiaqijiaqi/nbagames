<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PlayRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'game_id' => 'required|string',
            'C' => 'required|string',
            'PF' => 'required|string',
            'SF' => 'required|string',
            'SG' => 'required|string',
            'PG' => 'required|string'
        ];
    }
}
