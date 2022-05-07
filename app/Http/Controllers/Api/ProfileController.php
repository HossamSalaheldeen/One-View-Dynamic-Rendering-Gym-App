<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileRequest;
use App\Http\Resources\Api\UserResource;
use App\Traits\HasFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    use HasFiles;

    public function __invoke(ProfileRequest $request)
    {
        $user = auth()->user();

        $reqData = $request->only([
            'name',
            'national_id',
            'date_of_birth',
            'email'
        ]);

        if ($request->password) {
            $reqData['password'] = Hash::make($request->password);
        }

        $user->update($reqData);

        if ($request->hasFile('avatar')) {

            $attachment = $user->avatar()->first();
            $attachment->delete();

            $avatar = $request->avatar;
            $path = $this->storeFile('users', $avatar, $avatar->getClientOriginalName(), $user);
            $user->avatar()->create(['path' => $path]);
        }

        return UserResource::make($user)->additional([
            'message' =>  'Profile updated successfully'
        ])->response()->setStatusCode(Response::HTTP_ACCEPTED);

//        return response([
//            'message' =>  'Profile updated successfully',
//        ], Response::HTTP_ACCEPTED);
    }
}
