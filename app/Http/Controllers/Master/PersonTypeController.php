<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\PersonType;
use Illuminate\Http\Request;

class PersonTypeController extends Controller
{
    //
    public function index()
    {
        // item_id = 11 => ID Item tipo trabajador
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 11)
            ->get();
        return view('master.persontype.index')->with('menu', $menu);
    }

    public function list()
    {
        $persontypes = PersonType::all()->sortBy('nombre');

        return view('master.persontype.components.table')
                ->with('ttrabajadors', $persontypes);
    }
}
