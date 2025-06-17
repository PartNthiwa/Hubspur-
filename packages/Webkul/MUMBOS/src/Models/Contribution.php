<?php

namespace Webkul\MUMBOS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\Customer\Models\Customer;
use Webkul\User\Models\Admin;
use Webkul\MUMBOS\Contracts\Contribution as ContributionContract;

class Contribution extends Model implements ContributionContract
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contributions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shareholder_id', 'amount', 'currency', 'payment_method',
        'payment_channel', 'payment_reference', 'payment_receipt',
        'payment_status', 'payment_metadata', 'payment_fee',
        'paid_at', 'contributed_at', 'status',
        'recorded_by', 'approved_by', 'approved_at', 'note',
        'created_by', 'updated_by', 'deleted_by','receipt_url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payment_metadata' => 'array',
        'contributed_at' => 'date',
        'paid_at' => 'datetime',
        'approved_at' => 'datetime',
    ];
  
    
    public function shareholder()
    {
        return $this->belongsTo(Shareholder::class);
    }

    /** Shortcut to the underlying customer via the shareholder */
    public function customer()
    {
        // hasOneThrough: Customer ← Shareholder ← Contribution
        return $this->hasOneThrough(
            Customer::class,
            Shareholder::class,
            'id',            // Foreign key on shareholders table...
            'id',            // Foreign key on customers table...
            'shareholder_id',// Local key on contributions table...
            'customer_id'    // Local key on shareholders table...
        );
    }

    /** The admin who recorded this contribution */
    public function recordedBy()
    {
        return $this->belongsTo(Admin::class, 'recorded_by');
    }

    /** The admin who approved this contribution */
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /** User who created the record (if different from recorded_by) */
    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /** User who last updated the record */
    public function updater()
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /** User who soft‑deleted the record */
    public function deleter()
    {
        return $this->belongsTo(Admin::class, 'deleted_by');
    }

    //
    // Scopes for common filters
    //

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }
 
}