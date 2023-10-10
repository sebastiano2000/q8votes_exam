<?php

namespace App\Exports;

use App\Models\Apartment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

class ExportApartment implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    
    protected $building_id;
    
    public function __construct($building_id)
    {
        $this->building_id = $building_id;
    }
    
    public function collection()
    {
        $apartments = DB::table('apartments')
            ->leftJoin('tenants', 'tenants.apartment_id' , '=' ,'apartments.id')
            ->leftJoin('users', 'users.id' , '=' , 'tenants.user_id')
            ->select([
                'apartments.name as apartment_name',
                'users.name as users_name',
                'tenants.start_date as start_date',
                'tenants.end_date as end_date',
                'tenants.price as price',
            ])
            ->where('apartments.building_id', $this->building_id->building_id)->get();
            
        return $apartments;
    }
}
