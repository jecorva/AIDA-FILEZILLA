<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\CategoriaImplemento;
use App\Models\Implement;
use App\Models\MenuItem;
use Exception;
use Illuminate\Http\Request;

class ImplementController extends Controller
{
    //
    public function index()
    {
        // item_id = 4 => ID Item Implementos
       $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 4)
            ->get();
        return view('master.implements.index')
                ->with('menu', $menu);
    }

    public function list()
    {
        $implements = Implement::all()->sortBy('nombre');

        return view('master.implements.components.table')
            ->with('implementos', $implements);
    }

    public function update_state(Request $request) {
        $id = $request->id;
        $implement = Implement::findOrFail($id);
        $response = "";

        try {
            $implement->flag = $request->flag;
            $implement->updated_at = date('Y-m-d H:i:s');
            $implement->save();

            $response = "402";
        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }

    public function get(Request $request)
    {
        $id = $request->id;
        $implement = Implement::find($id);

        $categorias = CategoriaImplemento::all();

        return view('master.implements.components.edit')
            ->with('implemento', $implement)
            ->with('categorias', $categorias)
            ->with('id', $id);
    }

    public function update(Request $request)
    {
        $id = $request->key;
        $implement = Implement::findOrFail($id);
        $response = "";

        try{
            $implement->code_abby = $request->cod_abby;
            $implement->cat_implemento_id = $request->categoria_id;
            $implement->updated_at = date('Y-m-d H:i:s');
            $implement->save();

            $response = "402";

        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }
}
