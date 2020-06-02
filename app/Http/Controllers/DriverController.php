<?php

namespace App\Http\Controllers;

use App\Model\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{

    const FOLDER = "program.drivers";
    const TITLE = "Վարորդներ";
    const ROUTE = "/drivers";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Driver::orderBy("id", "DESC")->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Ստեղծել ' . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route'));
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
            "phone" => "required",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել վարորդի անունը',
            'phone.required' => 'Խնդրում եմ նշել վարորդի հեռախոսահամարը',
        ];
        $this->validate($request, $rules, $messages);

        $driver = new Driver();
        $driver->name = $request->name;
        $driver->phone = $request->phone;
        $driver->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        $title = 'Փոփոխել ' . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.edit', compact('title', 'route', 'driver'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        $rules = [
            "name" => "required",
            "phone" => "required",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել հաճախորդի անունը',
            'phone.required' => 'Խնդրում եմ նշել հաճախորդի հեռախոսահամարը',
        ];
        $this->validate($request, $rules, $messages);

        $driver->name = $request->name;
        $driver->phone = $request->phone;
        $driver->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect(self::ROUTE);
    }
}
