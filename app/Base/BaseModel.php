<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Scope a query to only include active records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to search by keyword.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @param array $fields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $keyword, array $fields = [])
    {
        if (empty($keyword) || empty($fields)) {
            return $query;
        }

        return $query->where(function ($q) use ($keyword, $fields) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'LIKE', "%{$keyword}%");
            }
        });
    }

    /**
     * Scope a query to filter by date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $field
     * @param string|null $from
     * @param string|null $to
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, $field, $from = null, $to = null)
    {
        if ($from) {
            $query->whereDate($field, '>=', $from);
        }

        if ($to) {
            $query->whereDate($field, '<=', $to);
        }

        return $query;
    }
}
