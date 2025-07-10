<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Models\Incentive;
use Webkul\MUMBOS\Models\MembershipType;
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
        'is_board_member' => 'boolean',
        'share_units' => 'integer',
        'capital_paid' => 'decimal:2',
        'joined_at' => 'datetime',
        'last_profile_update' => 'datetime',
        'id_document_path' => 'string',
        'passport_photo_path' => 'string',
        'signature_path' => 'string',
        'customer_id' => 'integer',
        'share_class' => 'string',
        'position' => 'string',
        'full_name' => 'string',
        'id_number' => 'string',
        'kra_pin' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'postal_address' => 'string',
        'physical_address' => 'string',
        'city' => 'string', 
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
  

 /** Only “first” incentives */
    public function firstIncentives()
    {
        return $this->incentives()->first();
    }

    /** Only “second” incentives */
    public function secondIncentives()
    {
        return $this->incentives()->second();
    }

    /** Only “third” incentives (if you use it) */
    public function thirdIncentives()
    {
        return $this->incentives()->third();
    }
    /** Only “third” incentives (if you use it) */
    public function otherIncentives()
    {
        return $this->incentives()->other();
    }




    // ------------------------------------------------------------------------
    // Computed accessors
    // ------------------------------------------------------------------------

    /** Sum of first-incentive units */
    public function getFirstIncentiveUnitsAttribute()
    {
        return $this->firstIncentives->sum('units');
    }

    /** Sum of second-incentive units */
    public function getSecondIncentiveUnitsAttribute()
    {
        return $this->secondIncentives->sum('units');
    }

    /** Sum of third-incentive units */
    public function getThirdIncentiveUnitsAttribute()
    {
        return $this->thirdIncentives->sum('units');
    }
   public function getOtherIncentiveUnitsAttribute()
    {
        return $this->otherIncentives->sum('units');
    }
    /** Total of all incentive units (fallback) */
    public function getIncentiveUnitsAttribute()
    {
        return $this->incentives->sum('units');
    }

public function incentives()
{
    return $this->belongsToMany(Incentive::class, 'incentive_shareholder')
                ->withPivot('units')
                ->withTimestamps();
}

     /** Capital shares computed from contributions + their phase value */
public function getCapitalShareUnitsAttribute()
    {
        return $this->contributions
            ->sum(function($contrib) {
                $phaseValue = optional($contrib->phase)->share_value;
                if (! $phaseValue) {
                    return 0;
                }
                // compute raw shares, then round to nearest whole share
                $rawShares = $contrib->amount / $phaseValue;
                return (int) round($rawShares, 0, PHP_ROUND_HALF_UP);
            });
    }




    public function membershipTypes()
    {
        return $this->belongsToMany(MembershipType::class, 'membership_type_shareholder')
            ->withPivot('amount_paid', 'joined_at')
            ->withTimestamps();
    }
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }



}