<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Task;
use App\Models\UnitMeasure;
use Exception;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //
    public function index()
    {
        // item_id = 5 => ID Item Labores
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 5)
            ->get();
        return view('master.task.index')
                ->with('menu', $menu);
    }

    public function list()
    {
        $labores = Task::select(
            'tasks.id', 'tasks.code_nisira', 'tasks.nombre', 'tasks.descripcion', 'tasks.ratio', 'tasks.flag',
            'unit_measures.siglas'
        )   ->join('unit_measures', 'unit_measures.id', '=', 'tasks.um_id')
            ->get();

        return view('master.task.components.table')
                ->with('labores', $labores);
    }

    public function edit(Request $request)
    {
        $task = Task::find($request->id);
        $unit_measures = UnitMeasure::all();

        return view('master.task.components.edit')
                ->with('task', $task)
                ->with('id', $request->id)
                ->with('unit_measures', $unit_measures);
    }

    public function update(Request $request)
    {
        $id = $request->key;
        $task = Task::findOrFail($id);
        $response =  "";
        try {
            $task->ratio =  doubleval($request->ratio);
            $task->um_id = $request->um;
            $task->updated_at = date('Y-m-d H:i:s');
            $task->save();
            $response = "402";
        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }
}
