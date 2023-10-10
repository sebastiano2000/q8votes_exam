<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;

class MaintenanceObserver
{
    public function created(Maintenance $maintenance)
    {
        $user_id =  Auth::user()->id ?? null;

        Log::create([
            'user_id' => $user_id,
            'action' => 'created',
            'action_id' => $maintenance->id,
            'message' => ($user_id) ? "لقد قام " . Auth::user()->name . " بإنشاء " . $maintenance->name : ' لقد قام '  .$maintenance->name . ' ' . 'بالتسجيل ' ,
            'action_model' => $maintenance->getTable(),
        ]);
    }

    public function updated(Maintenance $maintenance)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'updated',
            'action_id'    => $maintenance->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . 'بتعديل بيانات العقار '.  $maintenance->name ,
            'action_model' => $maintenance->getTable(),
        ]);
    }

    public function deleted(Maintenance $maintenance)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'deleted',
            'action_id'    => $maintenance->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . ' بحذف العقار '. $maintenance->name ,
            'action_model' => $maintenance->getTable(),
        ]);
    }
}
