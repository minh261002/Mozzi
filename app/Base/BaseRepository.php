<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->model = $this->makeModel();
    }

    /**
     * Make model instance
     *
     * @return Model
     */
    abstract protected function makeModel(): Model;

    /**
     * Get all records
     *
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model->get($columns);
    }

    /**
     * Find record by ID
     *
     * @param int $id
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, array $columns = ['*']): ?Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Find record by ID or fail
     *
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findOrFail(int $id, array $columns = ['*']): Model
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * Find by field
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return Collection
     */
    public function findByField(string $field, $value, array $columns = ['*']): Collection
    {
        return $this->model->where($field, $value)->get($columns);
    }

    /**
     * Find one by field
     *
     * @param string $field
     * @param mixed $value
     * @param array $columns
     * @return Model|null
     */
    public function findOneByField(string $field, $value, array $columns = ['*']): ?Model
    {
        return $this->model->where($field, $value)->first($columns);
    }

    /**
     * Create new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update record
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->findOrFail($id);
        return $record->update($data);
    }

    /**
     * Delete record
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $record = $this->findOrFail($id);
        return $record->delete();
    }

    /**
     * Paginate records
     *
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Get query builder
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * Apply filters to query
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        return $query;
    }

    /**
     * Apply search to query
     *
     * @param Builder $query
     * @param string|null $search
     * @param array $searchableFields
     * @return Builder
     */
    protected function applySearch(Builder $query, ?string $search, array $searchableFields): Builder
    {
        if (empty($search) || empty($searchableFields)) {
            return $query;
        }

        return $query->where(function ($q) use ($search, $searchableFields) {
            foreach ($searchableFields as $field) {
                $q->orWhere($field, 'LIKE', "%{$search}%");
            }
        });
    }

    /**
     * Apply sorting to query
     *
     * @param Builder $query
     * @param string|null $sortBy
     * @param string $sortOrder
     * @return Builder
     */
    protected function applySorting(Builder $query, ?string $sortBy, string $sortOrder = 'asc'): Builder
    {
        if (empty($sortBy)) {
            return $query;
        }

        return $query->orderBy($sortBy, $sortOrder);
    }

    /**
     * Get paginated data with filters, search, and sorting
     *
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getPaginatedData(array $params = []): LengthAwarePaginator
    {
        $query = $this->query();

        // Apply filters
        if (!empty($params['filters'])) {
            $query = $this->applyFilters($query, $params['filters']);
        }

        // Apply search
        if (!empty($params['search']) && !empty($params['searchable_fields'])) {
            $query = $this->applySearch($query, $params['search'], $params['searchable_fields']);
        }

        // Apply sorting
        if (!empty($params['sort_by'])) {
            $query = $this->applySorting($query, $params['sort_by'], $params['sort_order'] ?? 'asc');
        }

        // Apply default sorting if not specified
        if (empty($params['sort_by'])) {
            $query->latest();
        }

        $perPage = $params['per_page'] ?? 15;

        return $query->paginate($perPage);
    }

    /**
     * Bulk insert records
     *
     * @param array $data
     * @return bool
     */
    public function bulkInsert(array $data): bool
    {
        return $this->model->insert($data);
    }

    /**
     * Bulk delete records
     *
     * @param array $ids
     * @return int
     */
    public function bulkDelete(array $ids): int
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    /**
     * Count records
     *
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int
    {
        $query = $this->query();

        if (!empty($filters)) {
            $query = $this->applyFilters($query, $filters);
        }

        return $query->count();
    }

    /**
     * Check if record exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    /**
     * Get first record
     *
     * @param array $columns
     * @return Model|null
     */
    public function first(array $columns = ['*']): ?Model
    {
        return $this->model->first($columns);
    }

    /**
     * Restore soft deleted record
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        return $this->model->withTrashed()->find($id)?->restore() ?? false;
    }

    /**
     * Force delete record
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool
    {
        return $this->model->withTrashed()->find($id)?->forceDelete() ?? false;
    }
}
