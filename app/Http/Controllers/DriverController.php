<?php

namespace App\Http\Controllers;

use App\EmployeeSalary;
use App\Model\Car;
use App\Model\Driver;
use App\Model\PaidOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = Driver::with(["salary", "paidSalary", "car"])->orderBy("id", "DESC")->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        $months = PaidOrder::MONTHS;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', 'months'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cars = Car::all();
        $title = 'Ստեղծել ' . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route', 'cars'));
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
            "car_id" => "required|integer"
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել վարորդի անունը',
            'phone.required' => 'Խնդրում եմ նշել վարորդի հեռախոսահամարը',
            'car_id' => 'Խնդրում եմ նշել ավտոաշտարակի դաշտը'
        ];
        $this->validate($request, $rules, $messages);

        $driver = new Driver();
        $driver->name = $request->name;
        $driver->phone = $request->phone;
        $driver->car_id = $request->car_id;
        $driver->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = Driver::with(["paidSalary" => function($q) {
            $q->whereYear('created_at', Carbon::now()->year);
        }])->find($id);
        $title = $driver->name . 'ի աշխատավարձերի ցուցակ';
        $route = self::ROUTE;
        $months = PaidOrder::MONTHS;

        return view(self::FOLDER . '.show', compact('title', 'driver', 'route', 'months'));
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
        $cars = Car::all();
        return view(self::FOLDER . '.edit', compact('title', 'route', 'driver', 'cars'));
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
            "car_id" => "required|integer"
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել հաճախորդի անունը',
            'phone.required' => 'Խնդրում եմ նշել հաճախորդի հեռախոսահամարը',
            'car_id' => 'Խնդրում եմ նշել ավտոաշտարակի դաշտը'
        ];
        $this->validate($request, $rules, $messages);

        $driver->name = $request->name;
        $driver->phone = $request->phone;
        $driver->car_id = $request->car_id;
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

    /**
     * Insert Salary Sum into the storage.
     *
     * @param  $id (driver id)
     * @return redirect
     */

    public function paySalary($id, Request $request)
    {
        $driver = Driver::find($id);
        $paidOrder = new PaidOrder();
        $paidOrder->driver_id = $id;
        $paidOrder->price = - $request->price;
        $paidOrder->at_driver = 0;
        $paidOrder->comment = "Աշխատավարձ ".$driver->name."ին" . $request->comment;
        $paidOrder->type = $request->transfer_type ?? 0;
        $paidOrder->month = $request->month;
        $paidOrder->save();

        return redirect(self::ROUTE);
    }

    /**
     * Update salary
     *
     * @param  \App\Employee  $employee
     * @return
     */
    public function updateGivenSalary($id, Request $request)
    {
        $paidOrder = PaidOrder::find($id);
        $paidOrder->price = - $request->price;
        $paidOrder->month = $request->month;
        $paidOrder->save();

        return redirect()->back();
    }

    public function deleteSalary($id)
    {
        PaidOrder::find($id)->delete();
        return redirect()->back();
    }
}
