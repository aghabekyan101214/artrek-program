<?php

namespace App\Http\Controllers;

use App\Model\PaidOrder;
use Illuminate\Http\Request;

class CashDeskController extends Controller
{

    const FOLDER = "program.cashDesk";
    const TITLE = "Գումարի Կառավարում";
    const ROUTE = "/cashdesk";

    public function index(Request $request)
    {
        $q_data = PaidOrder::orderBy("id", "DESC")->where("at_driver", "!=", 1);
        $this->manageSearch($q_data, $request);
        $data = $q_data->get();

        $q_cash = PaidOrder::where("at_driver", "!=", 1)->where(["type" => PaidOrder::CASH]);
        $this->manageSearch($q_cash, $request);
        $cash = $q_cash->sum("price"); // Sum Of Cashes

        $q_transfer = PaidOrder::where("at_driver", "!=", 1)->where(["type" => PaidOrder::TRANSFER]);
        $this->manageSearch($q_transfer, $request);
        $transfer = $q_transfer->sum("price"); // Sum Of Transfers

        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', 'cash', 'transfer', 'request'));
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

    private function manageSearch(&$query, $request)
    {
        if(!is_null($request->registered_from)) {
            $query->whereDate("created_at", ">=", $request->registered_from)->whereDate("created_at", "<=", $request->registered_to);
        }
    }
}
