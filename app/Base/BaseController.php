<?php

namespace App\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

abstract class BaseController extends Controller
{
    /**
     * @var BaseService
     */
    protected $service;

    /**
     * Get service instance
     *
     * @return BaseService
     */
    abstract protected function getService(): BaseService;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->service = $this->getService();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = [
                'per_page' => $request->input('per_page', 15),
                'search' => $request->input('search'),
                'searchable_fields' => $this->getSearchableFields(),
                'filters' => $request->input('filters', []),
                'sort_by' => $request->input('sort_by'),
                'sort_order' => $request->input('sort_order', 'asc'),
            ];

            $data = $this->service->getPaginatedData($params);

            return $this->successResponse($data, 'Data retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate($this->getStoreRules());
            $model = $this->service->create($validatedData);

            return $this->successResponse($model, 'Record created successfully', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $model = $this->service->findByIdOrFail($id);

            return $this->successResponse($model, 'Record retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validate($this->getUpdateRules($id));
            $model = $this->service->update($id, $validatedData);

            return $this->successResponse($model, 'Record updated successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return $this->successResponse(null, 'Record deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Bulk delete resources
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer'
            ]);

            $count = $this->service->bulkDelete($request->input('ids'));

            return $this->successResponse(
                ['deleted_count' => $count],
                "{$count} records deleted successfully"
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Restore soft deleted resource
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        try {
            $this->service->restore($id);

            return $this->successResponse(null, 'Record restored successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    /**
     * Get validation rules for store
     *
     * @return array
     */
    abstract protected function getStoreRules(): array;

    /**
     * Get validation rules for update
     *
     * @param int $id
     * @return array
     */
    abstract protected function getUpdateRules(int $id): array;

    /**
     * Get searchable fields
     *
     * @return array
     */
    protected function getSearchableFields(): array
    {
        return [];
    }

    /**
     * Success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Error response
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Error', int $code = 500, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response
     *
     * @param array $errors
     * @param string $message
     * @return JsonResponse
     */
    protected function validationErrorResponse(array $errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }
}
