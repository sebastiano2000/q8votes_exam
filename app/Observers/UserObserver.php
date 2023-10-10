<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    public function created(User $user)
    {
        $user_id =  Auth::user()->id ?? null;

        Log::create([
            'user_id' => $user_id,
            'action' => 'created',
            'action_id' => $user->id,
            'message' => ($user_id) ? "لقد قام " . Auth::user()->name . " بإنشاء " . $user->name : ' لقد قام '  .$user->name . ' ' . 'بالتسجيل ' ,
            'action_model' => $user->getTable(),
        ]);
    }

    public function updated(User $user)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'updated',
            'action_id'    => $user->id,
            'message'   =>   " لقد قام  " .Auth::user()->name .'' . 'بتعديل بيانات المستحدم '.  $user->name ,
            'action_model' => $user->getTable(),
        ]);
    }

    public function deleted(User $user)
    {
        Log::create([
            'user_id'      => Auth::user()->id,
            'action'       => 'deleted',
            'action_id'    => $user->id,
            'message'      =>   " لقد قام  " .Auth::user()->name .'' . ' بحذف المستحدم '.  $user->name ,
            'action_model' => $user->getTable(),
        ]);
    }
}
