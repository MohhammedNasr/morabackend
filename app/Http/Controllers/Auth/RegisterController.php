<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
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
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = DB::transaction(function () use ($request) {
            $storeOwnerRole = Role::where('slug', 'store-owner')->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $storeOwnerRole->id,
            ]);

            $store = Store::create([
                'user_id' => $user->id,
                'name' => $request->store_name,
                'phone' => $request->phone,
                'commercial_record' => $request->commercial_record,
                'tax_record' => $request->tax_record,
                'credit_limit' => config('mora.default_credit_limit', 5000),
                'is_verified' => false,
            ]);

            Wallet::create([
                'store_id' => $store->id,
                'balance' => 0,
            ]);

            return $user;
        });

        auth()->login($user);

        return redirect()->route('verification.notice');
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
            'password' => ['required', 'confirmed', Password::defaults()],
            'store_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'unique:stores', 'regex:/^\+966[0-9]{9}$/'],
            'commercial_record' => ['required', 'string', 'unique:stores'],
            'tax_record' => ['required', 'string', 'unique:stores'],
        ], [
            'phone.regex' => 'The phone number must be a valid Saudi phone number starting with +966.',
        ]);
    }
}
