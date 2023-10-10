<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Tenant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'price',
        'apartment_id',
        'building_id',
        'user_id',
        'tenant_id',
        'expire_date',
        'end_date',
        'start_date',
        'paid',
        'end_payment',
        'is_blocked'
    ];

    protected static function booted()
    { 
        if(Auth::hasUser())
        {
            if(!Auth::user()->isSuperAdmin())
            {
                static::addGlobalScope(new TenantScope());
            } 
        } 
    }

    static function upsertInstance($request)
    {  
        $tenant = Tenant::updateOrCreate(
            [
                'id' => $request->id ?? null
            ],
                $request->all()
            );

        return $tenant;
    }

    public function scopeFilter($query,$request)
    {
        if ( isset($request['name']) ) {
            $query->where('name','like','%'.$request['name'].'%')
                ->orWhere('address','like','%'.$request['name'].'%');
        }

        return $query;
    }

    static function tenantSelect($request)
    {
        $results = count($request->term) == 2 ? Tenant::where('name','like','%'.$request->term["term"].'%')->take(10)->get()->toArray() : Tenant::filter($request->all())->take(10)->get()->toArray();
        
        return response()->json($results);
    }
    
    static function data($request)
    {
        // get all tenats with the same tenant_id and apartment_id
        $results = Tenant::where('tenant_id', $request->tenant_id)->where('apartment_id', $request->apartment_id)->whereNull('expire_date')->get();
        // $results = Tenant::whereHas('payments', function($q) use($request){
        //     $q->where('apartment_id', $request->apartment_id)->where('tenant_id',$request->tenant_id);
        // })->first();


        $tenant = Tenant::where('tenant_id', $request->tenant_id)->where('apartment_id', $request->apartment_id)->latest()->first();
        $new_date = date('M-Y');
        $date_test = date('Y-m-d');
        
        if(!$results){
            $results = [
                strtotime($tenant->end_date) >= strtotime($date_test) ?  [ 'date' => $new_date, 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 1 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 1 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 2 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 2 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 3 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 3 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 4 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 4 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 5 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 5 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 6 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 6 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 7 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 7 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 8 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 8 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 9 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 9 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test. ' + 10 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 10 months')), 'status' => 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 11 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 11 months')), 'status' => 'enabled' ] : ''
            ];
            // $results = [
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date) ?  [ 'date' => date('M-Y', strtotime($tenant->start_date)), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 1 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 1 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 2 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 2 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 3 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 3 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 4 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 4 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 5 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 5 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 6 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 6 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 7 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 7 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 8 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 8 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 9 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 9 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 10 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 10 months')), 'status' => 'enabled' ] : '',
            //     strtotime($tenant->end_date) >= strtotime($tenant->start_date . ' + 11 months') ? [ 'date' => date('M-Y', strtotime($tenant->start_date . ' + 11 months')), 'status' => 'enabled' ] : ''
            // ];
        }
        else{

            $payed = [];
            foreach ($results as  $result ){
                foreach($result->payments as $payment){
                    $last_payed = explode(",", $payment->pay_monthes);
                    $payed = array_merge($payed, $last_payed);
                }
            }

            $results = [
                strtotime($tenant->end_date) >= strtotime($date_test) ?  [ 'date' => $new_date, 'status' => in_array($new_date, $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 1 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 1 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 1 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 2 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 2 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 2 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 3 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 3 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 3 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 4 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 4 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 4 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 5 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 5 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 5 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 6 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 6 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 6 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 7 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 7 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 7 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 8 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 8 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 8 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 9 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 9 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 9 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 10 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 10 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 10 months')), $payed) ? 'disabled' : 'enabled' ] : '',
                strtotime($tenant->end_date) >= strtotime($date_test . ' + 11 months') ? [ 'date' => date('M-Y', strtotime($new_date . ' + 11 months')), 'status' => in_array(date('M-Y', strtotime($new_date . ' + 11 months')), $payed) ? 'disabled' : 'enabled' ] : ''
            ];
        }
        
        // Get only the enabled values
        $results = array_filter($results, function ($result) {
            return $result ?  $result['status'] === 'enabled': '';
        });
        $results = array_values($results);

        return response()->json($results);
    }

    static function statusUpdate($request)
    {
       $tenant = Tenant::where('id', $request->id)->update([ 'is_blocked' => $request->approved ]);
       
       return $tenant;       
    }
    
    public function deleteInstance()
    {
        return $this->delete();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
    
    public function financial()
    {
        return $this->hasMany(Financial_transaction::class , 'tenancy_id');
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class, 'tenancy_id');
    }
    
    public function picture()
    {
        return $this->morphOne(Gallary::class,'imageable')->where('use_for','picture');
    }
}
