<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\CategoriaImplemento;
use App\Models\Implement;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuPermission;
use App\Models\Request as ModelsRequest;
use App\Models\SubArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComponentController extends Controller
{
    //
    public function card_menu()
    {
        $menus = DB::table('menu')->orderBy('order', 'asc')->get();

        return view('components.card-menu')
                ->with('menus', $menus);
    }

    public function card_submenu(Request $request)
    {
        $menuId = $request->MenuID;
        $userId = $request->userId;

        $items = MenuItem::select(
            'items.id', 'items.name', 'menu_items.id As MenuSubmenuID'
        )   ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu.id', $menuId)
            ->get();

        $opMenu = MenuPermission::select(
            'menu_items.id', 'menu_permissions.permission'
        )   ->join('menu_items', 'menu_items.id', '=', 'menu_permissions.menu_item_id')
            ->where('menu_permissions.user_id', intval($userId))
            ->get();

        $opMenu = json_encode($opMenu);

        return view('components.card-submenu')
                ->with('submenus', $items)
                ->with('name', $request->name)
                ->with('opMenu', $opMenu);
    }

    public function select_option(Request $request)
    {
        $option = $request->option;
        $values = array();
        if( $option == 'implements' ) {
            $values = Implement::all();
        }

        if( $option == 'categoria_implementos') {
            $values = CategoriaImplemento::all();
        }

        return view('components.select-option')
            ->with('implements', $values)
            ->with('option', $option);
    }

    public function select_supervisor(Request $request)
    {
        $query = ModelsRequest::select(
            'requests.anio', 'requests.nro_semana', 'users.apel_pat', 'users.apel_mat', 'users.nombres',
            'people.id'
        )   ->join('people', 'people.id', '=', 'requests.person_id')
            ->join('users', 'users.id', '=', 'people.user_id')
            ->where('requests.nro_semana', $request->nro_semana)
            ->where('requests.anio', $request->anio)
            ->get();

        return view('components.select-supervisor')->with('values', $query);
    }

    public function table_subarea(Request $request)
    {
        $values = SubArea::select(
            'sub_areas.id', 'sub_areas.nombre', 'areas.nombre as nom_area', 'sub_areas.flag'
        )   ->join('areas', 'areas.id', '=', 'sub_areas.area_ID')
            ->get();

        return view('components.subarea-table')
                ->with('subareas', $values);
    }

    public function new_subarea(Request $request)
    {
        $values = Area::all()->sortBy('ASC');
        return view('components.subarea-new')->with('areas', $values);
    }

    public function edit_subarea(Request $request)
    {
        $values = Area::all()->sortBy('ASC');
        $subarea_id = $request->arg;
        $subareas = SubArea::where('id', $request->arg)->get();
        $subarea = $subareas[0];

        return view('components.subarea-edit')
                ->with('areas', $values)
                ->with('area_id', $subarea->area_ID)
                ->with('subarea_id', $subarea_id)
                ->with('name', $subarea->nombre);
    }

    public function subarea_trabajador(Request $request)
    {
        $values = SubArea::where('area_ID', $request->area_id)->get();

        return view('components.trabajador-select-subarea')->with('subareas', $values);
    }
}
