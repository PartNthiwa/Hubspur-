<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\MembershipType as MembershipTypeContract;

class MembershipType extends Model implements MembershipTypeContract
{ protected $table = 'membership_types';

    protected $fillable = [
        'type',
        'share_value',
        'description',
        'is_active',
        'visibility',
    ];

    protected $casts = [
    'is_active' => 'boolean',
];

    /**
     * Get all shareholders under this membership type.
     */
public function shareholders()
{
    return $this->belongsToMany(Shareholder::class, 'membership_type_shareholder')
        ->withPivot('amount_paid', 'joined_at')
        ->withTimestamps();
}



}