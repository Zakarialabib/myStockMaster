<?php

declare(strict_types=1);

namespace app\Http\Controllers\Api;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Retrieve a list of User with optional filters and pagination.
     */
    /**
     * Retrieve a list of expenses with optional filters and pagination.
     */
    #[Get('/api/users', name: 'api.users.index')]
    #[Middleware('api')]
    public function index(Request $request): AnonymousResourceCollection
    {

        if ($request->get('_end') !== null) {
            $limit = $request->get('_end') ? $request->get('_end') : 10;
            $offset = $request->get('_start') ? $request->get('_start') : 0;

            $order = $request->get('_order') ? $request->get('_order') : 'asc';
            $sort = $request->get('_sort') ? $request->get('_sort') : 'id';
            // Filters
            $where_raw = ' 1=1 ';

            // capture category_id filter
            $category_id = $request->get('category_id') ? $request->get('category_id') : '';

            if ($category_id !== '') {
                $where_raw .= " AND (category_id =  $category_id)";
            }
            // capture sort fields
            $sort_array = explode(',', $sort);

            if (count($sort_array) > 0) {
                // retireve ordered and limit expenses list
                $expenses = User::whereRaw($where_raw)
                    // ->orderByRaw("COALESCE($sort)")
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                // retireve ordered and limit expenses list
                $expenses = User::orderBy($sort, $order)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }
        } else {
            // retireve all expenses
            $expenses = User::get();
        }

        return UserResource::collection($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[Post('/api/users', name: 'api.users.store')]
    #[Middleware('api')]
    public function store(StoreUserRequest $request): UserResource
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    #[Get('/api/users/{id}', name: 'api.users.show')]
    #[Middleware('api')]
    public function show(int $id): UserResource|JsonResponse
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    #[Put('/api/users/{id}', name: 'api.users.update')]
    #[Middleware('api')]
    public function update(UpdateUserRequest $request, int $id): UserResource
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return $this->sendResponse($user, 'User deleted successfully');
        } catch (Exception $e) {
            DB::rollback();

            return $this->sendError($e->getMessage());
        }
    }
}
