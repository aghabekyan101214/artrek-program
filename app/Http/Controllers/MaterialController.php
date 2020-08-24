<?php

namespace App\Http\Controllers;

use App\Model\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    const FOLDER = "program.materials";
    const TITLE = "Ապրանքներ";
    const ROUTE = "/materials";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Material::with("quantity")->get();
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
        $title = 'Ստեղծել ' . self::TITLE;
        $route = self::ROUTE;
        $units = Material::UNITS;
        return view(self::FOLDER . '.create', compact('title', 'route', "units"));
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
            "unit" => "required|integer",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել նյութի անունը',
            'unit.required' => 'Խնդրում եմ նշել Չափման միավորը',
        ];
        $this->validate($request, $rules, $messages);

        $material = new Material();
        $material->name = $request->name;
        $material->unit = $request->unit;
        $material->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        $title = 'Փոփոխել ' . self::TITLE;
        $route = self::ROUTE;
        $units = Material::UNITS;
        return view(self::FOLDER . '.create', compact('title', 'route', "units", "material"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Material $material)
    {
        $rules = [
            "name" => "required|max:191",
            "unit" => "required|integer",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել նյութի անունը',
            'unit.required' => 'Խնդրում եմ նշել Չափման միավորը',
        ];
        $this->validate($request, $rules, $messages);

        $material->name = $request->name;
        $material->unit = $request->unit;
        $material->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        $material->delete();
        return redirect(self::ROUTE);
    }
}
