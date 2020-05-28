<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'phone',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    public function getPhoneAttribute($phone)
    {
        $phone = (substr($phone, 0, 1) == '+') ? str_replace('+', '', $phone) : $phone;
        return (substr($phone, 0, 1) == '0') ? preg_replace('/^0/', '254', $phone) : $phone;
    }

    public function setPhoneAttribute($phone)
    {
        $phone                     = (substr($phone, 0, 1) == '+') ? str_replace('+', '', $phone) : $phone;
        $this->attributes['phone'] = (substr($phone, 0, 1) == '0') ? preg_replace('/^0/', '254', $phone) : $phone;
    }

    public function getAvatarAttribute($avatar)
    {
        return $avatar . $this->phone;
    }

    public function getPaymentCountAttribute()
    {
        return array(
            "success"   => ['completed', Payment::where('customer_id', $this->id)->where('status', 'completed')->count()],
            "info"      => ['processing', Payment::where('customer_id', $this->id)->where('status', 'processing')->count()],
            "light"     => ['pending', Payment::where('customer_id', $this->id)->where('status', 'pending')->count()],
            "warning"   => ['cancelled', Payment::where('customer_id', $this->id)->where('status', 'cancelled')->count()],
            "danger"    => ['failed', Payment::where('customer_id', $this->id)->where('status', 'failed')->count()],
        );
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function payments()
    {
        return $this->hasMany(Activity::class);
    }

    public function sms($message = '')
    {
        return send_sms($this->phone, $message);
    }
}
