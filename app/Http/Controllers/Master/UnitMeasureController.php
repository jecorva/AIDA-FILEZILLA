<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\UnitMeasure;
use Exception;
use Illuminate\Http\Request;

class UnitMeasureController extends Controller
{
    //
    public function index()
    {
        // item_id = 6 => ID Item Unidad de Medida
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 6)
            ->get();
        return view('master.um.index')
            ->with('menu', $menu);
    }

    public function list()
    {
        $units = UnitMeasure::all()->sortBy('nombre');

        return view('master.um.components.table')
                ->with('unidades', $units);
    }

    public function create()
    {
        return view('master.um.components.create');
    }

    public function save(Request $request)
    {
        $units = new UnitMeasure();
        $units->siglas = $request->siglas;
        $units->nombre = $request->descripcion;
        $units->created_at = date('Y-m-d H:i:s');
        $units->updated_at = date('Y-m-d H:i:s');
        $response = "";

        try {
            $units->save();
            $response = "402";

        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $unit = UnitMeasure::find($id);

        return view('master.um.components.edit')
                ->with('unidad', $unit)
                ->with('id', $id);
    }

    public function update(Request $request)
    {
        $id = $request->key;
        $unit = UnitMeasure::findOrFail($id);
        $response = "";

        $unit->siglas = $request->siglas;
        $unit->nombre = $request->nombre;
        $unit->flag = $request->checked;
        $unit->updated_at = date('Y-m-d H:i:s');
        try {
            $unit->save();

            $response = "402";
        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }
}
