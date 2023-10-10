<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApartmentRequest;
use App\Models\Apartment;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.apartment.index',[
                'apartments' => Apartment::filter($request->all())->paginate(10),
        
            ]);
        else 
            abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upsert(Apartment $apartment)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.apartment.upsert',[
                'apartment' => $apartment,
                'buildings' => Building::get(),
            ]);
        else 
            abort(404);
    }

    public function modify(ApartmentRequest $request)
    {
        return Apartment::upsertInstance($request);
    }

    public function apartments(Request $request)
    {
        return Apartment::apartmentSelect($request);
    }

    public function destroy(Apartment $apartment)
    {
        return $apartment->deleteInstance();
    }

    public function filter(Request $request)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.apartment.index',[
                'apartments' => Apartment::filter($request->all())->paginate(10)
            ]);
        else 
            abort(404);
    }
}