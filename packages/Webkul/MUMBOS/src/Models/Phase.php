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
    protected $fillable = ['name','share_value','description'];
    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
}