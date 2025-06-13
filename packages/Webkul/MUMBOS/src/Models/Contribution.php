<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\MUMBOS\Contracts\Contribution as ContributionContract;

class Contribution extends Model implements ContributionContract
{
    protected $table = 'contributions';

    protected $fillable = [
        'shareholder_id',
        'amount',
        'type',
        'reference',
        'note',
        'status',
        'contributed_at',
        'recorded_by',
    ];

    public function shareholder()
    {
        return $this->belongsTo(Shareholder::class);
    }
}