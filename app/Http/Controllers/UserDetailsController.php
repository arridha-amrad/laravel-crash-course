<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserDetailsRequest;
use App\Http\Resources\UserDetailsResource;
use App\Models\UserDetails;
use Illuminate\Http\JsonResponse;

class UserDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserDetails::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserDetailsRequest $request)
    {
        $validated = $request->validated();
        $userId = request()->user()->id;

        if (!is_null(UserDetails::where('user_id', $userId)->first())) {
            return response()->json([
                'message' => 'Failed storing your details. If you need to change, please consider to update',
            ], 400);
        }

        $userDetails = UserDetails::create([
            'user_id' => $userId,
            'city' => $validated['city'],
            'postal_code' => $validated['postalCode'],
            'phone_number' => $validated['phoneNumber']
        ]);

        return new JsonResponse([
            'message' => 'new user details created successfully',
            'user' => new UserDetailsResource($userDetails)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userDetail = UserDetails::find($id);
        if (is_null($userDetail)) {
            return response(['message' => 'Details not found'], 404);
        }
        return new UserDetailsResource($userDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUserDetailsRequest $request, string $id)
    {
        $userDetails = UserDetails::findOr($id, function () {
            return response(['message' => 'Details not found'], 404);
        });
        $validated = $request->validated();
        $userDetails->city = $validated['city'];
        $userDetails->phone_number = $validated['phoneNumber'];
        $userDetails->postal_code = $validated['postalCode'];
        $userDetails->save();
        return response(['message' => 'Details updated successfully', 'data' => new UserDetailsResource($userDetails)]);
        // dd($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreUserDetailsRequest $request, string $id)
    {
        $request->validated();
        $details = UserDetails::find($id);
        if (is_null($details)) return response(['message' => 'Details not found']);
        $details->delete();
        return response(['message' => 'One Details deleted']);
    }
}
