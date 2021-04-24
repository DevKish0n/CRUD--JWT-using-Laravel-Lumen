<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    //Register User
    public function register(Request $request)
    {
        $name = $request->name;
        $username = $request->username;
        $email = $request->email;
        $password = $request->password;
        $profile_image = $request->profile_image;

        // Check if field is not empty
        if (empty($name) or empty($username) or empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 'error', 'message' => 'You must enter a valid email']);
        }

        // Check if password is greater than 5 character
        if (strlen($password) < 8) {
            return response()->json(['status' => 'error', 'message' => 'Password should be min 8 character']);
        }

        // Check if user already exist
        if (User::where('email', '=', $email)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User already exists with this email']);
        }

        //Check if username already exists
        if (User::where('username', '=', $username)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User already exists with this username']);
        }

        // Create new user
        try {
            $user = new User();
            $user->name = $name;
            $user->username = $username;
            $user->email = $email;
            $user->password = app('hash')->make($password);
            $user->profile_image = $profile_image;

            if ($user->save()) {
                // Will call login method
                return $this->login($request);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    //Login method
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must need to fill all required fields!']);
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['status' => 'error', 'message' => 'Email or Password is incorrect!']);
        }

        return $this->respondWithToken($token);
    }

    //Logout user
    public function logout()
    {
        auth()->logout();

        return response()->json(['status' => 'success', 'message' => 'Successfully logged out!']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
