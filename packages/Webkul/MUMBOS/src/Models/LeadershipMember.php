<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\LeadershipMember as LeadershipMemberContract;

class LeadershipMember extends Model implements LeadershipMemberContract
{



    /** 
     * The attributes that are mass assignable.
     * @var array
     */ 


     protected $table= 'leaders';

     
   protected $fillable = [
        'team_id',
        'name',
        'position',
        'slug',
        'photo',
        'bio',
        'email',
        'phone',
        'linkedin_url',
        'quote',
        'qualifications',
        'experience',
        'social_links',
        'status',
        'start_date',
        'end_date',
        'priority',
    ];

    protected $casts = [
        'social_links' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Scope: Active leaders
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}