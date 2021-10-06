<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class EmloyeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:6|max:191',
            'salary' => 'required|numeric|min:1|max:100000000',
        ];
    
        $customMessages = [
            'name.required' => 'Vui lòng nhập tên',
            'name.max' => 'Tên không được vượt quá 191 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'name.email' => 'Nhập đúng định dạng (vd me@gmail.com)',
            'name.unique' => 'Email này đã tồn tại',
            'name.max' => 'Email không được vượt quá 191 ký tự',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu quá ngắn',
            'password.max' => 'Mật khẩu không được vượt quá 191 ký tự',
            'salary.required' => 'Vui lòng nhập mức lương',
            'salary.numeric' => 'Mức lương phải là kiểu số nguyên dương',
            'salary.min' => 'Mức lương tối thiểu là 1',
            'salary.max' => 'Mức lương tối đa là 100000000',
        ];
    
        $this->validate($request, $rules, $customMessages);
        //
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role'],
            'salary' => $request['salary'],
            'password' => Hash::make($request['password']),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete($id);
        return ['message' => 'Xoá thành công'];
    }
}
