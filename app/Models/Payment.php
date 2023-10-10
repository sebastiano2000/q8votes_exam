<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'apartment_id',
        'user_id',
        'tenancy_id',
        'financial_transaction_id',
        'total_amount',
        'pay_time',
        'pay_monthes',
        'notes'
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
    
    public function tenancy()
    {
        return $this->belongsTo(Tenant::class, 'tenancy_id');
    }
    
    public function financialTransaction()
    {
        return $this->belongsTo(Financial_transaction::class, 'Financial_transaction');
    }
}
