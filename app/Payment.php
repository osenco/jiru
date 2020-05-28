<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'paid', 'balance', 'customer_id', 'user_id', 'request', 'reference', 'receipt', 'status', 'meta',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'customer_id' => 'integer',
        'amount'      => 'float',
        'paid'        => 'float',
        'balance'    => 'float',
        'meta'        => 'array',
    ];

    protected $appends = [
        'phone', 'name',
    ];

    public function getPhoneAttribute()
    {
        if (isset($this->customer)) {
            return $this->customer->phone;
        } elseif (isset($this->user)) {
            return $this->user->phone;
        }
    }

    public function getNameAttribute()
    {
        if (isset($this->customer)) {
            return "{$this->customer->first_name} {$this->customer->middle_name} {$this->customer->last_name}";
        } elseif (isset($this->user)) {
            return "{$this->user->name}";
        }
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}
