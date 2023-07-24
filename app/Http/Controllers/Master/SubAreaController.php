<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\SubArea;
use Exception;
use Illuminate\Http\Request;

class SubAreaController extends Controller
{
    //
    public function index()
    {
        // $id = Crypt::encryptString(Auth::user()->id);

        // item_id = 17 => ID Item Sub-Area
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 17)
            ->get();

        return view('master.subareas.index')
            //->with('id_user', $id)
            //->with('user_id', Auth::user()->id )
            ->with('menu', $menu);
    }

    public function save(Request $request)
    {
        $status = '';
        try{
            $subarea = new SubArea();
            $subarea->nombre = $request->name;
            $subarea->area_ID = $request->area_id;
            $subarea->save();
            $status = 200;
            $msg = 'Sub-área generada';

        }catch(Exception $e) {
            $status = 402;
            $msg = $e->getMessage();
        }

        $response = array(
            'status' => $status,
            'message' => $msg
        );

        return response()->json($response);
    }

    public function update(Request $request) {
        $subarea_id = $request->subarea_id;
        $status = '';

        try {
            $subarea = SubArea::findOrFail($subarea_id);
            $subarea->nombre = $request->name;
            $subarea->area_ID = $request->area_id;
            $subarea->save();
            $status = 200;
            $msg = 'Sub-área actualizada';

        }catch(Exception $e) {
            $status = 402;
            $msg = $e->getMessage();
        }

        $response = array(
            'status' => $status,
            'message' => $msg
        );

        return response()->json($response);
    }

    public function delete(Request $request) {
        $subarea_id = $request->id;
        $status = '';

        try {
            $isCorrect = SubArea::where('id', intval($subarea_id))
                                ->delete();
            $status = 200;
            $msg = 'Sub-área elminada';

        }catch(Exception $e) {
            $status = 402;
            $msg = $e->getMessage();
        }

        $response = array(
            'status' => $status,
            'message' => $msg
        );

        return response()->json($response);
    }
}
