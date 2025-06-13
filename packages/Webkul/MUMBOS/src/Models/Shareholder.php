<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\Shareholder as ShareholderContract;

class Shareholder extends Model implements ShareholderContract
{

    protected $table = 'shareholders';
    protected $fillable = [
        'customer_id',
        'shareholder_number',
        'full_name',
        'id_number',
        'kra_pin',
        'email',
        'phone',
        'postal_address',
        'physical_address',
        'city',
        'country',
        'share_class',
        'share_units',
        'capital_paid',
        'joined_at',
        'is_active',
        'is_board_member',
        'position',
        'id_document_path',
        'passport_photo_path',
        'signature_path',
        'last_profile_update',
    ];
     public function getRouteKeyName()
    {
        return 'shareholder_number';
    }
    protected $casts = [
        'is_active' => 'boolean',
    ];
    protected $dates = [
        'joined_at',
        'last_profile_update',
    ];
  public function customer()
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class, 'customer_id', 'id');
    }


    public function contributions()
    {
        return $this->hasMany(Contribution::class, 'shareholder_id');
    }
 
    public function shares()
    {
        return $this->belongsToMany(\Webkul\MUMBOS\Models\Share::class, 'shareholder_share')
                    ->withPivot('units')
                    ->withTimestamps();
    }

}