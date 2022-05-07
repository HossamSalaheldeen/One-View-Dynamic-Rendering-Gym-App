<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use HasFiles;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
//            'national_id' => ['sometimes','required','string','unique:users,national_id,'],
            'date_of_birth' => ['required','date'],
            'gender' => ['required'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,svg', 'max:3072']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        //dd($data);
        $userData = Arr::only($data,[
            'name',
            'national_id',
            'email',
            'password',
            'date_of_birth',
            'gender'
        ]);

        $user =  User::create($userData);

        if (request()->hasFile('avatar')) {
            $avatar = request()->file('avatar');
            $path = $this->storeFile('users', $avatar,$avatar->getClientOriginalName() ,$user);
            $user->avatar()->create(['path' => $path]);
        }else {
            $user->avatar()->create(['path' => 'default-avatar.png']);
        }

        return $user;
    }
}
