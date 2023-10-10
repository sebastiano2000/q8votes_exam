<?php

namespace App\Observers;

use App\Models\Building;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class BuildingObserver
{
    public function created(Building $building)
    {
        $user_id =  Auth::user()->id ?? null;

        Log::create([
            'user_id' => $user_id,
            'action' => 'created',
            'action_id' => $building->id,
            'message' => ($user_id) ? "لقد قام " . Auth::user()->name . " بإنشاء " . $building->name : ' لقد قام '  .$building->name . ' ' . 'بالتسجيل ' ,
            'action_model' => $building->getTable(),
        ]);
    }

    public function updated(Building $building)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'updated',
            'action_id'    => $building->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . 'بتعديل بيانات العقار '.  $building->name ,
            'action_model' => $building->getTable(),
        ]);
    }

    public function deleted(Building $building)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'deleted',
            'action_id'    => $building->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . ' بحذف العقار '.  $building->name ,
            'action_model' => $building->getTable(),
        ]);
    }
}
