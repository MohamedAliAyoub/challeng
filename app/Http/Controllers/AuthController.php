<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->post('email');
        $this->validate($request, [
            'email' => ['required', Rule::when(!is_numeric($email), 'email')],
            'password' => 'required',
        ]);
        $user = User::query()->verified()->where(valid_email($email) ? 'email' : 'phone', $email)->first();

        if (!is_null($user) && Hash::check($request->post('password'), $user->password)) {
            do {
                $token = Str::random(64);
            } while (User::query()->where('api_token', $token)->count() > 0);
            $user->update(['api_token' => $token]);
            return response()->json([
                'success' => true,
                'api_token' => $token
            ]);
        } else
            return response()->json([
                'success' => false,
                'error' => 'failed login message.'
            ], 422);
    }

    public function otp(Request $request)
    {
        $this->validate($request, [
            'type' => ['required', 'in:email,phone'],
            'username' => ['required', Rule::when($request->post('type') === 'email', 'email', 'numeric')]
        ]);
        $otp = App::environment('local') ? '1234' : rand(1000, 9999);
        $hash = Str::random(64);
        // send otp by email or sms here
        Cache::put('otp_' . $hash, [
            'id' => $request->post('username'),
            'type' => $request->post('type'),
            'otp' => $otp,
        ], Carbon::now()->addMinutes(10)); // otp valid for 10 min
        return response()->json([
            'otp_id' => $hash
        ]);
    }

    public function register(Request $request)
    {
        $this->validate($request, array_merge([
            'name' => 'required|array',
            'email' => 'required|unique:users|email',
            'phone' => 'required|numeric|unique:users',
            'password' => 'required',
        ], collect(array_map(function ($locale) {
            return [
                "name.$locale" => 'required|string|max:128',
            ];
        }, locales()))->collapse()->toArray()));

        $otpHash = 'otp_' . $request->post('otp_id');
        if (Cache::missing($otpHash))
            return response()->json(['success' => false, 'error' => 'otp hash invalid'], 400);

        $otp = Cache::get($otpHash);
        if ($request->post(@$otp['type']) === @$otp['id'] && $request->post('otp') === @$otp['otp']) {
            $password = Hash::make($request->post('password'));
            $user = User::query()->create(array_merge($request->only(['name', 'email', 'phone']), [
                'password' => $password
            ]));

            do {
                $token = Str::random(64);
            } while (User::query()->where('api_token', $token)->count() > 0);
            $user->update(['api_token' => $token]);
            return response()->json([
                'success' => true,
                'api_token' => $token
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'invalid otp'
        ], 401);
    }
}
