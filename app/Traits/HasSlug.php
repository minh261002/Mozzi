<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * Boot the trait.
     */
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            if (empty($model->slug) && !empty($model->{$model->getSlugSource()})) {
                $model->slug = $model->generateSlug($model->{$model->getSlugSource()});
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->getSlugSource())) {
                $model->slug = $model->generateSlug($model->{$model->getSlugSource()});
            }
        });
    }

    /**
     * Get the source field for slug generation.
     *
     * @return string
     */
    protected function getSlugSource(): string
    {
        return property_exists($this, 'slugSource') ? $this->slugSource : 'name';
    }

    /**
     * Generate a unique slug.
     *
     * @param string $value
     * @return string
     */
    protected function generateSlug(string $value): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Check if slug exists.
     *
     * @param string $slug
     * @return bool
     */
    protected function slugExists(string $slug): bool
    {
        $query = static::where('slug', $slug);

        if ($this->exists) {
            $query->where($this->getKeyName(), '!=', $this->getKey());
        }

        return $query->exists();
    }

    /**
     * Find by slug.
     *
     * @param string $slug
     * @return static|null
     */
    public static function findBySlug(string $slug)
    {
        return static::where('slug', $slug)->first();
    }

    /**
     * Find by slug or fail.
     *
     * @param string $slug
     * @return static
     */
    public static function findBySlugOrFail(string $slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }
}
