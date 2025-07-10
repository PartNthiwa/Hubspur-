<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\Incentive as IncentiveContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\Customer\Models\Customer;
use Webkul\User\Models\Admin;

class Incentive extends Model implements IncentiveContract
{
    protected $fillable = ['type','metadata','description'];

    public function shareholder() {
         return $this->belongsTo(Shareholder::class); 
    }

      // Scopes for each incentive type
    public function scopeFirst($query)
    {
        return $query->where('type','first');
    }

    public function scopeSecond($query)
    {
        return $query->where('type','second');
    }

    public function scopeThird($query)
    {
        return $query->where('type','third');
    }
    public function scopeOther($query)
    {
        return $query->where('type','other');
    }

public function shareholders()
{
    return $this->belongsToMany(Shareholder::class, 'incentive_shareholder')
                ->withPivot('units')
                ->withTimestamps();
}

public function getRouteKeyName()
{
    return 'id'; 
}

}