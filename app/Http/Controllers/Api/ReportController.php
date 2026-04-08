<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetReportsRequest;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * Get reports with filtering and pagination.
     *
     * @param  GetReportsRequest  $request
     * @return JsonResponse
     */
    public function index(GetReportsRequest $request): JsonResponse
    {
        $filters = $request->validated();
        $limit = $filters['limit'] ?? 10;
        $page = $filters['page'] ?? 1;

        // Build the query with filters
        $query = Report::filter($filters);

        // Get total count before pagination
        $total = $query->count();

        // Apply pagination
        $reports = $query
            ->orderByDesc('created_at')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'message' => 'success',
            'data' => ReportResource::collection($reports->items()),
            'meta' => [
                'page' => $reports->currentPage(),
                'limit' => $reports->perPage(),
                'total' => $total,
            ],
        ]);
    }
}
