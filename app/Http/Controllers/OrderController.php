<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\LaserList;
use App\Model\MaterialList;
use App\Model\Order;
use App\Model\Material;
use App\Model\PaidOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const FOLDER = "program.orders";
    const TITLE = "Պատվերներ";
    const ROUTE = "/orders";

    public function index()
    {
        $data = Order::with(["client", "paidList"])->orderBy("id", "DESC")->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        $units = Material::UNITS;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', "units"));
    }

    public function create()
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        $clients = Client::all();
        $materials = Material::with("selfPrice")->whereHas("quantity")->selectRaw("id, name")->get()->toArray();
        $laserTypes = LaserList::TYPES;
        $engravingPrice = LaserList::ENGRAVING;
        return view(self::FOLDER . '.create', compact('title', 'route', 'clients', 'materials', 'laserTypes', 'engravingPrice'));
    }

    public function store(Request $request)
    {
        $rules = [
            "client_id" => "required|integer",
            "price" => "required|numeric",
            "paid" => "required|numeric",
        ];
        $messages = [
            'client_id.required' => 'Խնդրում եմ լրացնել հաճախորդի անունը',
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'paid.required' => 'Խնդրում եմ լրացնել վճարված գումարը',
            'paid.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
        ];
        $this->validate($request, $rules, $messages);
        $orderListData = $this->getInsertData($request->data, 0);
        $laserListData = $this->getInsertData($request->data, 1);
        $laserListDataWithoutMaterial = [];

        foreach ($orderListData as $bin => $data) {
            unset($orderListData[$bin]["order_type"]);
            unset($orderListData[$bin]["line_meter"]);
            $orderListData[$bin]["self_price"] = MaterialList::where("material_id", $data["material_id"])->orderBy("id", "desc")->first()["self_price"] ?? 0;
        }

        foreach ($laserListData as $bin => $data) {
            unset($laserListData[$bin]["order_type"]);
            $laserListData[$bin]["self_price"] = MaterialList::where("material_id", $data["material_id"])->orderBy("id", "desc")->first()["self_price"] ?? 0;
        }

        DB::beginTransaction();
        $order = new Order();
        $order->client_id = $request->client_id;
        $order->price = $request->price;
        $order->due_date = $request->due_date;
        $order->save();
        $order->orderList()->createMany($orderListData);
        if(!empty($laserListData)) {
            $order->laserList()->createMany($laserListData);
        }
        if($request->paid != 0) {
            $paid = new PaidOrder(["price" => $request->paid, "type" => ($request->transfer_type ?? 0)]);
            $order->paidList()->save($paid);
        }

        DB::commit();
        return redirect(self::ROUTE);
    }

    public function edit(Order $order)
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        $clients = Client::all();
        $materials = Material::with("selfPrice")->whereHas("quantity")->selectRaw("id, name")->get()->toArray();
        $laserTypes = LaserList::TYPES;
        $engravingPrice = LaserList::ENGRAVING;
        return view(self::FOLDER . '.create', compact('title', 'route', 'clients', 'materials', 'laserTypes', 'order', 'engravingPrice'));
    }

    public function update(Order $order, Request $request)
    {
        $rules = [
            "client_id" => "required|integer",
            "price" => "required|numeric",
            "paid" => "required|numeric",
        ];
        $messages = [
            'client_id.required' => 'Խնդրում եմ լրացնել հաճախորդի անունը',
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'paid.required' => 'Խնդրում եմ լրացնել վճարված գումարը',
            'paid.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
        ];
        $this->validate($request, $rules, $messages);
        $orderListData = $this->getInsertData($request->data, 0);
        $laserListData = $this->getInsertData($request->data, 1);

        foreach ($orderListData as $bin => $data) {
            unset($orderListData[$bin]["order_type"]);
            unset($orderListData[$bin]["line_meter"]);
            $orderListData[$bin]["self_price"] = MaterialList::where("material_id", $data["material_id"])->orderBy("id", "desc")->first()["self_price"] ?? 0;
        }

        foreach ($laserListData as $bin => $data) {
            unset($laserListData[$bin]["order_type"]);
            $laserListData[$bin]["self_price"] = MaterialList::where("material_id", $data["material_id"])->orderBy("id", "desc")->first()["self_price"] ?? 0;
        }

        DB::beginTransaction();

        $order->client_id = $request->client_id;
        $order->price = $request->price;
        $order->due_date = $request->due_date;
        $order->save();

        $order->orderList()->delete();

        if(!empty($orderListData)){
            $order->orderList()->createMany($orderListData);
        }

        $order->laserList()->delete();

        if(!empty($laserListData)) {
            $order->laserList()->createMany($laserListData);
        }

        if($request->paid != 0) {
            $paid = PaidOrder::where(["order_id" => $order->id])->orderBy("id", "DESC")->first() ?? new PaidOrder();
            $paid->order_id = $order->id;
            $paid->price = $request->paid;
            $paid->type = ($request->transfer_type ?? 0);
            $paid->save();
        }

        DB::commit();
        return redirect(self::ROUTE);
    }

    private function getInsertData($data, $type)
    {
        $returnData = [];
        foreach ($data as $key => $value) {
            if($value["order_type"] == $type) {
                $returnData []= $value;
            }
        }
        return $returnData;
    }

    public function pay($id, Request $request)
    {
        $paidOrder = new PaidOrder();
        $paidOrder->order_id = $id;
        $paidOrder->price = $request->price;
        $paidOrder->type = $request->transfer_type ? 1 : 0;
        $paidOrder->save();

        return redirect(self::ROUTE);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect(self::ROUTE);
    }

    public function destroyPayment($id)
    {
        PaidOrder::find($id)->delete();
        return redirect(self::ROUTE);
    }

}
