<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompoundRequest;
use App\Models\Compound;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompoundController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.compound.index',[
                'compounds' => Compound::filter($request->all())->paginate(10),
                'users' => User::where('role_id',OWNER)->orWhere('role_id',SUPERADMIN)->get(),
            ]);
        elseif(Auth::user()->role_id == 2)
            return view('admin.pages.compound.index',[
                'compounds' => Compound::where('user_id', Auth::user()->id)->paginate(10),
                'users' => User::where('role_id',OWNER)->get(),
            ]);
        else
            abort(404);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upsert(Compound $compound)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.compound.upsert',[
                'compound' => $compound,
                'users' => User::where('role_id',OWNER)->orWhere('role_id',SUPERADMIN)->get(),
            ]);
        else 
            abort(404);
    }

    public function compounds(Request $request)
    {
        return Compound::compoundSelect($request);
    }

    public function modify(CompoundRequest $request)
    {
        return Compound::upsertInstance($request);
    }

    public function destroy(Compound $compound)
    {
        return $compound->deleteInstance();
    }

    public function filter(Request $request)
    {
        return view('admin.pages.compound.index',[
            'compounds' => Compound::filter($request->all())->paginate(10)
        ]);
    }
}
