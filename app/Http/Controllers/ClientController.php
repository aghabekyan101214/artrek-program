<?php

namespace App\Http\Controllers;

use App\Model\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ClientController extends Controller
{
    const FOLDER = "program.clients";
    const TITLE = "Հաճախորդներ";
    const ROUTE = "/clients";

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Client::with('creator')->get();
        $title = self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.index', compact('title', 'route', 'data'));
    }

    /**
     * Show the form for creating a new resource.
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "name" => "required",
            "phone" => "required",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել հաճախորդի անունը',
            'phone.required' => 'Խնդրում եմ նշել հաճախորդի հեռախոսահամարը',
        ];
        $this->validate($request, $rules, $messages);

        $client = new Client;
        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->save();

        return redirect(self::ROUTE);
    }

    /**
     * Display the specified resource.
     * @param \App\Model\Client $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Model\Client $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $title = 'Փոփոխել ' . self::TITLE;
        $route = self::ROUTE;
        return view(self::FOLDER . '.edit', compact('title', 'route', 'client'));
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Client        $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $rules = [
            "name" => "required",
            "phone" => "required",
        ];
        $messages = [
            'name.required' => 'Խնդրում եմ նշել հաճախոռդի անունը',
            'phone.required' => 'Խնդրում եմ նշել հաճախոռդի հեռախոսահամարը',
        ];
        $this->validate($request, $rules, $messages);

        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->save();

        return redirect(self::ROUTE);
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Model\Client $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        Client::destroy($client->id);
        return redirect(self::ROUTE);
    }
}
