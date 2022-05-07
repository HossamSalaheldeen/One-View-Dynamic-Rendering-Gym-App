<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\PaymentRequest;
use App\Models\Gym;
use App\Models\Revenue;
use App\Models\TrainingPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private $resource;

    public function __construct()
    {
        $this->middleware('role:'.RoleEnum::ADMIN.'|'.RoleEnum::City_MANAGER.'|'.RoleEnum::GYM_MANAGER.'|'.RoleEnum::USER);
        $this->resource = 'payments';

    }

    public function index()
    {
        $isEdit = false;
        $resource = $this->resource;
        $fields = [];
        $gymFieldShowRoles = [RoleEnum::ADMIN, RoleEnum::City_MANAGER, RoleEnum::USER];
        $userFieldShowRoles = [RoleEnum::ADMIN, RoleEnum::City_MANAGER,RoleEnum::GYM_MANAGER];
        return view('payments.index', get_defined_vars());
    }

    public function store(PaymentRequest $request)
    {

        $reqData = $request->only([
            'user_id',
            'training_package_id',
            'gym_id',
        ]);

        if (!$request->gym_id) {
            $reqData['gym_id'] = auth()->user()->gym_id;
        } else if (!$request->user_id) {
            $reqData['user_id'] = auth()->id();
        }

        $user = User::query()->find($reqData['user_id']);
        $gym = Gym::query()->find($reqData['gym_id']);
        $trainingPackage = TrainingPackage::query()->with('trainingSessions')->find($reqData['training_package_id']);

        $trainingSessions = $trainingPackage->trainingSessions->modelKeys();

        $user->trainingSessions()->attach($trainingSessions, ['gym_id' => $reqData['gym_id']]);
        $user->trainingPackages()->attach($trainingPackage->id);
        $gym->trainingPackages()->attach($trainingPackage->id);

        $amount = $trainingPackage->dollar_price;
        $reqData['amount'] = $amount;
        $revenue = Revenue::create($reqData);

//        Stripe::setApiKey(env('STRIPE_SECRET'));
//        Charge::create([
//            "amount" => 100 * 100,
//            "currency" => "usd",
//            "source" => $request->stripeToken,
//        ]);
        return response([
            'message' => ' payment successfully',
        ], Response::HTTP_OK);
//        \Session::flash('success', 'Payment successful!');
//
//        return to_route('payments.index');
    }
}
