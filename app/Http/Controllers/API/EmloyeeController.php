<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Emloyee;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateEmloyeeRequest;
class EmloyeeController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Gate::allows('isAdmin') || \Gate::allows('isManager')) {
            return Emloyee::latest()->paginate(5);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmloyeeRequest $request)
    {
        //
        return Emloyee::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'qrcode'=>bcrypt($request->name.$request->email),
            'salary' => $request['salary'],
            // 'password' => Hash::make($request['password']),
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
        $emloyee = Emloyee::findOrFail($id);
        $this->validate($request,[
            'email' => 'required|string|email|max:191|unique:Emloyees,email,'.$emloyee->id,
            'name' => 'required|string|max:191',
            'salary' => 'required|numeric|min:1|max:100000000',
        ]);
        $emloyee->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'qrcode'=>bcrypt($request->name.$request->email,$request),
            'salary' => $request['salary'],
            // 'password' => Hash::make($request['password']),
        ]);
        return ['message' => 'C???p nh???t th??nh c??ng'];
    }
    public function search(){

        if ($search = \Request::get('q')) {
            $emloyee = Emloyee::where(function($query) use ($search){
                $query->where('name','LIKE',"%$search%")
                        ->orWhere('email','LIKE',"%$search%");
            })->paginate(20);
        }else{
            $emloyee = Emloyee::latest()->paginate(5);
        }

        return $emloyee;

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        $emloyee = Emloyee::findOrFail($id);
        $emloyee->delete($id);
        return ['message' => 'Xo?? th??nh c??ng'];
    }
}
