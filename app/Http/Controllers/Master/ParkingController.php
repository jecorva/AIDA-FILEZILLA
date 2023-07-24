<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Parking;
use Exception;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    //
    public function index()
    {
        // item_id = 8 => ID Item Parqueo
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 8)
            ->get();
        return view('master.parking.index')
                ->with('menu', $menu);
    }

    public function list()
    {
        $parkings = Parking::all()->sortBy('nombre');

        return view('master.parking.components.table')
                ->with('parkings', $parkings);
    }

    public function create()
    {
        return view('master.parking.components.create');
    }

    public function save(Request $request)
    {
        $parking = new Parking();
        $parking->nombre = $request->name;
        $parking->referencia = $request->reference;
        $parking->created_at = date('Y-m-d H:i:s');
        $parking->updated_at = date('Y-m-d H:i:s');
        $response = "";

        try {
            $parking->save();
            $response = "402";

        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $parking = Parking::find($id);

        return view('master.parking.components.edit')
                ->with('parking', $parking)
                ->with('id', $id);
    }

    public function update(Request $request)
    {
        $id = $request->key;
        $parking = Parking::findOrFail($id);
        $response = "";

        $parking->nombre = $request->name;
        $parking->referencia = $request->reference;
        $parking->flag = $request->checked;
        $parking->updated_at = date('Y-m-d H:i:s');
        try {
            $parking->save();

            $response = "402";
        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }
}
