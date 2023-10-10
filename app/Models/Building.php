<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Image;

class Building extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    static function upsertInstance($request)
    {
        $building = Building::updateOrCreate(
            [
                'id' => $request->id ?? null
            ],
                $request->all()
            );
        
        $building->compounds()->sync($request->compound_id);
        
        if($request->apartment_name){
            foreach($request->apartment_name as $key => $apartment){
                $apartment = Apartment::create(
                    [
                        'name' => $apartment,
                        'building_id' => $building->id,
                        'user_id' => $request->user_id[1],
                    ]);
                
                $tenant = Tenant::create(
                    [
                        'start_date' => $request->tenant_date[$key],
                        'end_date' => $request->tenant_enddate[$key],
                        'price' => $request->price[$key],
                        'tenant_id' => $request->tenant_id[$key] ?? null,
                        'user_id' => $request->user_id[1],
                        'building_id' => $building->id,
                        'apartment_id' => $apartment->id,
                    ]);
                    
                $name = 'picture_'.$tenant->id.'.png';
            
                if ($request->file('picture')){
                    if ($request->file('picture')[$key]) {
                        if (!file_exists(public_path('tenants/'.$tenant->id.'/').$name)) {
                            mkdir(public_path('tenants/'.$tenant->id.'/'));
                        }
            
                        $image = $request->file('picture')[$key];
            
                        Image::make($image)->fit(120, 120)->save(public_path('tenants/'.$tenant->id.'/').$name);
            
                        $tenant->picture()->updateOrCreate(
                            [
                                'imageable_id' => $tenant->id,
                                'use_for' => 'picture'
                            ],
                            [
                                'name' => $name,
                                'use_for' => 'picture'
                            ]);
                    }
                }
            }
        }

        return $building;
    }
    
    static function edit($request)
    {
        $building = Building::updateOrCreate(
            [
                'id' => $request->id ?? null
            ],
                $request->all()
            );

        $building->compounds()->sync($request->compound_id);

        if($request->apartment_name){
            foreach($request->apartment_name as $key => $apartment){
                $apartment = Apartment::where('id', $key)->update(
                    [
                        'name' => $apartment,
                        'building_id' => $building->id,
                        'user_id' => $request->user_id[0],
                    ]);
                    
                $tenant_date = Tenant::where('apartment_id', $key)->where('tenant_id', $request->tenant_old[$key])->latest()->first();
                $tenant = Tenant::create(
                    [
                        'price' => $request->price[$key] ?? null,
                        'start_date' => $request->start_date[$key] ?? null,
                        'end_date' => $request->end_date[$key] ?? null,
                        'tenant_id' => $request->tenant_id[$key] ?? null,
                        'user_id' => $request->user_id[0],
                        'building_id' => $building->id,
                        'apartment_id' => $key,
                    ]);
                    
                if($request->tenant_id[$key] ?? null){
                    if($request->tenant_id[$key] != $request->tenant_old[$key] && $request->tenant_old[$key] != null){
                        $tenant_date = Tenant::where('apartment_id', $key)->where('tenant_id', $request->tenant_old[$key])->update(
                            [
                                'expire_date' => Carbon::now(),
                            ]);
                    }else{
                        $tenant->paid =  $tenant_date->paid ;
                        $tenant->end_payment  = $tenant_date->end_payment ;
                        $tenant->save();
                    }
                }
                    
                $name = 'picture_'.$tenant->id.'.png';
    
                if ($request->file('picture')) {
                    if ($request->file('picture')[$key]) {
                        if (!file_exists(public_path('tenants/'.$tenant->id.'/').$name)) {
                            mkdir(public_path('tenants/'.$tenant->id.'/'));
                        }
            
                        $image = $request->file('picture')[$key];
            
                        Image::make($image)->fit(120, 120)->save(public_path('tenants/'.$tenant->id.'/').$name);
            
                        $tenant->picture()->updateOrCreate(
                            [
                                'imageable_id' => $tenant->id,
                                'use_for' => 'picture'
                            ],
                            [
                                'name' => $name,
                                'use_for' => 'picture'
                            ]);
                    }
                }
            }
        }
        
        return $building;
    }
    
    static function appartment($request)
    {
        $apartment = Apartment::create(
            [
                'name' => $request->name,
                'building_id' => $request->id,
                'user_id' => $request->user_id,
            ]);
                
        $tenant = Tenant::create(
            [
                'start_date' => $request->tenant_date,
                'end_date' => $request->tenant_enddate,
                'price' => $request->price,
                'tenant_id' => $request->tenant_id,
                'user_id' => $request->user_id,
                'building_id' => $request->id,
                'apartment_id' => $apartment->id,
            ]);
            
        $name = 'picture_'.$request->tenant_id.'.png';

        if ($request->file('picture')) {
            if (!file_exists(public_path('tenants/'.$request->tenant_id.'/').$name)) {
                mkdir(public_path('tenants/'.$request->tenant_id.'/'));
            }

            $image = $request->file('picture');

            Image::make($image)->fit(120, 120)->save(public_path('tenants/'.$request->tenant_id.'/').$name);

            $tenant->picture()->updateOrCreate(
                [
                    'imageable_id' => $request->tenant_id,
                    'use_for' => 'picture'
                ],
                [
                    'name' => $name,
                    'use_for' => 'picture'
                ]);
        }

        return $tenant;
    }

    public function scopeFilter($query,$request)
    {
        if ( isset($request['name']) ) {
            $query->where('name','like','%'.$request['name'].'%');
        }

        return $query;
    }
    
    static function appartmentDelete($request)
    {
        Tenant::where('apartment_id', $request->appartment)->delete();
        Maintenance::where('apartment_id', $request->appartment)->delete();
        
        return Apartment::where('id', $request->appartment)->delete();
    }
    
    static function statusUpdate($request)
    {
        Apartment::where('id', $request->apartment_id)->update(['status' => $request->status]);
            
        if($request->status == '0'){
            $tenant = Tenant::where('id', $request->id)->first();
            $tenant->expire_date = $request->expire_date;
            $tenant->save();

            Tenant::create([
                'building_id' => $tenant->building_id,
                'apartment_id' => $tenant->apartment_id,
                'user_id' => $tenant->user_id,
            ]);
            
            return $tenant;
        }
        else {
            return false;
        }
    }
    
    static function buildingSelect($request)
    {
        // $results = count($request->term) == 2 ? Building::whereHas('compounds', function($q) use($request){
        //     $q->where('compound_id', $request->compound_id);
        // })->where('name','like','%'.$request->term["term"].'%')->take(10)->get()->toArray() : Building::filter($request->all())->whereHas('compounds', function($q) use($request){
        //     $q->where('compound_id', $request->compound_id);
        // })->take(10)->get()->toArray();

        // return all bulding with compound and owner
        $results = Building::all()->toArray();

        return response()->json($results);
    }
    
    static function name($request)
    {
        Building::where('id', $request->id)->update(['name' => $request->name]);
    }
    
    public function getBuildingRevenueAttribute($request)
    {
        $price = 0;
        
        foreach($this->apartments as $apartment){
            if(count($apartment->tenants) > 0){
                if($apartment->tenants()->latest()->first()->price){
                    $price += $apartment->tenants()->latest()->first()->price;
                }
            }
        }
        
        return $price;
    }
    
    public function getBuildingTenantAttribute($request)
    {
        $price = 0;
        
        foreach($this->apartments as $apartment){
            if(count($apartment->tenants) > 0){
                if($apartment->tenants()->latest()->first()->start_date && $apartment->tenants()->latest()->first()->price && $apartment->status){
                    $price += $apartment->tenants()->latest()->first()->price;
                }
            }
        }
        
        return $price;
    }
    
    public function getBuildingPaidAttribute($request)
    {
        $price = 0;
        
        foreach($this->apartments as $apartment){
            if(count($apartment->tenants) > 0){
                if($apartment->tenants()->latest()->first()->paid && strtotime($apartment->tenants()->latest()->first()->end_payment) >= strtotime(date("Y-m"))){
                    $price += $apartment->tenants()->latest()->first()->price;
                }
            }
        }
        
        return $price;
    }
    
    public function getApartmentNotpaidAttribute($request)
    {
        $count = 0;
        
        foreach($this->apartments as $apartment){
            if(count($apartment->tenants) > 0){
                if(!$apartment->tenants()->latest()->first()->paid && $apartment->status){
                    $count ++;
                }
            }
        }
        
        return $count;
    }

    // get the count of all occupied appartment
    public function getApartmentOccupiedAttribute($request)
    {
        $count = 0;
        
        foreach($this->apartments as $apartment){
            if(count($apartment->tenants) > 0){
                if($apartment->tenants()->latest()->first()->start_date && $apartment->tenants()->latest()->first()->price && $apartment->status){
                    $count ++;
                }
            }
        }
        
        return $count;
    }

    public function deleteInstance()
    {
        Tenant::where('building_id', $this->id)->delete();
        Maintenance::where('building_id', $this->id)->delete();
        Apartment::where('building_id', $this->id)->delete();
        
        return $this->delete();
    }
    
    static function retrieveUser($request)
    {
        return User::find($request->id)->only('id', 'name');
    }
    
    static function retrieveCompound($request)
    {
        return Compound::find($request->id)->only('id', 'name');
    }
    
    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }
    
    public function compounds()
    {
        return $this->belongsToMany(Compound::class);
    }
    
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
