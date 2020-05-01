<?php

namespace App\Http\Controllers;

use App\Model\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    const FOLDER = "program.services";
    const TITLE = "Ծառայություններ";
    const ROUTE = "/services";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Service::all();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = self::TITLE;
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
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել հաճախորդի անունը',
        ];
        $this->validate($request, $rules, $messages);

        $service = new Service();
        $service->name = $request->name;
        $service->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.create', compact('title', 'route', "service"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $rules = [
            "name" => "required|max:191",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել հաճախորդի անունը',
        ];
        $this->validate($request, $rules, $messages);

        $service->name = $request->name;
        $service->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect(self::ROUTE);
    }
}
