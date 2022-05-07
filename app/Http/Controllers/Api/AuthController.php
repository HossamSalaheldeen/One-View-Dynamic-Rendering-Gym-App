<?php

namespace App\Http\Controllers\Api;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\LoginResource;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use HasFiles;

    public function register(RegisterRequest $request)
    {
        $reqData = $request->only([
            'name',
            'national_id',
            'date_of_birth',
            'email',
            'password',
            'avatar',
            'gender'
        ]);

        $user = User::create($reqData);

        if ($request->hasFile('avatar')) {
            $avatar = $request->avatar;
            $path = $this->storeFile('users', $avatar, $avatar->getClientOriginalName(), $user);
            $user->avatar()->create(['path' => $path]);
        } else {
            $user->avatar()->create(['path' => 'default-avatar.png']);
        }

        $user->assignRole(RoleEnum::USER);

        return UserResource::make($user);


    }

    public function login(LoginRequest $request)
    {
        $reqData = $request->only([
            'email',
            'password',
        ]);

        if (!auth()->attempt($reqData)){
            throw ValidationException::withMessages( [ 'message' => 'Invalid Credentials' ] );
        }

        $user = User::query()->where('email',$reqData['email'])->first();

        $token = $user->createToken('api_token');

        return LoginResource::make($token);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'user logout successfully',
        ], Response::HTTP_OK);
    }
}
