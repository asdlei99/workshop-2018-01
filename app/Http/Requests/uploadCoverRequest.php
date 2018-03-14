<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class uploadCoverRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cover_img' => 'required|image',
        ];
    }

    public function messages()
    {
        return [
            'cover_img.required' => '头像不能为空',
            'cover_img.image' => '上传的文件必须为图片',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        exit(json_encode(ReturnHelper::returnWithStatus(
            $validator->errors()->toArray(),
            2006
        )));
    }
}
