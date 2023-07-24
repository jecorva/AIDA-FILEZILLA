<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Person;
use App\Models\Rol;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        // item_id = 1 => ID Item Usuarios
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 1)
            ->get();

        $users = User::select(
            'users.id', 'users.dni', 'users.nombres', 'users.apel_pat', 'users.apel_mat', 'users.email',
            'rols.nombre as nameRol', 'users.flag'
        )   ->join('rols', 'rols.id', '=', 'users.rol_id')
            ->get();

        $rols = Rol::all();

        return view('setting.users.index')
                ->with('usuarios', $users)
                ->with('menu', $menu)
                ->with('roles', $rols);
    }

    public function list()
    {
        $users = User::select(
            'users.id', 'users.dni', 'users.nombres', 'users.apel_pat', 'users.apel_mat', 'users.email',
            'rols.nombre as nameRol', 'users.flag', 'users.rol_id'
        )   ->join('rols', 'rols.id', '=', 'users.rol_id')
            ->orderBy('users.created_at')
            ->get();

        return view('setting.users.components.table')
                ->with('usuarios', $users);
    }

    public function create()
    {
        $rols = Rol::all();

        return view('setting.users.components.create')
                ->with('roles', $rols);
    }

    public function save(Request $request)
    {
        $usuario = new User();
        $trabajador = new Person();
        $response = "";
        $pass = Hash::make($request->password);

        $emailExist = DB::table('users')->where('email', $request->email)->exists();
        $dniExist = DB::table('users')->where('dni', $request->dni)->exists();

        if ($emailExist != 1) {
            if ($dniExist != 1) {
                try {
                    $usuario->dni = $request->input('dni');
                    $usuario->nombres = $request->nombres;
                    $usuario->apel_pat = $request->apel_pat;
                    $usuario->apel_mat = $request->apel_mat;
                    $usuario->email = $request->email;
                    $usuario->rol_id = $request->rol;
                    $usuario->password = $pass;
                    $usuario->created_at =  date('Y-m-d H:i:s');
                    $usuario->updated_at = date('Y-m-d H:i:s');
                    $usuario->save();

                    $dni = $request->input('dni');
                    $sql = "SELECT id FROM users WHERE dni='" . $dni . "'";
                    $exec = DB::select($sql);
                    $id = $exec[0]->id;

                    $trabajador->user_id = $id;
                    $trabajador->area_id = 15; // Predeterminado Acuicola
                    $trabajador->typeperson_id = 6; // Predeterminado Estandar
                    $trabajador->password_app = md5($request->password);
                    $trabajador->created_at =  date('Y-m-d H:i:s');
                    $trabajador->updated_at = date('Y-m-d H:i:s');
                    $trabajador->save();

                    $response = "402";
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    $code = $e->getCode();
                    $string = $e->__toString();
                    $response = "303"; // Error al insertar en la base de datos
                }
            } else {
                $response = "202"; // Dni existe
            }
        } else {
            $response = "101"; // Email existe
        }

        return $response; // Insertado correctamente
    }

    public function edit(Request $request)
    {
        $roles = Rol::all();
        $user_id = Crypt::decryptString($request->id);
        $usuario = User::find($user_id);


        return view('setting.users.components.edit')
            ->with('roles', $roles)
            ->with('usuario', $usuario)
            ->with('id', $request->id);
    }

    public function update(Request $request)
    {
        $id = Crypt::decryptString($request->key);
        $usuario = User::findOrFail($id);
        $response = "";
        $email = "";
        $dni = "";
        $error = false;

        // Verificar email
        if( $request->email == $request->email_old ) {
            $email = $request->email_old;
        }else {
            $emailExist = DB::table('users')->where('email', $request->email)->exists();
            if( $emailExist != 1 ) {
                $email = $request->email;
            }else {
                $response = "101"; // Nuevo email existe
                $error = true;
            }
        }

        // Verificar dni
        if( $request->dni == $request->dni_old ) {
            $dni   = $request->dni_old;
        }else {
            $dniExist = DB::table('users')->where('dni', $request->dni)->exists();
            if( $dniExist != 1 ) {
                $dni = $request->dni;
            }else {
                $response = "202"; // Nuevo dni existe
                $error = true;
            }
        }

        if( $error ) {
            return $response; // Retorna el error
        }else {
            try {
                $usuario->dni = $dni;
                $usuario->nombres = $request->nombres;
                $usuario->apel_pat = $request->apel_pat;
                $usuario->apel_mat = $request->apel_mat;
                $usuario->email = $email;
                $usuario->rol_id = $request->rol;
                $usuario->updated_at = date('Y-m-d H:i:s');
                $usuario->save();

                $response = "402";
            } catch (Exception $e) {
                $response = "303"; // Error al insertar en la base de datos
            }
        }

        return $response; // Retorna todo correcto
    }

    public function update_pass(Request $request)
    {
        $id = Crypt::decryptString($request->key);
        $usuario = User::findOrFail($id);
        $response = "";
        $email = "";
        $dni = "";
        $error = false;
        $pass = Hash::make($request->pass);

        // Verificar email
        if( $request->email == $request->email_old ) {
            $email = $request->email_old;
        }else {
            $emailExist = DB::table('users')->where('email', $request->email)->exists();
            if( $emailExist != 1 ) {
                $email = $request->email;
            }else {
                $response = "101"; // Nuevo email existe
                $error = true;
            }
        }

        // Verificar dni
        if( $request->dni == $request->dni_old ) {
            $dni   = $request->dni_old;
        }else {
            $dniExist = DB::table('users')->where('dni', $request->dni)->exists();
            if( $dniExist != 1 ) {
                $dni = $request->dni;
            }else {
                $response = "202"; // Nuevo dni existe
                $error = true;
            }
        }

        if( $error ) {
            return $response; // Retorna el error
        }else {
            try {
                $usuario->dni = $dni;
                $usuario->nombres = $request->nombres;
                $usuario->apel_pat = $request->apel_pat;
                $usuario->apel_mat = $request->apel_mat;
                $usuario->email = $email;
                $usuario->rol_id = $request->rol;
                $usuario->password = $pass;
                $usuario->updated_at = date('Y-m-d H:i:s');
                $usuario->save();

                $response = "402";
            } catch (Exception $e) {
                $response = "303"; // Error al insertar en la base de datos
            }
        }

        return $response; // Retorna todo correcto
    }
}
