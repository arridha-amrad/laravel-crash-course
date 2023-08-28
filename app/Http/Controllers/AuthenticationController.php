<?php

namespace App\Http\Controllers;

use App\GlobalConstants;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthenticationController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();
        $user = null;
        if (str_contains($validated['identity'], "@")) {
            $user = User::where('email', $validated["identity"])->first();
            if (!Auth::attempt(['email' => $validated['identity'], 'password' => $validated['password']])) {
                return response()->json(['message' => 'Invalid email and password'], 400);
            }
        } else {
            $user = User::where('username', $validated["identity"])->first();
            if (!Auth::attempt(['username' => $validated['identity'], 'password' => $validated['password']])) {
                return response()->json(['message' => 'Invalid username and password'], 400);
            }
        }
        if (is_null($user)) return response()->json(['message' => 'User not found'], 404);
        $userAgent = request()->header('user-agent') ?? "my-token";
        // i won't duplicate token for same user-agent of same user
        $user->tokens()->where(['name' => $userAgent, 'tokenable_id' => $user->id])->delete();
        $newAccToken = $user->createToken(name: $userAgent, expiresAt: now()->addYear());
        $token = $newAccToken->plainTextToken;
        $cookie = cookie(name: GlobalConstants::JWT_COOKIE, value: $token, minutes: 60 * 24 * 365);
        return response()->json([
            'user' => new UserResource($user),
        ])->withCookie($cookie);
    }

    public function me()
    {
        return new UserResource(request()->user());
    }

    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
        $cookie = Cookie::forget(GlobalConstants::JWT_COOKIE);
        return response(['message' => 'logout successfully'], 200)->withCookie($cookie);
    }
}
