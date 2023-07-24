<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\MenuItem;
use App\Models\Person;
use App\Models\PersonType;
use App\Models\Rol;
use App\Models\SubArea;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    //
    public function index()
    {
        // item_id = 10 => ID Item trabajador
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 10)
            ->get();
        return view('master.person.index')->with('menu', $menu);
    }

    public function list()
    {
        $persons = Person::select(
            'people.id',
            'users.dni', 'users.nombres', 'users.apel_pat', 'users.apel_mat', 'users.flag',
            'areas.nombre AS area',
            'person_types.nombre AS tipo'
        )   ->join('users', 'users.id', '=', 'people.user_id')
            ->join('areas', 'areas.id', '=', 'people.area_id')
            ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
            ->where('users.rol_id', '<>', '1')
            ->orderBy('people.created_at', 'asc')
            ->get();

        return view('master.person.components.table')
                ->with('trabajadores', $persons);
    }

    public function create()
    {
        $rols = Rol::all()
                    ->where('id','<>', '1')
                    ->where('id', '<>', '2');

        $areas = Area::all()->sortBy('nombre');
        $personTypes = PersonType::all()->sortBy('nombre');

        return view('master.person.components.create')
                ->with('roles', $rols)
                ->with('areas', $areas)
                ->with('employees', $personTypes);
    }

    public function save_user(Request $request)
    {
        $usuario = new User();
        $response = "";
        $dniExist = "";

        try {
            $dniExist = DB::table('users')->where('dni', $request->dni)->exists();
        }catch(Exception $e) {
            $response = "303";
        }

        if ($dniExist != 1) {
            try {
                $usuario->dni = $request->dni;
                $usuario->nombres = $request->nombres;
                $usuario->apel_pat = $request->apel_pat;
                $usuario->apel_mat = $request->apel_mat;
                $usuario->rol_id   = $request->rol;
                $usuario->created_at =  date('Y-m-d H:i:s');
                $usuario->updated_at = date('Y-m-d H:i:s');
                $usuario->save();

                $dni = $request->dni;
                $sql = "SELECT id FROM users WHERE dni='" . $dni . "'";

                $exec = DB::select($sql);
                $id = Crypt::encryptString($exec[0]->id);

                $response = $id;
            } catch (Exception $e) {
                $message = $e->getMessage();
                $code = $e->getCode();
                $string = $e->__toString();
                $response = $message; // Error al insertar en la base de datos
            }
        } else {
            $response = "202"; // Dni existe
        }

        return $response;
    }

    public function save(Request $request)
    {
        $work = new Person();
        $response = "";
        $id_usuario = Crypt::decryptString($request->iduser);
        $dni = $request->dni;

        try {
            $work->user_id = $id_usuario;
            $work->area_id  = $request->area;
            $work->subarea_id = $request->slt_suba;
            $work->typeperson_id = $request->tipo;
            $work->password_app = md5($dni);
            $work->created_at =  date('Y-m-d H:i:s');
            $work->updated_at = date('Y-m-d H:i:s');
            $work->save();

            $response = "402";
        } catch (Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            $string = $e->__toString();

            $response = "303"; // Error al insertar en la base de datos
        }

        return $response;
    }

    public function edit(Request $request)
    {
        // falta validar usuario
        $id_work = Crypt::decryptString($request->id);
        $person = Person::select(
            'people.id','people.subarea_id',
            'users.dni', 'users.nombres', 'users.apel_pat', 'users.apel_mat', 'users.flag',
            'people.area_id', 'people.typeperson_id', 'users.rol_id'
        )   ->join('users', 'users.id', '=', 'people.user_id')
            ->where('people.id', $id_work)
            ->get();

        $rols = Rol::all()
                    ->where('id','<>', '1')
                    ->where('id', '<>', '2');

        $areas = Area::all()->sortBy('nombre');
        $personTypes = PersonType::all()->sortBy('nombre');

        $area_id = $person[0]->area_id;
        $subareas = SubArea::where('area_ID', $area_id)->get();


        return view('master.person.components.edit')
            ->with('work', $person)
            ->with('roles', $rols)
            ->with('areas', $areas)
            ->with('subareas', $subareas)
            ->with('tipos', $personTypes);
    }

    public function update(Request $request)
    {
        $id = $request->key;
        $trabajador = Person::findOrFail($id);
        $response = "";

        try {
            $trabajador->area_id = $request->sarea;
            $trabajador->subarea_id = $request->subarea;
            $trabajador->typeperson_id = $request->stipo;
            $trabajador->updated_at = date('Y-m-d H:i:s');
            $trabajador->save();
            $response = "402";
        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }
}
