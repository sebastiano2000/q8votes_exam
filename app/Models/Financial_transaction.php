<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Financial_transaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['total_amount', 'tenant_id', 'resultCode', 'paymentToken', 'paymentId', 'paidOn', 'orderReferenceNumber', 'variable1', 'variable2', 'variable3', 'variable4', 'variable5', 'method', 'administrativeCharge', 'paid', 'tenant_id', 'tenancy_id', 'quantity'];

    //Tenancy
    // protected static function booted()
    // { 
    //     if(Auth::hasUser())
    //     {
    //         if(! Auth::user()->isSuperAdmin())
    //         {
    //             static::addGlobalScope(new TenantScope());
    //         } 
    //     } 
    // }

    //Functions 
    static function upsertInstance($request)
    {
        // $financial = Financial_transaction::updateOrCreate(
        //     [
        //         'id' => $examination->schedule->financial()->first()->id ?? null,
        //     ],
        //     [
        //         'total_amount' => $request->total_amount,
        //         'schedule_id' => $request->schedule_id,
        //         'clinic_id' => $request->clinic_id,
        //         'percentage' => $examination->schedule->user->percentage,
        //     ]
        // );

        // return $financial;
    }

    public function scopeFilter($query, $request)
    {            
        if ( isset($request['name']) ) {
            $query->where(function($query) use ($request){
                $query->whereHas('tenant', function($q) use($request){
                    $q->where('name','like','%'.$request['name'].'%');
                });
            });
        }
    
        return $query;
    }

    public function roleback($financial_transaction)
    {
        // $financial = Financial_transaction::where('tenancy_id', $financial_transaction->tenancy_id)->latest()->first();
        // get the payed monthes from paymemts
        $payments = Payment::where('financial_transaction_id', $financial_transaction->id)->first();
        $payed_monthes = explode(',', $payments->pay_monthes);
        
        
        $tenant = $financial_transaction->tenancy()->first();
        if(strtotime($tenant->end_payment) == strtotime(end($payed_monthes) . ' + 1 months') ){
            $tenant->update([
                'end_payment' => date('M-Y', strtotime($tenant->end_payment . ' - 1 months')),
            ]);
            if(strtotime($tenant->end_payment . ' - 1 months') < strtotime(date('M-Y'))){
                $tenant->update([
                    'paid' => 0
                ]);
            }
        }
        $tenant->save();

        // get the tenant from the tenats table and update the end payment for this tenant
        
        // $tenant = $financial->tenancy()->first();
        // $new_date = date('M-Y', strtotime($tenant->end_payment . ' - 1 months'));

        // if(strtotime($new_date) < strtotime(date('M-Y'))){
        //     $tenant->update([
        //         'end_payment' => $new_date,
        //         'paid' => 0
        //     ]);
        // }else{
        //     $tenant->update([
        //         'end_payment' => $new_date,
        //     ]);
        // }
        // $tenant->save();
        
        if(count($payed_monthes) > 1){
            array_pop($payed_monthes);
            $payments->update([
                'pay_monthes' => implode(',', $payed_monthes),
            ]);
        }else{
            $payments->delete();
            $financial_transaction->delete();
        }
    }

    //Relations
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
    
    public function tenancy()
    {
        return $this->belongsTo(Tenant::class, 'tenancy_id');
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
