<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->is_editor;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        Validator::extend('not_copyed_image', function ($attribute, $value, $parameters) {
            return !str_contains($value, ";base64,");
        });

        return [
            'title'       => 'required',
            // 'keywords'    => 'required',
            // 'description' => 'required|min:10',
            'body'        => 'required|min:20|not_copyed_image',
        ];
    }
}
