<?php

namespace App\Http\Requests;

use App\Http\ReturnHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class uploadAvatarRequest extends FormRequest
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
            'head_img' => 'required|image',
        ];
    }

    public function messages()
    {
        return [
            'head_img.required' => '头像不能为空',
            'head_img.image' => '上传的文件必须为图片',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        exit(json_encode(ReturnHelper::returnWithStatus(
            $validator->errors()->toArray(),
            6002
        )));
    }
}
