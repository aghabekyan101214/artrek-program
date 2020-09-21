<?php

namespace App\Http\Controllers;

use App\Model\PaidOrder;
use Illuminate\Http\Request;

class CashDeskController extends Controller
{

    const FOLDER = "program.cashDesk";
    const TITLE = "Գումարի Կառավարում";
    const ROUTE = "/cashdesk";

    public function index()
    {
        $data = PaidOrder::orderBy("id", "DESC")->where("at_driver", "!=", 1)->get();
        $cash = PaidOrder::where("at_driver", "!=", 1)->where(["type" => PaidOrder::CASH])->sum("price"); // Sum Of Cashes
        $transfer = PaidOrder::where("at_driver", "!=", 1)->where(["type" => PaidOrder::TRANSFER])->sum("price"); // Sum Of Transfers
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', 'cash', 'transfer'));
    }

    public function create()
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route'));
    }

    public function store(Request $request)
    {
        $rules = [
            "price" => "required|numeric",
            "comment" => "max:3000",
            "type" => "required|min:-1|max:1|numeric"
        ];
        $messages = [
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'type' => 'Խնդրում եմ լրացնել ելք/մուտ դաշտը',
        ];
        $this->validate($request, $rules, $messages);

        $paidOrder = new PaidOrder();
        $paidOrder->price = $request->price * $request->type;
        $paidOrder->at_driver = 0;
        $paidOrder->type = $request->transfer_type ?? 0;
        $paidOrder->comment = $request->comment;
        $paidOrder->save();

        return redirect(self::ROUTE);
    }

    public function edit($id)
    {
        $paidOrder = PaidOrder::findOrFail($id);
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route', 'paidOrder'));
    }

    public function update($id, Request $request)
    {
        $rules = [
            "price" => "required|numeric",
            "comment" => "max:3000"
        ];
        $messages = [
            'price.required' => 'Խնդրում եմ լրացնել պատվերի գինը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
        ];
        $this->validate($request, $rules, $messages);

        $paidOrder = PaidOrder::findOrFail($id);
        $paidOrder->price = $request->price * $request->type;
        $paidOrder->at_driver = 0;
        $paidOrder->type = $request->transfer_type ?? 0;
        $paidOrder->comment = $request->comment;
        $paidOrder->save();

        return redirect(self::ROUTE);
    }

    public function destroy($id, Request $request)
    {
        PaidOrder::find($id)->delete();
        $redirect = $request->back_route ?? self::ROUTE;
        return redirect($redirect);
    }
}
