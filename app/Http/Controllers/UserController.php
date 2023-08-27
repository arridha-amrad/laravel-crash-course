<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new UserResourceCollection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new UserResource(User::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserRequest $request, string $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $validated = $request->validated();

        $user->first_name = $validated['firstName'];
        $user->last_name = $validated['lastName'];

        $user->save();

        return response([
            'message' => 'user updated successfully',
            'updatedUser' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if (is_null($user)) return response()->json(['message' => 'user not found'], 404);
        $user->delete();
        return response([
            'message' => 'user deleted successfully',
            'deletedUser' => $user
        ]);
    }
}
