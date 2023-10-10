<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaintenanceRequest;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.maintenance.index',[
                'maintenances' => Maintenance::filter($request->all())->paginate(10),
            ]);
        elseif(Auth::user()->role_id == 2)
            return view('admin.pages.maintenance.index',[
                'maintenances' => Maintenance::where('user_id', Auth::user()->id)->paginate(10),
            ]);
        else 
            abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upsert(Maintenance $maintenance)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.maintenance.upsert', [
                'maintenance' => $maintenance,
            ]);
        else 
            abort(404);
    }
    
    public function add()
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.maintenance.add');
        else 
            abort(404);
    }
    
    public function building(Request $request)
    {
        return Maintenance::buildingSelect($request);
    }

    public function modify(MaintenanceRequest $request)
    {
        return Maintenance::upsertInstance($request);
    }

    public function destroy(Maintenance $maintenance)
    {
        return $maintenance->deleteInstance();
    }

    public function filter(Request $request)
    {
        return view('admin.pages.maintenance.index',[
            'maintenances' => Maintenance::filter($request->all())->paginate(10)
        ]);
    }
}
