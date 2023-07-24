<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //
    public function index()
    {
        // item_id = 3 => ID Item Àreas
       $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 3)
            ->get();

        return view('master.areas.index')
                ->with('menu', $menu);
    }

    public function list()
    {
        $areas = Area::all()->sortBy('nombre');

        return view('master.areas.components.table')
                ->with('areas', $areas);
    }
}
