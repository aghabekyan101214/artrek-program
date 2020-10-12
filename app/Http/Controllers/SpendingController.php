<?php

namespace App\Http\Controllers;

use App\Model\PaidOrder;
use App\Model\Spending;
use Illuminate\Http\Request;

class SpendingController extends Controller
{

    const FOLDER = "program.spendings";
    const TITLE = "Այլ Ծախսեր";
    const ROUTE = "/spendings";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categoriesAll = Spending::select('id', 'name')->get();
        $query = Spending::with(["spendings" => function($q) use($request) {
            if(!is_null($request->registered_from)) {
                $q->whereDate("created_at", ">=", $request->registered_from)->whereDate("created_at", "<=", $request->registered_to);
            }
        }]);
        $this->manageSearch($query, $request);
        if(!is_null($request->registered_from)) {
            $query->whereHas('spendings', function ($q) use ($request) {
                $q->whereDate("created_at", ">=", $request->registered_from)->whereDate("created_at", "<=", $request->registered_to);
            });
        }
        $data = $query->orderBy("id", "DESC")->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', 'categoriesAll', 'request'));
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
            "name" => "required|max:190",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել Այլ Ծախսի Կատեգորիա',
        ];
        $this->validate($request, $rules, $messages);

        $spending = new Spending();
        $spending->name = $request->name;
        $spending->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Spending  $spending
     * @return \Illuminate\Http\Response
     */
    public function show(Spending $spending)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Spending  $spending
     * @return \Illuminate\Http\Response
     */
    public function edit(Spending $spending)
    {
        $title = 'Փոփոխել ' . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route', 'spending'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Spending  $spending
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spending $spending)
    {
        $rules = [
            "name" => "required|max:190",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել Այլ Ծախսի Կատեգորիա',
        ];
        $this->validate($request, $rules, $messages);

        $spending->name = $request->name;
        $spending->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Spending  $spending
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spending $spending)
    {
        $spending->delete();
        return redirect(self::ROUTE);
    }

    public function paySalary($id, Request $request)
    {
        $paidOrder = new PaidOrder();
        $paidOrder->spending_id = $id;
        $spendingCategory = Spending::find($id);
        $paidOrder->price = - $request->price;
        $paidOrder->at_driver = 0;
        $paidOrder->comment = "$spendingCategory->name-ի Ծախս \n" . $request->comment;
        $paidOrder->type = $request->transfer_type ?? 0;
        $paidOrder->save();

        return redirect(self::ROUTE);
    }

    private function manageSearch(&$query, $request)
    {
        if(!is_null($request->category)){
            $query->where('id', $request->category);
        }
    }
}
