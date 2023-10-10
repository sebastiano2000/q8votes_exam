<?php 

function getDataFromForget($col)
{

    if ( !empty(session()->get('subscripe.personal')) ) {
        $session_collection = collect(session()->get('subscripe.personal'));
        if ( count($session_collection) ) {
            return collect(session()->get('subscripe.personal'))[$col] ?? null;
        }
    }

    return null;
}

function getPhoneNumberFromForget()
{
    return '+'.collect(session()->get('subscripe.personal'))['country_code'].collect(session()->get('subscripe.personal'))['phone'];
}

