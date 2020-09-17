<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\CraneOrder;
use App\Model\Driver;
use App\Model\DriverSalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\PaidOrder;

class CraneOrderController extends Controller
{

    const FOLDER = "program.craneOrders";
    const TITLE = "Ավտոաշտարակի Պատվերներ";
    const ROUTE = "/crane-orders";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CraneOrder::with(["client", "paidList", "driver"])->orderBy("id", "DESC")->get();
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
        $title = self::TITLE;
        $route = self::ROUTE;
        $clients = Client::all();
        $drivers = Driver::all();
        return view(self::FOLDER . '.create', compact('title', 'route', 'clients', 'drivers'));
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
            "client_id" => "required|integer",
            "driver_id" => "required|integer",
            "price" => "required|numeric",
            "paid" => "required|numeric",
        ];
        $messages = [
            'client_id.required' => 'Խնդրում եմ ընտրել հաճախորդ',
            'driver_id.required' => 'Խնդրում եմ ընտրել վարորդ',
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'paid.required' => 'Խնդրում եմ լրացնել վճարված գումարը',
            'paid.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
        ];
        $this->validate($request, $rules, $messages);

        DB::beginTransaction();

        $order = new CraneOrder();
        $order->client_id = $request->client_id;
        $order->driver_id = $request->driver_id;
        $order->price = $request->price;
        $order->save();

        if($request->paid != 0) {
            $paid = new PaidOrder(["price" => $request->paid, "type" => ($request->transfer_type ?? 0), "at_driver" => ($request->at_driver ?? 0)]);
            $order->paidList()->save($paid);
            $salary = new DriverSalary(["price" => ( $request->paid * Driver::PERCENTAGE / 100 ), "driver_id" => $order->driver_id]);
            $paid->salary()->save($salary);
        }

        DB::commit();
        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\CraneOrder  $craneOrder
     * @return \Illuminate\Http\Response
     */
    public function show(CraneOrder $craneOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\CraneOrder  $craneOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(CraneOrder $craneOrder)
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        $clients = Client::all();
        $drivers = Driver::all();
        return view(self::FOLDER . '.create', compact('title', 'route', 'clients', 'drivers', 'craneOrder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\CraneOrder  $craneOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CraneOrder $craneOrder)
    {
        $rules = [
            "client_id" => "required|integer",
            "driver_id" => "required|integer",
            "price" => "required|numeric",
            "paid" => "required|numeric",
        ];
        $messages = [
            'client_id.required' => 'Խնդրում եմ ընտրել հաճախորդ',
            'driver_id.required' => 'Խնդրում եմ ընտրել վարորդ',
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'paid.required' => 'Խնդրում եմ լրացնել վճարված գումարը',
            'paid.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
        ];
        $this->validate($request, $rules, $messages);
        DB::beginTransaction();

        $craneOrder->client_id = $request->client_id;
        $craneOrder->driver_id = $request->driver_id;
        $craneOrder->price = $request->price;
        $craneOrder->save();

        $paid = PaidOrder::where(["crane_order_id" => $craneOrder->id])->orderBy("id", "DESC")->first() ?? new PaidOrder(["crane_order_id" => $craneOrder->id, "price" => $request->paid, "type" => ($request->transfer_type ?? 0), "at_driver" => ($request->at_driver ?? 0)]);
        $paid->price = $request->paid;
        $paid->type = ($request->transfer_type ?? 0);
        $paid->at_driver = ($request->at_driver ?? 0);
        $paid->save();

        $driverSalary = DriverSalary::where("paid_order_id", $paid->id)->orderBy("id", "DESC")->first() ?? new DriverSalary();
        $driverSalary->paid_order_id = $paid->id;
        $driverSalary->price = ( $request->paid * Driver::PERCENTAGE / 100 );
        $driverSalary->driver_id = $craneOrder->driver_id;
        $driverSalary->save();

        DB::commit();
        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\CraneOrder  $craneOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(CraneOrder $craneOrder)
    {
        $craneOrder->delete();
        return redirect(self::ROUTE);
    }

    public function takeFromDriver($paid_id)
    {
        $paid = PaidOrder::find($paid_id);
        $paid->at_driver = 0;
        $paid->save();

        return redirect(self::ROUTE);
    }

    public function pay($id, Request $request)
    {
        $craneOrder = CraneOrder::find($id);
        $paidOrder = new PaidOrder();
        $paidOrder->crane_order_id = $id;
        $paidOrder->at_driver = ($request->at_driver ?? 0);
        $paidOrder->price = $request->price;
        $paidOrder->type = $request->transfer_type ? 1 : 0;
        $paidOrder->save();
        if($request->price != 0) {
            $salary = new DriverSalary(["price" => ( $request->price * Driver::PERCENTAGE / 100 ), "driver_id" => $craneOrder->driver_id]);
            $paidOrder->salary()->save($salary);
        }

        return redirect(self::ROUTE);
    }
}
