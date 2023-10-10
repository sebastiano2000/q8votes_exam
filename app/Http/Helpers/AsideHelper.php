<?php 


function is_active($name) {
    $route_name = request()->route()->getName();    
    $route_name_array = explode('.',$route_name);

    if ( in_array($name,$route_name_array) )
        return 'active';

}

function getDataFromPayment($col)
{
    if (!empty(session()->get('payment.data')) ) {
        $session_collection = collect(session()->get('payment.data'));
        
        if (count($session_collection) ) {
            return collect(session()->get('payment.data'))[$col] ?? null;
        }
    }

    return null;
}