<?php

namespace App\Http\Requests;

use App\Http\ReturnHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title' => 'required',
            'body' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '标题不能为空',
            'body.required' => '正文不能为空',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->toArray();
        $expected_errors_array = [];
        foreach ($errors as $error) {
            $expected_errors_array['errors'][] = $error;
        }
        exit(json_encode(ReturnHelper::returnWithStatus(
            $expected_errors_array,
            2001,
            '文章、标题因长度等原因未能被服务器接受'
        )));
    }

}
