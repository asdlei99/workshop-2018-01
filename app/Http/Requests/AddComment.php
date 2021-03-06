<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AddComment extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'body.required' => '评论正文不能为空',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        exit(json_encode(ReturnHelper::returnWithStatus(
            $validator->errors()->toArray(),
            6002
        )));
    }
}
