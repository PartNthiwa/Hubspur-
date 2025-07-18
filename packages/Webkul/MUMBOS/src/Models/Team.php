<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\Team as TeamContract;

class Team extends Model implements TeamContract
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'teams';
    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
  
    public function leaders()
    {
        return $this->hasMany(LeadershipMember::class, 'team_id');
    }

}