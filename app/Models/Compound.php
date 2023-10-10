<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Image;

class Compound extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'user_id'
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
        $request->merge([
            'address' => '',    
        ]);

        $compound = Compound::updateOrCreate(
            [
                'id' => $request->id ?? null
            ],
                $request->all()
            );

        return $compound;
    }

    public function scopeFilter($query,$request)
    {
        if ( isset($request['name']) ) {
            $query->where('name','like','%'.$request['name'].'%')
                ->orWhere('address','like','%'.$request['name'].'%');
        }

        return $query;
    }
    
    static function compoundSelect($request)
    {
        $results = count($request->term) == 2 ? Compound::where('user_id', $request->user_id)->where('name','like','%'.$request->term["term"].'%')->take(10)->get()->toArray() : Compound::filter($request->all())->where('user_id', $request->user_id)->take(10)->get()->toArray();
        
        return response()->json($results);
    }

    public function deleteInstance()
    {
        foreach($this->buildings as $building){
            if(count($building->compounds) > 1){
                $building->pivot->where('building_compound.compound_id', $this->id)->delete();
            }
            else{
                Tenant::where('building_id', $building->id)->delete();
                Maintenance::where('building_id', $building->id)->delete();
                Apartment::where('building_id', $building->id)->delete();
                Building::where('id', $building->id)->delete();
            }
        }
        
        return $this->delete();
    }

    public function user()
    {
      return  $this->belongsTo(User::class, 'user_id');
    }
    
    public function buildings()
    {
        return $this->belongsToMany(Building::class);
    }
}
