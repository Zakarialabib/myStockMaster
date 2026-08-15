<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Http\Controllers\Api\Concerns\SafeApiQuery;

class UserController extends Controller
{
    use SafeApiQuery;

    /**
     * Retrieve a list of users with optional filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->get('_end') !== null) {
            $limit = (int) ($request->input('_end') ?? 10);
            $offset = (int) ($request->input('_start') ?? 0);
            $order = $this->safeOrderDirection($request);
            $sort = $this->safeSortColumn($request, ['id', 'name', 'email', 'created_at', 'updated_at']);

            $users = User::query()
                ->orderBy($sort, $order)
                ->offset($offset)
                ->limit($limit)
                ->get();
        } else {
            $users = User::query()->get();
        }

        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $storeUserRequest): UserResource
    {
        $user = User::query()->create($storeUserRequest->validated());

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): UserResource|JsonResponse
    {
        $user = User::query()->find($id);

        if ($user === null) {
            return new \Illuminate\Http\JsonResponse(['message' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $updateUserRequest, int $id): UserResource
    {
        $user = User::query()->findOrFail($id);
        $user->update($updateUserRequest->validated());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        $user->delete();

        return new \Illuminate\Http\JsonResponse(['message' => 'User deleted successfully']);
    }
}
