<?php

namespace App\Http\Controllers;

use App\Model\Car;
use App\Model\PaidOrder;
use Illuminate\Http\Request;

class CarController extends Controller
{


    const FOLDER = "program.cars";
    const TITLE = "Ավտոաշտարակներ";
    const ROUTE = "/cars";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Car::with("driver")->orderBy("id", "DESC")->get();
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
            "name" => "required|max:191",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել մեքենայի անվանում/համարանիշ',
        ];
        $this->validate($request, $rules, $messages);

        $car = new Car();
        $car->name = $request->name;
        $car->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        $title = 'Փոփոխել ' . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route', 'car'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Car $car)
    {
        $rules = [
            "name" => "required|max:191",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել մեքենայի անվանում/համարանիշ',
        ];
        $this->validate($request, $rules, $messages);

        $car->name = $request->name;
        $car->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        $car->delete();
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
        $paidOrder = new PaidOrder();
        $paidOrder->car_id = $id;
        $paidOrder->price = - $request->price;
        $paidOrder->at_driver = 0;
        $paidOrder->comment = "Մեքենայի ծախս " . $request->comment;
        $paidOrder->type = $request->transfer_type ?? 0;
        $paidOrder->save();

        return redirect(self::ROUTE);
    }
}
