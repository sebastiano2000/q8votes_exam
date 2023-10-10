<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tenants = Tenant::where('paid', 1)->get();
        
        foreach($tenants as $tenant){
            $day = date('d', strtotime($tenant->start_date));
            $today = date('Y-m-d');
            $end_payment = date('Y-m-d', strtotime($tenant->end_payment . '+ 1 month + ' . $day . ' days'));
            
            if($today >= $end_payment){
                $tenant->paid = 0;
                $tenant->save();
            }
        }
        
        $day = date('d');
        $block_date = date('M-Y');
        $block_date_year = date('Y-m-d');
        $trigger = User::where('id', 1)->first();
        
        if($day == '30' && $trigger->trigger_block){
            $tenants_blocked = Tenant::where('paid', 0)->where('end_payment', '<', $block_date)->whereNotNull('start_date')->whereNull('expire_date')->update([ 'is_blocked' => 1 ]);
            $tenants_blocked = Tenant::where('paid', 0)->whereNull('end_payment')->where('start_date', '<', $block_date_year)->whereNull('expire_date')->update([ 'is_blocked' => 1 ]);
            User::where('id', 1)->update([ 'trigger_block' => 1 ]);
        }
        
        if($day == '1'){
            User::where('id', 1)->update([ 'trigger_block' => 0 ]);
        }
        
        if(Auth::user()->role_id == 3){
            $tenants = DB::table('apartments')
                ->leftJoin('tenants',  function($query){
                    $query->on('tenants.apartment_id', '=', 'apartments.id')
                        ->whereRaw('tenants.id IN (select MAX(tenants.id) from tenants join apartments on apartments.id = tenants.apartment_id  group by apartments.id)');
                        
                })
                ->leftJoin('buildings', 'buildings.id' , '=' , 'apartments.building_id')
                ->select([
                    'tenants.*',
                    'apartments.id as apartment_id',
                    'apartments.name as apartment_name',
                    'buildings.name as building_name'
                ])
                ->whereNull('tenants.deleted_at')
                ->whereNull('apartments.deleted_at')
                ->whereNull('tenants.expire_date')
                ->where('tenants.tenant_id', Auth::user()->id)
                ->where('apartments.status', 1)->get();

            return view('admin.pages.dashboard', ['tenants' => $tenants]);
        }
        else {
            return view('admin.pages.dashboard', ['tenants' => []]);
        }
    }
    
    public function getData(Request $request)
    {
        return Tenant::data($request);
    }
}
