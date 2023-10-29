<?php

namespace LaravelReady\UrlShortener\Http\Requests;

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
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => 'required|url',
            'type' => 'required|in:random,custom,emoji,emoji_custom',
            'short_code' => 'required_if:type,custom,emoji_custom|nullable|string|min:1|max:50',
            'meta.title' => 'nullable|string|min:3|max:200',
            'meta.description' => 'nullable|string|min:3|max:1000',
            'meta.status' => 'nullable|boolean',
            'meta.delay' => 'nullable|integer|min:0|max:1000',
            'meta.expire_date' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:' . date(DATE_ATOM),
            'meta.password' => 'nullable|string|min:3|max:100',
        ];
    }
}
