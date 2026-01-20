<?php

namespace App\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

abstract class BaseService
{
    /**
     * @var BaseRepository
     */
    protected $repository;

    /**
     * Get repository instance
     *
     * @return BaseRepository
     */
    abstract protected function getRepository(): BaseRepository;

    /**
     * BaseService constructor.
     */
    public function __construct()
    {
        $this->repository = $this->getRepository();
    }

    /**
     * Get all records
     *
     * @param array $columns
     * @return Collection
     */
    public function getAll(array $columns = ['*']): Collection
    {
        return $this->repository->all($columns);
    }

    /**
     * Find record by ID
     *
     * @param int $id
     * @param array $columns
     * @return Model|null
     */
    public function findById(int $id, array $columns = ['*']): ?Model
    {
        return $this->repository->find($id, $columns);
    }

    /**
     * Find record by ID or fail
     *
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function findByIdOrFail(int $id, array $columns = ['*']): Model
    {
        return $this->repository->findOrFail($id, $columns);
    }

    /**
     * Create new record
     *
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function create(array $data): Model
    {
        DB::beginTransaction();

        try {
            $data = $this->beforeCreate($data);
            $model = $this->repository->create($data);
            $this->afterCreate($model, $data);

            DB::commit();

            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating record: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    /**
     * Update record
     *
     * @param int $id
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function update(int $id, array $data): Model
    {
        DB::beginTransaction();

        try {
            $model = $this->repository->findOrFail($id);
            $data = $this->beforeUpdate($model, $data);
            $this->repository->update($id, $data);
            $model->refresh();
            $this->afterUpdate($model, $data);

            DB::commit();

            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating record: ' . $e->getMessage(), [
                'id' => $id,
                'data' => $data,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    /**
     * Delete record
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        DB::beginTransaction();

        try {
            $model = $this->repository->findOrFail($id);
            $this->beforeDelete($model);
            $result = $this->repository->delete($id);
            $this->afterDelete($model);

            DB::commit();

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting record: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    /**
     * Get paginated data with filters
     *
     * @param array $params
     * @return LengthAwarePaginator
     */
    public function getPaginatedData(array $params = []): LengthAwarePaginator
    {
        return $this->repository->getPaginatedData($params);
    }

    /**
     * Bulk create records
     *
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function bulkCreate(array $data): bool
    {
        DB::beginTransaction();

        try {
            $result = $this->repository->bulkInsert($data);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error bulk creating records: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    /**
     * Bulk delete records
     *
     * @param array $ids
     * @return int
     * @throws Exception
     */
    public function bulkDelete(array $ids): int
    {
        DB::beginTransaction();

        try {
            $result = $this->repository->bulkDelete($ids);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error bulk deleting records: ' . $e->getMessage(), [
                'ids' => $ids,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    /**
     * Count records
     *
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int
    {
        return $this->repository->count($filters);
    }

    /**
     * Check if record exists
     *
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return $this->repository->exists($id);
    }

    /**
     * Restore soft deleted record
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function restore(int $id): bool
    {
        DB::beginTransaction();

        try {
            $result = $this->repository->restore($id);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error restoring record: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            throw $e;
        }
    }

    /**
     * Hook: Before create
     *
     * @param array $data
     * @return array
     */
    protected function beforeCreate(array $data): array
    {
        return $data;
    }

    /**
     * Hook: After create
     *
     * @param Model $model
     * @param array $data
     * @return void
     */
    protected function afterCreate(Model $model, array $data): void
    {
        //
    }

    /**
     * Hook: Before update
     *
     * @param Model $model
     * @param array $data
     * @return array
     */
    protected function beforeUpdate(Model $model, array $data): array
    {
        return $data;
    }

    /**
     * Hook: After update
     *
     * @param Model $model
     * @param array $data
     * @return void
     */
    protected function afterUpdate(Model $model, array $data): void
    {
        //
    }

    /**
     * Hook: Before delete
     *
     * @param Model $model
     * @return void
     */
    protected function beforeDelete(Model $model): void
    {
        //
    }

    /**
     * Hook: After delete
     *
     * @param Model $model
     * @return void
     */
    protected function afterDelete(Model $model): void
    {
        //
    }
}
