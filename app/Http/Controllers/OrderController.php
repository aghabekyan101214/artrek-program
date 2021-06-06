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
        $query = Order::with(["client", "paidList", "creator"])->orderBy("id", "DESC");
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
            "paid_cash" => "numeric",
            "paid_transfer" => "numeric",
        ];
        $messages = [
            'client_id.required' => 'Խնդրում եմ լրացնել հաճախորդի անունը',
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'paid_cash.required' => 'Խնդրում եմ լրացնել վճարված գումարը',
            'paid_cash.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'paid_transfer.required' => 'Խնդրում եմ լրացնել վճարված գումարը',
            'paid_transfer.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
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
        $order->comment = $request->comment;
        $order->save();
        $order->orderList()->createMany($orderListData);
        if(!empty($laserListData)) {
            $order->laserList()->createMany($laserListData);
        }

        if($request->paid_cash > 0) {
            $paid = new PaidOrder(["price" => $request->paid_cash, "type" => PaidOrder::CASH, 'comment' => "Գովազդի պատվերի գումար " . $order->client->name]);
            $order->paidList()->save($paid);
        }
        if($request->paid_transfer > 0) {
            $paid = new PaidOrder(["price" => $request->paid_transfer, "type" => PaidOrder::TRANSFER, 'comment' => "Գովազդի պատվերի գումար " . $order->client->name]);
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
        ];
        $messages = [
            'client_id.required' => 'Խնդրում եմ լրացնել հաճախորդի անունը',
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
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
        $order->comment = $request->comment;
        $order->save();

        $order->orderList()->delete();

        if(!empty($orderListData)){
            $order->orderList()->createMany($orderListData);
        }

        $order->laserList()->delete();

        if(!empty($laserListData)) {
            $order->laserList()->createMany($laserListData);
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
        $order = Order::with('client')->find($id);
        if ($request->price_cash > 0) {
            $paidOrder = new PaidOrder();
            $paidOrder->order_id = $id;
            $paidOrder->price = $request->price_cash;
            $paidOrder->comment = "Գովազդի պատվերի գումար " . $order->client->name;
            $paidOrder->type = PaidOrder::CASH;
            $paidOrder->save();
        }
        if($request->price_transfer > 0) {
            $paidOrder = new PaidOrder();
            $paidOrder->order_id = $id;
            $paidOrder->price = $request->price_transfer;
            $paidOrder->comment = "Գովազդի պատվերի գումար " . $order->client->name;
            $paidOrder->type = PaidOrder::TRANSFER;
            $paidOrder->save();
        }

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
        DB::beginTransaction();
        if ($request->price_cash > 0) {

            $paidOrder = new PaidOrder();
            $paidOrder->price = -$request->price_cash;
            $paidOrder->type = PaidOrder::CASH;
            $paidOrder->comment = 'Գովազդի պատվերի այլ ծախս';
            $paidOrder->save();

            $spending = new OrderSpendingList();
            $spending->spending_order_id = $id;
            $spending->paid_order_id = $paidOrder->id;
            $spending->price = -$request->price_cash;
            $spending->save();

        }

        if ($request->price_transfer > 0) {

            $paidOrder = new PaidOrder();
            $paidOrder->price = -$request->price_transfer;
            $paidOrder->type = PaidOrder::TRANSFER;
            $paidOrder->comment = 'Գովազդի պատվերի այլ ծախս';
            $paidOrder->save();

            $spending = new OrderSpendingList();
            $spending->spending_order_id = $id;
            $spending->paid_order_id = $paidOrder->id;
            $spending->price = -$request->price_transfer;
            $spending->save();

        }

        DB::commit();

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
