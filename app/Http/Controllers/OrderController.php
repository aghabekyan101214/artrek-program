<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\LaserList;
use App\Model\MaterialList;
use App\Model\Order;
use App\Model\Material;
use App\Model\OrderSpending;
use App\Model\OrderSpendingList;
use App\Model\PaidOrder;
use App\Model\Spending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const FOLDER = "program.orders";
    const TITLE = "Պատվերներ";
    const ROUTE = "/orders";

    public function index(Request $request)
    {
        $query = Order::with(["client", "paidList"])->orderBy("id", "DESC");
        $this->manageSearch($query, $request);
        $title = self::TITLE;
        $route = self::ROUTE;
        $units = Material::UNITS;
        $data = $query->get();
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', "units", "request"));
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

    public function show(Order $order)
    {
        $title = "Պատվերի այլ ծախսեր";
        $route = self::ROUTE;
        $spendings = $order->spendings;
        return view(self::FOLDER . '.show', compact('title', 'route', 'order', 'spendings'));
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

    public function addSpending($order_id, Request $request)
    {
        $spending = new OrderSpending();
        $spending->title = $request->title;
        $spending->price = $request->price;
        $spending->order_id = $order_id;
        $spending->save();

        return redirect()->back();
    }

    public function editSpending($spending_id, Request $request)
    {
        $spending = OrderSpending::find($spending_id);
        $spending->title = $request->title;
        $spending->price = $request->price;
        $spending->save();

        return redirect()->back();
    }

    public function paySpending($id, Request $request)
    {
        if($request->price > 0) {
            DB::beginTransaction();

            $paidOrder = new PaidOrder();
            $paidOrder->price = -$request->price;
            $paidOrder->type = 0;
            $paidOrder->comment = 'Գովազդի պատվերի այլ ծախս';
            $paidOrder->save();

            $spending = new OrderSpendingList();
            $spending->spending_order_id = $id;
            $spending->paid_order_id = $paidOrder->id;
            $spending->price = -$request->price;
            $spending->save();

            DB::commit();
        }

        return redirect()->back();
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect(self::ROUTE);
    }

    public function destroyPayment($id)
    {
        PaidOrder::find($id)->delete();
        return redirect()->back();
    }

    public function deleteSpending($id)
    {
        $spending = OrderSpending::find($id);
        $paidOrderIds = $spending->paidList->pluck('paid_order_id');
        DB::beginTransaction();
        PaidOrder::whereIn("id", $paidOrderIds)->delete();
        $spending->delete();
        DB::commit();
        return redirect()->back();
    }

    private function manageSearch(&$query, $request)
    {
        if(!is_null($request->registered_from)) {
            $query->whereDate("created_at", ">=", $request->registered_from)->whereDate("created_at", "<=", $request->registered_to);
        }
        if(!is_null($request->will_be_finished_from)) {
            $query->whereDate("due_date", ">=", $request->will_be_finished_from)->whereDate("due_date", "<=", $request->will_be_finished_to);
        }
    }

}
