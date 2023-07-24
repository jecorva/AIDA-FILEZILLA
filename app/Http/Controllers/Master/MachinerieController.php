<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\CategoriaMaquinaria;
use App\Models\Machinerie;
use App\Models\MenuItem;
use Exception;
use Illuminate\Http\Request;

class MachinerieController extends Controller
{
    //
    public function index()
    {
        // item_id = 7 => ID Item Unidad de Medida
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 7)
            ->get();
        return view('master.machinerie.index')
            ->with('menu', $menu);
    }

    public function list()
    {
        $machineries = Machinerie::select(
            'machineries.id', 'machineries.code_nisira', 'machineries.code_abby', 'machineries.nombre', 'machineries.flag',
            'parkings.nombre as parking'
        )   ->join('parkings', 'parkings.id', '=', 'machineries.parking_id')
            //->orderBy('machineries.nombre')
            ->get();

        return view('master.machinerie.components.table')
                ->with('maquinarias', $machineries);
    }

    public function update_state(Request $request)
    {
        $id = $request->id;

        $machinerie = Machinerie::findOrFail($id);
        $response = "";

        try {
            $machinerie->flag = $request->flag;
            $machinerie->updated_at = date('Y-m-d H:i:s');
            $machinerie->save();

            $response = "402";
        }catch(Exception $e) {
            $response = "303";

        }

        return $response;
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $machinerie = Machinerie::find($id);

        $categorias = CategoriaMaquinaria::all();

        return view('master.machinerie.components.edit')
            ->with('maquinaria', $machinerie)
            ->with('categorias', $categorias)
            ->with('id', $id);
    }

    public function update(Request $request)
    {
        $id = $request->key;
        $maquinaria = Machinerie::findOrFail($id);
        $response = "";

        try{
            $maquinaria->code_abby = $request->cod_abby;
            $maquinaria->cat_maquinaria_id = $request->categoria_id;
            $maquinaria->updated_at = date('Y-m-d H:i:s');
            $maquinaria->save();

            $response = "402";

        }catch(Exception $e) {
            //$response = $e->getMessage();
            $response ='303';
        }

        return $response;
    }
}
