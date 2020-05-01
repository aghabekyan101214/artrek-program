<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\MaterialList;
use App\Model\Order;
use App\Model\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    const FOLDER = "program.orders";
    const TITLE = "Պատվերներ";
    const ROUTE = "/orders";

    public function index()
    {
        $data = Order::with(["client"])->get();
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
        $materials = Material::whereHas("quantity")->selectRaw("id, name")->get()->toArray();
        return view(self::FOLDER . '.create', compact('title', 'route', 'clients', 'materials'));
    }

    public function store(Request $request)
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

        $insertData = $request->data;
        foreach ($insertData as $bin => $data) {
            $insertData[$bin]["self_price"] = MaterialList::where("material_id", $data["material_id"])->orderBy("id", "desc")->first()["self_price"];
        }
        DB::beginTransaction();

        $order = new Order();
        $order->client_id = $request->client_id;
        $order->price = $request->price;
        $order->due_date = $request->due_date;
        $order->save();
        $order->orderList()->createMany($insertData);

        DB::commit();
        return redirect(self::ROUTE);
    }

}
