<?php

namespace App\Http\Requests\video;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required'],
            'title' => ['required', 'string'],
            'video_url' => ['required', 'string'],
        ];
    }
}
