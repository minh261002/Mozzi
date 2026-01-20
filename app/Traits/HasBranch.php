<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasBranch
{
    /**
     * Boot the trait.
     */
    protected static function bootHasBranch()
    {
        // Auto-assign branch when creating
        static::creating(function ($model) {
            if (empty($model->branch_id) && Auth::check()) {
                $model->branch_id = Auth::user()->branch_id;
            }
        });

        // Global scope: filter by branch (except for admin users)
        static::addGlobalScope('branch', function ($builder) {
            if (Auth::check() && !Auth::user()->isAdmin()) {
                // Staff can only see data from their branch
                $builder->where('branch_id', Auth::user()->branch_id);
            }
        });
    }

    /**
     * Scope a query to only include records from specific branch.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $branchId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to include records from all branches (admin only).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllBranches($query)
    {
        return $query->withoutGlobalScope('branch');
    }

    /**
     * Branch relationship.
     */
    // public function branch()
    // {
    //     return $this->belongsTo(\App\Models\Branch::class);
    // }

    /**
     * Check if record belongs to specific branch.
     *
     * @param int $branchId
     * @return bool
     */
    public function belongsToBranch(int $branchId): bool
    {
        return $this->branch_id === $branchId;
    }

    /**
     * Check if record belongs to current user's branch.
     *
     * @return bool
     */
    public function belongsToMyBranch(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->branch_id === Auth::user()->branch_id;
    }
}
