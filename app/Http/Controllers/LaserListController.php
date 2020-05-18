<?php

namespace App\Http\Controllers;

use App\Model\Client;
use App\Model\LaserList;
use App\Model\Order;
use Illuminate\Http\Request;
use App\Model\Material;

class LaserListController extends Controller
{

    const FOLDER = "program.lasers";
    const TITLE = "Լազեր";
    const ROUTE = "/laser";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Order::whereHas("laserList")->with("laserList")->orderBy("id", "DESC")->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        $types = LaserList::TYPES;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data', "types"));
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
        $types = LaserList::TYPES;
        $clients = Client::orderBy("id", "DESC")->get();
        $materials = Material::whereHas("quantity")->selectRaw("id, name")->get()->toArray();
        return view(self::FOLDER . '.create', compact('title', 'route', "units", "types", "clients", "materials"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LaserList  $laserList
     * @return \Illuminate\Http\Response
     */
    public function show(LaserList $laserList)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LaserList  $laserList
     * @return \Illuminate\Http\Response
     */
    public function edit(LaserList $laserList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LaserList  $laserList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LaserList $laserList)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LaserList  $laserList
     * @return \Illuminate\Http\Response
     */
    public function destroy(LaserList $laserList)
    {
        //
    }
}
