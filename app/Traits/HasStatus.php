<?php

namespace App\Traits;

trait HasStatus
{
    /**
     * Get status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->getStatusLabels()[$this->status] ?? 'Unknown';
    }

    /**
     * Get status color.
     *
     * @return string
     */
    public function getStatusColorAttribute(): string
    {
        return $this->getStatusColors()[$this->status] ?? 'gray';
    }

    /**
     * Get all status labels.
     *
     * @return array
     */
    abstract protected function getStatusLabels(): array;

    /**
     * Get all status colors.
     *
     * @return array
     */
    protected function getStatusColors(): array
    {
        return [];
    }

    /**
     * Scope a query to only include records with specific status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Change status.
     *
     * @param string $status
     * @return bool
     */
    public function changeStatus(string $status): bool
    {
        $this->status = $status;
        return $this->save();
    }
}
