<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.tenant.index',[
                'tenants' => Tenant::filter($request->all())->paginate(10),
            ]);
        else 
            abort(404);
        
    }
    
    public function status(Request $request)
    {
        return Tenant::statusUpdate($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function upsert(Tenant $tenant)
    {
        if(Auth::user()->isSuperAdmin())
            return view('admin.pages.tenant.upsert',[
                'tenant' => $tenant,
                'users' => User::where('role_id',TENANT)->get(),
            ]);
        else 
            abort(404);
    }

    public function tenants(Request $request)
    {
        return Tenant::tenantSelect($request);
    }

    public function modify(TenantRequest $request)
    {
        return Tenant::upsertInstance($request);
    }

    public function destroy(Tenant $tenant)
    {
        return $tenant->deleteInstance();
    }

    public function filter(Request $request)
    {
        return view('admin.pages.tenant.index',[
            'tenants' => Tenant::filter($request->all())->paginate(10)
        ]);
    }
}
