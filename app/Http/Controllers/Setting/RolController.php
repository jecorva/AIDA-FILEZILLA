<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RolController extends Controller
{
    //
    public function index()
    {
        $roles = Rol::all();

        return view('setting.users.rols.index')
                ->with('roles', $roles);
    }

    public function list()
    {
        $roles = Rol::all();

        return view('setting.users.rols.components.table')
            ->with('roles', $roles);
    }

    public function edit(Request $request)
    {
        $id_rol = Crypt::decryptString($request->id);
        $rol = Rol::find($id_rol);        

        return view('setting.users.rols.components.edit')
            ->with('rol', $rol)            
            ->with('id', $request->id);
    }

    public function update(Request $request)
    {
        $id_rol = Crypt::decryptString($request->key);
        $rol = Rol::findOrFail($id_rol);        
        $response = "";

        try {
            $rol->nombre = $request->nombre;
            $rol->descripcion = $request->descripcion;             
            $rol->updated_at = date('Y-m-d H:i:s');
            $rol->save();           

            $response = "402";
        }catch(Exception $e) {
            $response = "303";
        }      

        return $response;
    }
}
