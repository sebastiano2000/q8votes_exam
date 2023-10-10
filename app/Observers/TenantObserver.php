<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class TenantObserver
{
    public function created(Tenant $tenant)
    {
        $user_id =  Auth::user()->id ?? null;

        Log::create([
            'user_id' => $user_id,
            'action' => 'created',
            'action_id' => $tenant->id,
            'message' => ($user_id) ? "لقد قام " . Auth::user()->name . " بإنشاء " . $tenant->name : ' لقد قام '  .$tenant->name . ' ' . 'بالتسجيل ',
            'action_model' => $tenant->getTable(),
        ]);
    }

    public function updated(Tenant $tenant)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'updated',
            'action_id'    => $tenant->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . 'بتعديل بيانات العقار '.  $tenant->name,
            'action_model' => $tenant->getTable(),
        ]);
    }

    public function deleted(Tenant $tenant)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'deleted',
            'action_id'    => $tenant->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . ' بحذف العقار '. $tenant->name,
            'action_model' => $tenant->getTable(),
        ]);
    }
}
