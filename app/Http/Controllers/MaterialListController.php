<?php

namespace App\Http\Controllers;

use App\Model\Material;
use App\Model\MaterialList;
use Illuminate\Http\Request;

class MaterialListController extends Controller
{
    const FOLDER = "program.materialList";
    const TITLE = "Ապրանքի Մուտք";
    const ROUTE = "/material-list";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = MaterialList::orderBy("id", "DESC")->with("material")->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        $units = Material::UNITS;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', "units"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $materials = Material::with(["quantity", "selfPrice"])->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        $units = Material::UNITS;
        return view(self::FOLDER . '.create', compact('title', 'route', 'materials', "units"));
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
            "material_id" => "required|integer",
            "quantity" => "required|numeric|min:0",
            "self_price" => "required|numeric|min:0",
        ];
        $messages = [
            'material_id.required' => 'Խնդրում եմ ընտրել ապրանք',
            'material_id.integer' => 'Խնդրում եմ ընտրել ապրանք',

            'quantity.required' => 'Խնդրում եմ նշել ապրանքի քանակը',
            'quantity.numeric' => 'Խնդրում եմ մուտքագրել ճիշտ քանակ',
            'quantity.min' => 'Քանակը չի կարող փոքր լինել 1 -ից',

            'self_price.required' => 'Խնդրում եմ նշել ապրանքի գումարը',
            'self_price.numeric' => 'Խնդրում եմ մուտքագրել ճիշտ գումար',
            'self_price.min' => 'Ինքնարժեք չի կարող փոքր լինել 1 -ից',

        ];

        $this->validate($request, $rules, $messages);

        $list = new MaterialList();
        $list->material_id = $request->material_id;
        $list->quantity = $request->quantity;
        $list->self_price = $request->self_price;
        $list->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MaterialList  $materialList
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialList $materialList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MaterialList  $materialList
     * @return \Illuminate\Http\Response
     */
    public function edit(MaterialList $materialList)
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.edit', compact('title', 'route', "materialList"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MaterialList  $materialList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialList $materialList)
    {
        $rules = [
            "quantity" => "required|numeric|min:0",
            "self_price" => "required|numeric|min:0",
        ];
        $messages = [
            'quantity.required' => 'Խնդրում եմ նշել ապրանքի քանակը',
            'quantity.numeric' => 'Խնդրում եմ մուտքագրել ճիշտ քանակ',
            'quantity.min' => 'Քանակը չի կարող փոքր լինել 1 -ից',

            'self_price.required' => 'Խնդրում եմ նշել ապրանքի գումարը',
            'self_price.numeric' => 'Խնդրում եմ մուտքագրել ճիշտ գումար',
            'self_price.min' => 'Ինքնարժեք չի կարող փոքր լինել 1 -ից',

        ];

        $this->validate($request, $rules, $messages);

        $materialList->quantity = $request->quantity;
        $materialList->self_price = $request->self_price;
        $materialList->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MaterialList  $materialList
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialList $materialList)
    {
        $materialList->delete();
        return redirect(self::ROUTE);
    }
}
