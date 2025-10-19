<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchBalanceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_branch_id',
        'store_id',
        'requested_balance_limit',
        'business_type',
        'years_in_business',
        'average_monthly_revenue',
        'number_of_employees',
        'business_description',
        'tax_registration_number',
        'commercial_registration_number',
        'bank_account_number',
        'bank_name',
        'iban',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'contact_person_position',
        'documents',
        'status',
        'admin_notes',
        'rejection_reason',
        'approved_balance_limit',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'requested_balance_limit' => 'decimal:2',
        'average_monthly_revenue' => 'decimal:2',
        'approved_balance_limit' => 'decimal:2',
        'documents' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function storeBranch()
    {
        return $this->belongsTo(StoreBranch::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
