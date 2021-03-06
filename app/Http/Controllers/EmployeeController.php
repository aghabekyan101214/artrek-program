<?php

namespace App\Http\Controllers;

use App\Employee;
use App\EmployeeSalary;
use App\Model\DriverSalary;
use App\Model\PaidOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    const FOLDER = "program.employees";
    const TITLE = "Աշխատակիցներ";
    const ROUTE = "/employees";


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (is_null($request->type)) {
            $data = Employee::with('creator')->orderBy("id", "DESC")->get();
        } else {
            $data = Employee::onlyTrashed()->with('creator')->orderBy("id", "DESC")->get();
        }

        $title = self::TITLE;
        $route = self::ROUTE;
        $months = PaidOrder::MONTHS;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', 'months'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Ավելացնել ' . self::TITLE;
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
            "name" => "required|max:191",
            "phone" => "max:191",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել աշխատակցի անունը',
            'phone.max' => 'Մուտքագրեք առավելագույնը 191 սիմվոլ'
        ];
        $this->validate($request, $rules, $messages);

        $employee = new Employee();
        $employee->name = $request->name;
        $employee->phone = $request->phone;
        $employee->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::withTrashed()->find($id);
        $title = $employee->name . 'ի աշխատավարձերի ցուցակ';
        $route = self::ROUTE;
        $months = PaidOrder::MONTHS;
        return view(self::FOLDER . '.show', compact('title', 'employee', 'route', 'months'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::withTrashed()->find($id);
        $title = 'Փոփոխել ' . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::withTrashed()->find($id);
        $rules = [
            "name" => "required|max:191",
            "phone" => "max:191",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել աշխատակցի անունը',
            'phone.max' => 'Մուտքագրեք առավելագույնը 191 սիմվոլ'
        ];
        $this->validate($request, $rules, $messages);

        $employee->name = $request->name;
        $employee->phone = $request->phone;
        $employee->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::withTrashed()->find($id);
        if($employee->trashed()) {
            $employee->restore();
        } else {
            $employee->delete();
        }
        return redirect(self::ROUTE);
    }

    /**
     * Save the salary in the db.
     *
     * @param  \App\Employee  $employee
     * @return
     */
    public function giveSalary($id, Request $request)
    {
        DB::beginTransaction();
        $employee = Employee::withTrashed()->find($id);
        if ($request->price_cash > 0) {
            $salary = new EmployeeSalary(['price' => -$request->price_cash, 'month' => $request->month, 'year' => $request->year]);
            $employee->salaries()->save($salary);

            $paidOrder = new PaidOrder(['price' => -$request->price_cash, 'comment' => 'Աշխատավարձ ' . $employee->name . 'ին', 'type' => PaidOrder::CASH]);
            $salary->paidSalaries()->save($paidOrder);
        }
        if($request->price_transfer > 0) {
            $salary = new EmployeeSalary(['price' => -$request->price_transfer, 'month' => $request->month, 'year' => $request->year]);
            $employee->salaries()->save($salary);

            $paidOrder = new PaidOrder(['price' => -$request->price_transfer, 'comment' => 'Աշխատավարձ ' . $employee->name . 'ին', 'type' => PaidOrder::TRANSFER]);
            $salary->paidSalaries()->save($paidOrder);
        }

        DB::commit();
        return redirect(self::ROUTE);
    }

    /**
     * Update salary
     *
     * @param  \App\Employee  $employee
     * @return
     */
    public function updateGivenSalary($id, Request $request)
    {
        DB::beginTransaction();

        $salary = EmployeeSalary::find($id);
        $salary->price = -$request->price;
        $salary->month = $request->month;
        $salary->year = $request->year;
        $salary->save();

        $salary->paidSalaries()->update(['price' => -$request->price, 'type' => (is_null($request->transfer_type) ? 0 : 1)]);

        DB::commit();
        return redirect()->back();
    }

    public function deleteSalary($id)
    {
        EmployeeSalary::find($id)->delete();
        return redirect()->back();
    }
}
