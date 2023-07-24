<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuPermission;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    //
    public function index()
    {
       // item_id = 2 => ID Item Usuarios
       $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 2)
            ->get();
        return view('setting.permission.permisos')
                ->with('menu', $menu);
    }

    public function list_permission()
    {
        $rows = User::where('rol_id', '<>', 4)
                        ->where('id', '<>', 1)
                        ->get();

        $response = array(
            'total' => count($rows),
            'rows' => $rows
        );

        return response()->json($response);
    }

    public function save(Request $request)
    {
        $response = array();

        $userId = $request->userId;
        $menuId = $request->menuId;
        $menusubmenus = $request->menusubmenus != '' ? $request->menusubmenus : []; // Solo valores checked(Seleccionados)


        try {
            MenuPermission::join('menu_items', 'menu_items.id', '=', 'menu_permissions.menu_item_id' )
                                ->where('menu_items.menu_id', $menuId)
                                ->where('menu_permissions.user_id', $userId)
                                ->update(['permission'=>0],);
        }catch(Exception $e) {
            return response()
                    ->json( array(
                            'status'=> 500,
                            'response'=> 'Error update [menu_permission.Permiso=0]',
                            'message'=> $e->getMessage()));
        }


        try {
            foreach( $menusubmenus as $menusubmenu ) {
                $update = MenuPermission::where('menu_item_id', $menusubmenu)
                                    ->where('user_id', $userId)
                                    ->update(['permission'=>1]);
                if( $update == 0 ) {
                    $option = new MenuPermission();
                    $option->user_id = $userId;
                    $option->menu_item_id = $menusubmenu;
                    $option->permission = 1;
                    $option->created_at = date('Y-m-d H:i:s');
                    $option->updated_at = date('Y-m-d H:i:s');
                    $option->save();
                }
            }
        }catch(Exception $e) {
            return response()
                    ->json( array(
                            'status'=> 500,
                            'response'=> 'Error update/insert [menu_users.Permiso=1]',
                            'message'=> $e->getMessage()));
        }


        $response = array(
            'status'=> 200,
            'response'=> 'OK' );

        return response()->json($response);

    }
}
