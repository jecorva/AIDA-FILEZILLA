<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Exception;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function index()
    {
        return view('master.location.index');
    }

    public function list()
    {
        $locations = Location::all()->sortBy('nombre');
        
        return view('master.location.components.table')
                ->with('locations', $locations);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $location = Location::find($id);
        
        return view('master.location.components.edit')
                ->with('location', $location)
                ->with('id', $id);
    }

    public function update(Request $request)
    {
        $id = $request->key;
        $location = Location::findOrFail($id);
        $response = "";

        try {
            $location->ratio = $request->ratio;
            $location->updated_at = date('Y-m-d H:i:s');
            $location->save();
            $response = "402";

        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }
}
