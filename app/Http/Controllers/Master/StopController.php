<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Stop;
use App\Models\StopCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StopController extends Controller
{
    //
    public function index()
    {
        // item_id = 9 => ID Item Parqueo
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 9)
            ->get();
        return view('master.stop.index')->with('menu', $menu);
    }

    public function list()
    {
        $stops = Stop::select(
            'stops.id',
            'stops.code',
            'stops.descripcion',
            'stops.flag',
            'stop_categories.descripcion AS categoria'
        )
            ->join('stop_categories', 'stop_categories.id', '=', 'stops.catstop_id')
            ->get();

        return view('master.stop.components.table')
            ->with('stops', $stops);
    }

    public function create()
    {
        $categories = StopCategory::all();

        return view('master.stop.components.create')
            ->with('categories', $categories);
    }

    public function save(Request $request)
    {
        $codigo = $request->codigo;
        $response = "";

        $existCod = DB::table('stops')->where('code', $codigo)->exists();
        if( $existCod != 1 ) {
            $stop = new Stop();
            $stop->code = $codigo;
            $stop->descripcion = $request->description;
            $stop->catstop_id = $request->category;
            $stop->created_at = date('Y-m-d H:i:s');
            $stop->updated_at = date('Y-m-d H:i:s');

        }else {
            return "202";
        }

        try {
            $stop->save();
            $response = "402";
        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $stop = Stop::find($id);
        $categories = StopCategory::all();

        return view('master.stop.components.edit')
            ->with('stop', $stop)
            ->with('categories', $categories)
            ->with('id', $id);
    }

    public function update(Request $request)
    {
        $id = $request->key;
        $stop = Stop::findOrFail($id);
        $response = "";

        try {
            $stop->code = $request->codigo;
            $stop->descripcion = $request->descripcion;
            $stop->flag = $request->checked;
            $stop->catstop_ID = $request->category;
            $stop->updated_at = date('Y-m-d H:i:s');
            $stop->save();

            $response = "402";

        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }
}
