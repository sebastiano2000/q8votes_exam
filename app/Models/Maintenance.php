<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Maintenance extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'building_id',
        'apartment_id',
        'cost',
        'user_id',
        'note',
        'invoice_date'
    ];

    protected static function booted()
    { 
        if(Auth::hasUser())
        {
            if(! Auth::user()->isSuperAdmin())
            {
                static::addGlobalScope(new TenantScope());
            } 
        } 
    }

    static function upsertInstance($request)
    {
        if($request->building_id){
            $user_id = Building::find($request->building_id)->compounds->first()->user->id;
            $compound_id = Building::find($request->building_id)->compounds->first()->id;

            $request->merge([
                'user_id' => $user_id,
                'compound_id' => $compound_id
            ]);
        }
        elseif($request->apartment_id) {
            $user_id = Apartment::find($request->apartment_id)->building->compounds->first()->user->id;
            $compound_id = Apartment::find($request->apartment_id)->building->compounds->first()->id;

            $request->merge([
                'user_id' => $user_id,
                'compound_id' => $compound_id
            ]);
        }

        $maintenance = Maintenance::updateOrCreate(
            [
                'id' => $request->id ?? null
            ],
                $request->all()
            );

        return $maintenance;
    }

    public function scopeFilter($query,$request)
    {
        if ( isset($request['name']) ) {
            $query->where('name','like','%'.$request['name'].'%')
                ->orWhere('cost','like','%'.$request['name'].'%');
        }

        return $query;
    }

    static function buildingSelect($request)
    {
        $results = count($request->term) == 2 ? Apartment::where('name','like','%'.$request->term["term"].'%')->where('building_id', $request->building_id)->take(10)->get()->toArray() : Apartment::filter($request->all())->where('building_id', $request->building_id)->take(10)->get()->toArray();

        return response()->json($results);
    }

    public function deleteInstance()
    {
        return $this->delete();
    }
    
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
