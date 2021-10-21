<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Responses\ResourceDeletedResponse;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function newToken(Request $request): Response|UserResource|Application|ResponseFactory
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $fields['email'])->first();
        if (! $user || ! Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid Login',
            ], 401);
        }

        return (new UserResource($user))
            ->setMeta('token', $user->createToken('app')->plainTextToken);
    }

    public function revokeToken(Request $request): ResourceDeletedResponse
    {
        auth()->user()->tokens()->delete();

        return new ResourceDeletedResponse();
    }
}
