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
    public function index(Request $request)
    {
        $query = CraneOrder::with(["client", "paidList", "driver", "creator"])->orderBy("id", "DESC");
        $this->manageSearch($query, $request);
        $data = $query->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', 'request'));
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
            "paid_cash" => "numeric",
            "paid_transfer" => "numeric",
            "description" => "max:3000",
        ];
        $messages = [
            'client_id.required' => 'Խնդրում եմ ընտրել հաճախորդ',
            'driver_id.required' => 'Խնդրում եմ ընտրել վարորդ',
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'paid_cash.required' => 'Խնդրում եմ լրացնել վճարված գումարը',
            'paid_cash.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'paid_transfer.required' => 'Խնդրում եմ լրացնել վճարված գումարը',
            'paid_transfer.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'description.max' => 'Խնդրում եմ լրացնել ոչ ավել քան 3000 նիշ',
        ];
        $this->validate($request, $rules, $messages);

        DB::beginTransaction();

        $order = new CraneOrder();
        $order->client_id = $request->client_id;
        $order->driver_id = $request->driver_id;
        $order->price = $request->price;
        $order->description = $request->description;
        $order->save();

        // Push to drivers salary
        $salary = new DriverSalary(["price" => ( $request->price * Driver::PERCENTAGE / 100 ), "driver_id" => $order->driver_id]);
        $order->salary()->save($salary);

        if($request->paid_cash > 0) {
            $paid = new PaidOrder(["price" => $request->paid_cash, "type" => PaidOrder::CASH, "at_driver" => ($request->at_driver ?? 0), 'comment' => "Ավտոաշտարակի պատվերի գումար " . $order->client->name]);
            $order->paidList()->save($paid);
        }
        if($request->paid_transfer > 0) {
            $paid = new PaidOrder(["price" => $request->paid_transfer, "type" => PaidOrder::TRANSFER, "at_driver" => 0, 'comment' => "Ավտոաշտարակի պատվերի գումար " . $order->client->name]);
            $order->paidList()->save($paid);
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
            "description" => "max:3000",
        ];
        $messages = [
            'client_id.required' => 'Խնդրում եմ ընտրել հաճախորդ',
            'driver_id.required' => 'Խնդրում եմ ընտրել վարորդ',
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'description.max' => 'Խնդրում եմ լրացնել ոչ ավել քան 3000 նիշ',
        ];
        $this->validate($request, $rules, $messages);
        DB::beginTransaction();

        $updateTime = $craneOrder->updated_at;
        // if the old and new prices are the same, no need to change update time
        if($craneOrder->price == $request->price) {
            $craneOrder->updated_at = $updateTime;
        }

        $craneOrder->client_id = $request->client_id;
        $craneOrder->driver_id = $request->driver_id;
        $craneOrder->price = $request->price;
        $craneOrder->description = $request->description;
        $craneOrder->save();

        $driverSalary = DriverSalary::where("crane_order_id", $craneOrder->id)->orderBy("id", "DESC")->first() ?? new DriverSalary();
        $driverSalary->crane_order_id = $craneOrder->id;
        $driverSalary->price = ( $request->price * Driver::PERCENTAGE / 100 );
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
        if ($request->price_cash > 0) {
            $craneOrder = CraneOrder::find($id);
            $paidOrder = new PaidOrder();
            $paidOrder->crane_order_id = $id;
            $paidOrder->at_driver = ($request->at_driver ?? 0);
            $paidOrder->price = $request->price_cash;
            $paidOrder->comment = "Ավտոաշտարակի պատվերի գումար " . $craneOrder->client->name;
            $paidOrder->type = PaidOrder::CASH;
            $paidOrder->save();
        }
        if($request->price_transfer > 0) {
            $craneOrder = CraneOrder::find($id);
            $paidOrder = new PaidOrder();
            $paidOrder->crane_order_id = $id;
            $paidOrder->at_driver = 0;
            $paidOrder->price = $request->price_transfer;
            $paidOrder->comment = "Ավտոաշտարակի պատվերի գումար " . $craneOrder->client->name;
            $paidOrder->type = PaidOrder::TRANSFER;
            $paidOrder->save();
        }

        // Collect the driver salary
//        if($request->price != 0) {
//            $salary = new DriverSalary(["price" => ( $request->price * Driver::PERCENTAGE / 100 ), "driver_id" => $craneOrder->driver_id]);
//            $paidOrder->salary()->save($salary);
//        }

        return redirect(self::ROUTE);
    }

    public function destroyPayment($id)
    {
        PaidOrder::find($id)->delete();
        return redirect(self::ROUTE);
    }

    private function manageSearch(&$query, $request)
    {
        if(!is_null($request->registered_from)) {
            $query->whereDate("created_at", ">=", $request->registered_from)->whereDate("created_at", "<=", $request->registered_to);
        }
    }

}
