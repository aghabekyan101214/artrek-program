<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    const FOLDER = "program.staff";
    const TITLE = "Անձնակազմ";
    const ROUTE = "/staffs";


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::where('role', User::ROLES['admin'])->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".index", compact("title", "route", 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Ստեղծել '.self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".create", compact("title", "route"));
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
            "name" => "required",
            "username" => "required|unique:users,username",
            "password" => "required|min:6",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել աշխատողի անունը',
            'username.required' => 'Խնդրում եմ նշել աշխատողի օգտանունը',
            'username.unique' => 'Այսպիսի օգտանուն արդեն գոյություն ունի',
            'password.required' => 'Խնդրում եմ նշել աշխատողի գախտնաբառը',
            'password.min' => 'Գախտնաբառը պետք է պաորունակի 6 կամ ավել նիշ',
        ];
        $this->validate($request, $rules, $messages);

        $user = new User;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect(self::ROUTE);
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $title = 'Փոփոխել '.self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . ".edit", compact("title", "route", 'user'));
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
        $rules = [
            "name" => "required",
            "username" => "required|unique:users,username,". $id,
            "password" => "required|min:6",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել աշխատողի անունը',
            'username.required' => 'Խնդրում եմ նշել աշխատողի օգտանունը',
            'username.unique' => 'Այսպիսի օգտանուն արդեն գոյություն ունի',
            'password.required' => 'Խնդրում եմ նշել աշխատողի գախտնաբառը',
            'password.min' => 'Գախտնաբառը պետք է պաորունակի 6 կամ ավել նիշ',
        ];
        $this->validate($request, $rules, $messages);

        $user = User::find($id);
        $user->name = $request->name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect(self::ROUTE);
    }
}
