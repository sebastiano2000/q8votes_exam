<?php

namespace App\Observers;

use App\Models\Apartment;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class ApartmentObserver
{
    public function created(Apartment $apartment)
    {
        $user_id =  Auth::user()->id ?? null;

        Log::create([
            'user_id' => $user_id,
            'action' => 'created',
            'action_id' => $apartment->id,
            'message' => ($user_id) ? "لقد قام " . Auth::user()->name . " بإنشاء " . $apartment->name : ' لقد قام '  .$apartment->name . ' ' . 'بالتسجيل ' ,
            'action_model' => $apartment->getTable(),
        ]);
    }

    public function updated(Apartment $apartment)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'updated',
            'action_id'    => $apartment->id,
            'message'   =>   " لقد قام  " .Auth::user()->name .'' . 'بتعديل بيانات المستحدم '.  $apartment->name ,
            'action_model' => $apartment->getTable(),
        ]);
    }

    public function deleted(Apartment $apartment)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'deleted',
            'action_id'    => $apartment->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . ' بحذف المستحدم '.  $apartment->name ,
            'action_model' => $apartment->getTable(),
        ]);
    }
}
