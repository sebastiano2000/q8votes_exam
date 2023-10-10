<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Image;

class Apartment extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'building_id',
        'user_id'
    ];

    static function upsertInstance($request)
    {
        $user_id = Building::find($request->building_id)->compounds->first()->user->id;

        $request->merge([
            'user_id' => $user_id
        ]);
     
        $apartment = Apartment::updateOrCreate(
            [
                'id' => $request->id ?? null
            ],
                $request->all()
            );

        $name = 'picture_'.$apartment->id.'.png';
        
        if ($request->file('picture')) {
            $image = $request->file('picture');
            Image::make($image)->fit(120, 120)->save(public_path('apartments/'.$apartment->id.'/').$name);

            $apartment->picture()->updateOrCreate(
            [
                'imageable_id' => $apartment->id,
                'use_for' => 'picture'
            ],
            [
                'name' => $name,
                'use_for' => 'picture'
            ]);
        }

        return $apartment;
    }

    public function scopeFilter($query,$request)
    {
        if ( isset($request['name']) ) {
            $query->where('name','like','%'.$request['name'].'%');
        }

        return $query;
    }

    static function apartmentSelect($request)
    {
        $results = count($request->term) == 2 ? Apartment::where('name','like','%'.$request->term["term"].'%')->take(10)->get()->toArray() : Apartment::filter($request->all())->take(10)->get()->toArray();

        return response()->json($results);
    }

    public function deleteInstance()
    {
        Tenant::where('apartment_id', $this->id)->delete();
        Maintenance::where('apartment_id', $this->id)->delete();

        return $this->delete();
    }
    
    public function picture()
    {
        return $this->morphOne(Gallary::class,'imageable')->where('use_for','picture');
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
