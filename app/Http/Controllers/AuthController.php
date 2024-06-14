<?php

namespace App\Http\Controllers;

use App\Mail\MailNotify;
use App\Mail\ChangePassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Manila');
    }
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Mail::to($request->email)->send(new MailNotify($user->id));
        return response()->json(['message' => 'Registration successful!'], 200);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            if ($user->verified == 0) {
                Auth::logout();
                return response()->json(['error' => 'Please verify your email before logging in.'], 403);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token]);
        }

        return response()->json(['errors' => ['message' => 'Incorrect email or password.']], 422);
    }
    public function forgotPassword(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['errors' => ['message' => 'Email does not exist.']], 422);
        }
        Mail::to($request->email)->send(new ChangePassword($user->id));
        return response()->json(['message' => 'Check your email for change password link.'], 200);
    }
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::whereRaw('MD5(id) = ?', [$request->input('id')])->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json(['message' => 'Password updated successfully!'], 200);
    }
    public function changePassword($userId)
    {
        $user = User::whereRaw('MD5(id) = ?', [$userId])->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $email = $user->email;
        Mail::to($email)->send(new ChangePassword($user->id));

        return redirect()->away("http://localhost:3000/change-password/{$userId}");
    }

    public function verifyEmail($userId)
    {
        $user = User::whereRaw('MD5(id) = ?', [$userId])->first();
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->email_verified_at = now();
        $user->save();

        $user->verified = 1;
        $user->save();
        return redirect()->away("http://localhost:3000/email-verified/{$userId}");
    }
    public function logout(Request $request)
    {
        $user = $request->User();
        $user->currentAccessToken()->delete();
        return response(['message' => 'Logout Successfully!']);
    }

    public function fetchCurrentUser(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        return response()->json($user);
    }
}
