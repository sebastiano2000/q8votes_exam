<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\AppartmentPDF;
use App\Models\BuildingPDF;
use Illuminate\Support\Facades\Auth;

class AppartmentPDFController extends Controller
{
    public function index(Building $building) 
    {
        if(Auth::user()->isSuperAdmin())
            return (new AppartmentPDF)->download($building);
        elseif(Auth::user()->role_id == 2){
            if($building->compounds->first()->user_id == Auth::user()->id)
                return (new AppartmentPDF)->download($building);
            else
                abort(404);
        }
        else
            abort(404);
    }
    
    public function indexBuildings() 
    {
        return (new BuildingPDF)->download();
    }
}