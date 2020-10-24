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
    const MONTHS = [
        ['index' => 1, 'name' => 'Հունվար'],
        ['index' => 2, 'name' => 'Փետրվար'],
        ['index' => 3, 'name' => 'Մարտ'],
        ['index' => 4, 'name' => 'Ապրիլ'],
        ['index' => 5, 'name' => 'Մայիս'],
        ['index' => 6, 'name' => 'Հունիս'],
        ['index' => 7, 'name' => 'Հուլիս'],
        ['index' => 8, 'name' => 'Օգոստոս'],
        ['index' => 9, 'name' => 'Սեպտեմբեր'],
        ['index' => 10, 'name' => 'Հոկտեմբեր'],
        ['index' => 11, 'name' => 'Նոյեմբեր'],
        ['index' => 12, 'name' => 'Դեկտեմբեր'],
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Employee::orderBy("id", "DESC")->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        $months = self::MONTHS;
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
    public function show(Employee $employee)
    {
        $title = $employee->name . 'ի աշխատավարձերի ցուցակ';
        $route = self::ROUTE;
        $months = self::MONTHS;
        return view(self::FOLDER . '.show', compact('title', 'employee', 'route', 'months'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
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
    public function update(Request $request, Employee $employee)
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
    public function destroy(Employee $employee)
    {
        $employee->delete();
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

        $employee = Employee::find($id);
        $salary = new EmployeeSalary(['price' => -$request->price, 'month' => $request->month]);
        $employee->salaries()->save($salary);

        $paidOrder = new PaidOrder(['price' => -$request->price, 'comment' => 'Աշխատավարձ ' . $employee->name . 'ին']);
        $salary->paidSalaries()->save($paidOrder);

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
        $salary->save();

        $salary->paidSalaries()->update(['price' => -$request->price]);

        DB::commit();
        return redirect()->back();
    }

    public function deleteSalary($id)
    {
        EmployeeSalary::find($id)->delete();
        return redirect()->back();
    }
}
