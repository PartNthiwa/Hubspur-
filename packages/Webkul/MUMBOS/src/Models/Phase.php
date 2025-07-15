<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\Phase as PhaseContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\MUMBOS\Models\Conribution;
use Webkul\Customer\Models\Customer;
use Webkul\User\Models\Admin;

class Phase extends Model implements PhaseContract
{

    use SoftDeletes;
    protected $fillable = ['name','share_value', 'starts_at',
    'ends_at','description'];

    protected $casts = [
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
}