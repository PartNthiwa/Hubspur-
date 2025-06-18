<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\Share as ShareContract;

class Share extends Model implements ShareContract
{

    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'shares';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
    'class', 'units', 'available_units', 'price_per_unit', 'total_value',
    'description', 'icon_url', 'is_active', 'visibility'
];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $appends = ['total_value'];
    /**
     * Get the route key name for the model.
     *
     * @return string
     */

    public function shareholder()
    {
        return $this->belongsTo(\Webkul\MUMBOS\Models\Shareholder::class);
    }

    public function getTotalValueAttribute()
    {
        return $this->units * $this->price_per_unit;
    }
    public function shareholders()
{
    return $this->belongsToMany(\App\Models\Shareholder::class, 'shareholder_share')
                ->withPivot('units')
                ->withTimestamps();
}

}