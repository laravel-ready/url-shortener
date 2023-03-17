<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateShortUrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            'url' => 'required|url',
            'type' => 'required|in:random,custom,emoji_random,emoji_custom',
            'title' => 'nullable|string|min:3|max:200',
            'description' => 'nullable|string|min:3|max:1000',
            'status' => 'nullable|boolean',
            'delay' => 'nullable|integer|min:0|max:1000',
            'expire_date' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:' . date(DATE_ATOM),
            'password' => 'nullable|string|min:3|max:100',
        ];
    }
}
