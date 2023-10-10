<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuildingRequest;
use App\Models\Building;
use App\Models\Compound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportApartment;
use App\Exports\ExportSheets;
use Carbon\Carbon;
use Cache;



class BuildingController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.building.index',[
                'buildings' => Building::filter($request->all())->paginate(10),
            ]);
        elseif(Auth::user()->role_id == 2){
            $compounds = Compound::where('user_id', Auth::user()->id)->pluck('id');
            
            return view('admin.pages.building.index',[
                'buildings' => Building::WhereHas('compounds', function ($query) use ($compounds) {
                    $query->whereIn('compound_id', $compounds);
                })->paginate(10)
            ]);
        }
        else
            abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upsert(Building $building)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.building.upsert',[
                'building' => $building,
                'compounds'=> Compound::get(),
            ]);
        else
            abort(404);
    }
    
    public function editBuilding(Building $building)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.building.edit',[
                'building' => $building,
                'compounds'=> Compound::get(),
            ]);
        elseif(Auth::user()->role_id == 2){
            if($building->compounds->first()->user_id == Auth::user()->id)
                return view('admin.pages.building.edit',[
                    'building' => $building,
                    'compounds'=> Compound::get(),
                ]);
            else
                abort(404);
        }
        else
            abort(404);
    }
    
    public function exportApartment(Request $request){
        $building = Building::where('id', $request->building_id)->first();

        if(Auth::user()->isSuperAdmin())
            return Excel::download(new ExportApartment($request), 'apartments.xlsx');
        elseif(Auth::user()->role_id == 2){
            if($building->compounds->first()->user_id == Auth::user()->id)
                return Excel::download(new ExportApartment($request), 'apartments.xlsx');
            else
                abort(404);
        }
        else
            abort(404);
    }
    
    public function exportRevenu(Request $request){
        $building = Building::where('id', $request->building_id)->first();
        $excel_name = date('F', strtotime(explode('?', $request->date)[0])) ."-". Carbon::now()->format('H:i:s') ."-". $request->building_name;

        if(Auth::user()->isSuperAdmin())
            return Excel::download(new ExportSheets($request), "$excel_name.xlsx");
        elseif(Auth::user()->role_id == 2){
            if($building->compounds->first()->user_id == Auth::user()->id)
                return Excel::download(new ExportSheets($request), "$excel_name.xlsx");
            else
                abort(404);
        }
        else
            abort(404);
    }

    public function modify(BuildingRequest $request)
    {
        return Building::upsertInstance($request);
    }
    
    public function status(Request $request)
    {
        return Building::statusUpdate($request);
    }
    
    public function name(Request $request)
    {
        return Building::name($request);
    }
    
    public function edit(Request $request)
    {
        return Building::edit($request);
    }
    
    public function appartment(Request $request)
    {
        return Building::appartment($request);
    }
    
    public function appartmentDelete(Request $request)
    {
        return Building::appartmentDelete($request);
    }
    
    public function buildings(Request $request)
    {
        return Building::buildingSelect($request);
    }
    
    public function retrieveUser(Request $request)
    {
        return Building::retrieveUser($request);
    }
    
    public function retrieveCompound(Request $request)
    {
        return Building::retrieveCompound($request);
    }

    public function destroy(Building $building)
    {
        return $building->deleteInstance();
    }

    public function filter(Request $request)
    {
        return view('admin.pages.building.index',[
            'buildings' => Building::filter($request->all())->paginate(10)
        ]);
    }
}