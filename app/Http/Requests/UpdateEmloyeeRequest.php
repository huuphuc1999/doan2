<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmloyeeRequest extends FormRequest
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
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|max:191',
            'salary' => 'required|numeric|min:1|max:100000000',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên',
            'name.max' => 'Tên không được vượt quá 191 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Nhập đúng định dạng (vd me@gmail.com)',
            'email.unique' => 'Email này đã tồn tại',
            'email.max' => 'Email không được vượt quá 191 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu quá ngắn',
            'password.max' => 'Mật khẩu không được vượt quá 191 ký tự',
            'salary.required' => 'Vui lòng nhập mức lương',
            'salary.numeric' => 'Mức lương phải là kiểu số nguyên dương',
            'salary.min' => 'Mức lương tối thiểu là 1',
            'salary.max' => 'Mức lương tối đa là 100000000',
        ];
    }
}
