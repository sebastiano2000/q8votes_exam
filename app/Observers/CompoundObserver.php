<?php

namespace App\Observers;

use App\Models\Compound;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class CompoundObserver
{
    public function created(Compound $compound)
    {
        $user_id =  Auth::user()->id ?? null;

        Log::create([
            'user_id' => $user_id,
            'action' => 'created',
            'action_id' => $compound->id,
            'message' => ($user_id) ? "لقد قام " . Auth::user()->name . " بإنشاء " . $compound->name : ' لقد قام '  .$compound->name . ' ' . 'بالتسجيل ' ,
            'action_model' => $compound->getTable(),
        ]);
    }

    public function updated(Compound $compound)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'updated',
            'action_id'    => $compound->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . 'بتعديل بيانات العقار '.  $compound->name ,
            'action_model' => $compound->getTable(),
        ]);
    }

    public function deleted(Compound $compound)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'deleted',
            'action_id'    => $compound->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . ' بحذف العقار '.  $compound->name ,
            'action_model' => $compound->getTable(),
        ]);
    }
}
