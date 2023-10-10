<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Hesabe\Controllers\PaymentController;
use Image;
use Session;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'national_id',
        'suspend',
        'trigger_block'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        if (Auth::hasUser()) {
            if (!Auth::user()->isSuperAdmin()) {
                static::addGlobalScope(new TenantScope());
            }
        }
    }

    static function upsertInstance($request)
    {
        $request->merge([
            'password' => Hash::make($request->password),
        ]);

        if ($request->password) {
            $user = User::updateOrCreate(
                [
                    'id' => $request->id ?? null
                ],
                $request->all()
            );
        } else {
            $user = User::updateOrCreate(
                [
                    'id' => $request->id ?? null
                ],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'role_id' => $request->role_id,
                    'national_id' => $request->national_id,
                ]
            );
        }

        $name = 'picture_' . $user->id . '.png';
        $name2 = 'contract_' . $user->id . '.png';

        if ($request->file('picture')) {
            if (!file_exists(public_path('users/' . $user->id . '/'))) {
                mkdir(public_path('users/' . $user->id . '/'));
            }

            $image = $request->file('picture');

            Image::make($image)->save(public_path('users/' . $user->id . '/') . $name);

            $user->picture()->updateOrCreate(
                [
                    'imageable_id' => $user->id,
                    'use_for' => 'picture'
                ],
                [
                    'name' => $name,
                    'use_for' => 'picture'
                ]
            );
        }

        if ($request->file('contract')) {
            if (!file_exists(public_path('users/' . $user->id . '/'))) {
                mkdir(public_path('users/' . $user->id . '/'));
            }

            $image = $request->file('contract');

            Image::make($image)->save(public_path('users/' . $user->id . '/') . $name2);

            $user->contract()->updateOrCreate(
                [
                    'imageable_id' => $user->id,
                    'use_for' => 'contract'
                ],
                [
                    'name' => $name2,
                    'use_for' => 'contract'
                ]
            );
        }

        return $user;
    }

    static function modifyPassword($request)
    {
        $user = User::where('id', $request->id)->update(['password' => Hash::make($request->password)]);
    }

    static function userSelect($request)
    {
        $results = count($request->term) == 2 ? User::where(function ($query) {
            $query->where('role_id', OWNER)
                ->orWhere('role_id', SUPERADMIN);
        })->where('name', 'like', '%' . $request->term["term"] . '%')->take(10)->get()->toArray() : User::filter($request->all())->where('role_id', OWNER)->orWhere('role_id', SUPERADMIN)->take(10)->get()->toArray();

        return response()->json($results);
    }

    static function payment($request)
    {
        $date = \Carbon\Carbon::now();

        $request->merge([
            'merchantCode' => '842217',
            // 'merchantCode' => '86721223',
            'amount' => $request->cost * count($request->quantity),
            'currency' => 'KWD',
            'paymentType' => '1',
            'orderReferenceNumber' => $date->timestamp,
            'variable1' => null,
            'variable2' => null,
            'variable3' => null,
            'variable4' => null,
            'variable5' => null,
            'paymentType' => '0',
            'responseUrl' => 'https://kircokw.com/admin/payment/success',
            'failureUrl' => 'https://kircokw.com/admin/payment/failure',
            'version' => '2.0',
            'isOrderReference' => '1'
        ]);

        $payment_data = [
            'apartment_id' => $request->apartment_id,
            'user_id' => $request->user_id,
            'tenancy_id' => $request->tenant_id,
            'tenant_id' => $request->id,
            'cost' => $request->cost * count($request->quantity),
            'quantity' => count($request->quantity),
            'monthes' => $request->quantity,
            'time' => $date
        ];

        $paymentController = new PaymentController();
        $url = $paymentController->formSubmit($request);

        $request->session()->regenerate();
        session()->put('payment.data', $payment_data);
        Cache::put('payment.data', $payment_data, now()->addMinutes(30));
        cookie('payment.data', json_encode($payment_data));

        // get Auth username and Hashed password
        $credential = [
            'username' => Auth::user()->email,
            'password' => Auth::user()->password,
        ];

        Cache::put('credential', $credential, now()->addMinutes(30));
        return $url;
    }

    static function paymentCash($request)
    {
        // check if the there is a month is selected
        if (count($request->quantity) < 1) {
            return false;
        }

        $date = \Carbon\Carbon::now();
        $financial_transaction = Financial_transaction::create([
            'resultCode' => 'CAPTURED',
            'total_amount' => number_format($request->cost  * count($request->quantity), 3, '.', ''),
            'paymentToken' => null,
            'paymentId' => null,
            'paidOn' => $date,
            'orderReferenceNumber' => $date->timestamp,
            'variable1' => null,
            'variable2' => null,
            'variable3' => null,
            'variable4' => null,
            'variable5' => null,
            'method' => 'CASH',
            'administrativeCharge' => null,
            'paid' => 1,
            'tenant_id' => $request->id,
            'tenancy_id' => $request->tenant_id,
            'quantity' => count($request->quantity),
        ]);

        Tenant::where('id', $request->tenant_id)->update([
            'paid' => 1,
            'end_payment' => $request->quantity[count($request->quantity) - 1],
        ]);

        Payment::create([
            'apartment_id' => $request->apartment_id,
            'user_id' => $request->user_id,
            'tenant_id' => $request->id,
            'tenancy_id' => $request->tenant_id,
            'total_amount' => number_format($request->cost  * count($request->quantity), 3, '.', ''),
            'financial_transaction_id' => $financial_transaction->id,
            'pay_time' => $date,
            'pay_monthes' => implode(',', $request->quantity),
            'notes' => $request->notes ?? null,
        ]);

        return true;
    }

    static function saveData($request)
    {
        if (!Auth::check()) {
            if (Cache::has('credential')) {
                $credential = Cache::get('credential');
            } else {
                return false;
            }
            $user = User::where('email', $credential['username'])->first();
            Auth::login($user);
        }

        $paymentController = new PaymentController();
        $response = $paymentController->getPaymentResponse($request->data);

        $payment_data_cookie = json_decode(cookie('payment.data'));
        $payment_data_cache = Cache::get('payment.data');

        $tenant_id = $payment_data_cache['tenant_id'] ?? $payment_data_cookie['tenant_id'] ?? getDataFromPayment('tenant_id');
        $tenancy_id = $payment_data_cache['tenancy_id'] ?? $payment_data_cookie['tenancy_id'] ?? getDataFromPayment('tenancy_id');
        $quantity = $payment_data_cache['quantity'] ?? $payment_data_cookie['quantity'] ?? getDataFromPayment('quantity');
        $monthes = $payment_data_cache['monthes'] ?? $payment_data_cookie['monthes'] ?? getDataFromPayment('monthes');
        $time = $payment_data_cache['time'] ?? $payment_data_cookie['time'] ?? getDataFromPayment('time');
        $apartment_id = $payment_data_cache['apartment_id'] ?? $payment_data_cookie['apartment_id'] ?? getDataFromPayment('apartment_id');
        $user_id = $payment_data_cache['user_id'] ?? $payment_data_cookie['user_id'] ?? getDataFromPayment('user_id');

        $financial_transaction = Financial_transaction::create([
            'resultCode' => $response->status ? 'CAPTURED' : 'NOT CAPTURED',
            'total_amount' => $response->response['amount'],
            'paymentToken' => $response->response['paymentToken'],
            'paymentId' => $response->response['paymentId'],
            'paidOn' => $response->response['paidOn'],
            'orderReferenceNumber' => $response->response['orderReferenceNumber'],
            'variable1' => $response->response['variable1'],
            'variable2' => $response->response['variable2'],
            'variable3' => $response->response['variable3'],
            'variable4' => $response->response['variable4'],
            'variable5' => $response->response['variable5'],
            'method' => $response->response['method'],
            'administrativeCharge' => $response->response['administrativeCharge'],
            'paid' => $response->status ? 1 : 0,
            'tenant_id' => $tenant_id,
            'tenancy_id' => $tenancy_id,
            'quantity' => $quantity,
        ]);

        if ($response->status) {
            Tenant::where('id', $tenancy_id)->update([
                'paid' => 1,
                'end_payment' => $monthes[count($monthes) - 1],
            ]);

            Payment::create([
                'apartment_id' => $apartment_id,
                'user_id' => $user_id,
                'tenant_id' => $tenant_id,
                'tenancy_id' => $tenancy_id,
                'total_amount' => $response->response['amount'],
                'financial_transaction_id' => $financial_transaction->id,
                'pay_time' => $time,
                'pay_monthes' => implode(",", $monthes),
            ]);
        }

        Cache::flush();
        cookie()->forget('payment.data');
        $request->session()->regenerate();

        return true;
    }

    static function usersTenant($request)
    {
        $results = count($request->term) == 2 ? User::where('role_id', TENANT)->where('name', 'like', '%' . $request->term["term"] . '%')->take(10)->get()->toArray() : User::filter($request->all())->where('role_id', TENANT)->take(10)->get()->toArray();

        return response()->json($results);
    }

    public function scopeFilter($query, $request)
    {
        if (isset($request['name'])) {
            $query->where('name', 'like', '%' . $request['name'] . '%')
                ->orWhere('national_id', 'like', '%' . $request['name'] . '%')
                ->orWhere('email', 'like', '%' . $request['name'] . '%')
                ->orWhere('phone', 'like', '%' . $request['name'] . '%');
        }

        return $query;
    }

    //Roles
    public function isSuperAdmin()
    {
        return Auth::user()->role_id == SUPERADMIN;
    }

    public function isOwner()
    {
        return Auth::user()->role_id == OWNER;
    }

    public function isTenant()
    {
        return Auth::user()->role_id == TENANT;
    }

    public function deleteInstance()
    {
        foreach ($this->compounds as $compound) {
            foreach ($compound->buildings as $building) {
                if (count($building->compounds) > 1) {
                    $building->pivot->where('building_compound.compound_id', $compound->id)->delete();
                } else {
                    Tenant::where('building_id', $building->id)->delete();
                    Maintenance::where('building_id', $building->id)->delete();
                    Apartment::where('building_id', $building->id)->delete();
                    Building::where('id', $building->id)->delete();
                }
            }
        }

        $this->compounds()->delete();
        return $this->delete();
    }

    public function picture()
    {
        return $this->morphOne(Gallary::class, 'imageable')->where('use_for', 'picture');
    }

    public function contract()
    {
        return $this->morphOne(Gallary::class, 'imageable')->where('use_for', 'contract');
    }

    public function compounds()
    {
        return  $this->hasMany(Compound::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'tenant_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'tenant_id');
    }
}
