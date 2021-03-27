<?php

namespace App\Http\Controllers;

use App\EmployeeSalary;
use App\Model\PaidOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            "price_cash" => "required|numeric|min:0",
            "price_transfer" => "required|numeric|min:0",
            "comment" => "max:3000",
            "type" => "required|min:-1|max:1|numeric"
        ];
        $messages = [
            'price.required' => 'Խնդրում եմ լրացնել գումարը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
            'type' => 'Խնդրում եմ լրացնել ելք/մուտ դաշտը',
        ];
        $this->validate($request, $rules, $messages);

        if($request->price_cash > 0) {

            $paidOrder = new PaidOrder();
            $paidOrder->type = PaidOrder::CASH;
            $paidOrder->at_driver = 0;
            $paidOrder->comment = $request->comment;
            $paidOrder->price = $request->price_cash * $request->type;
            $paidOrder->save();
            $paidOrder->thread_id = $paidOrder->id;
            $paidOrder->save();

        }
        if ($request->price_transfer > 0) {

            $paidOrderTransfer = new PaidOrder();
            $paidOrderTransfer->type = PaidOrder::TRANSFER;
            $paidOrderTransfer->at_driver = 0;
            $paidOrderTransfer->comment = $request->comment;
            $paidOrderTransfer->price = $request->price_transfer * $request->type;
            if(isset($paidOrder)) {
                $paidOrderTransfer->thread_id = $paidOrder->id;
            }
            $paidOrderTransfer->save();
            if (!isset($paidOrder)) {
                $paidOrderTransfer->thread_id = $paidOrderTransfer->id;
                $paidOrderTransfer->save();
            }

        }

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
            'price.required' => 'Խնդրում եմ լրացնել գումարը',
            'price.numeric' => 'Խնդրում եմ լրացնել ճիշտ թվանշաններ',
        ];
        $this->validate($request, $rules, $messages);

        DB::beginTransaction();

        $paidOrder = PaidOrder::findOrFail($id);
        $paidOrder->price = $request->price * $request->type;
        $paidOrder->at_driver = 0;
//        $paidOrder->type = $request->transfer_type ?? 0;
        $paidOrder->comment = $request->comment;
        $paidOrder->save();

        if(!is_null($paidOrder->employee_salary_id)) {
            $employeeSalary = EmployeeSalary::find($paidOrder->employee_salary_id);
            $employeeSalary->price = -$request->price;
            $employeeSalary->save();
        }

        DB::commit();
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

        if(!is_null($request->sum)) {
            $sign = $request->sum == -1 ? '<' : '>';
            $query->where('price', $sign, 0);
        }

        if(!is_null($request->type)) {
            $column = $request->type == 1 ? 'order_id' : 'crane_order_id';
            $query->whereNotNull($column);
        }
    }
}
