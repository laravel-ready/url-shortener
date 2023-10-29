<?php

namespace LaravelReady\UrlShortener\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnicodeEmojiStatusRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'status' => 'required|boolean',
        ];
    }
}
