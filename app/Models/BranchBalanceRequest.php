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
        'request_number',
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
        // Employee approval
        'employee_status',
        'employee_comment',
        'employee_reviewed_by',
        'employee_reviewed_at',
        // Manager approval
        'manager_status',
        'manager_comment',
        'manager_reviewed_by',
        'manager_reviewed_at',
        // Partner approval
        'partner_status',
        'partner_comment',
        'partner_reviewed_by',
        'partner_reviewed_at',
    ];

    protected $casts = [
        'requested_balance_limit' => 'decimal:2',
        'average_monthly_revenue' => 'decimal:2',
        'approved_balance_limit' => 'decimal:2',
        'documents' => 'array',
        'reviewed_at' => 'datetime',
        'employee_reviewed_at' => 'datetime',
        'manager_reviewed_at' => 'datetime',
        'partner_reviewed_at' => 'datetime',
    ];

    /**
     * Boot the model and generate request number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->request_number)) {
                $model->request_number = static::generateRequestNumber();
            }
        });
    }

    /**
     * Generate a unique request number
     */
    protected static function generateRequestNumber(): string
    {
        do {
            $number = 'REQ' . str_pad(rand(1, 9999999), 7, '0', STR_PAD_LEFT);
        } while (static::where('request_number', $number)->exists());

        return $number;
    }

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

    public function employeeReviewer()
    {
        return $this->belongsTo(User::class, 'employee_reviewed_by');
    }

    public function managerReviewer()
    {
        return $this->belongsTo(User::class, 'manager_reviewed_by');
    }

    public function partnerReviewer()
    {
        return $this->belongsTo(User::class, 'partner_reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeEmployeeReview($query)
    {
        return $query->where('status', 'employee_review');
    }

    public function scopeManagerReview($query)
    {
        return $query->where('status', 'manager_review');
    }

    public function scopePartnerReview($query)
    {
        return $query->where('status', 'partner_review');
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
