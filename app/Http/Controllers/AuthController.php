<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use News\Models\User;
use Illuminate\Support\Facades\Auth;
use News\Traits\ApiResponseTrait;
use News\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use News\ValidationRequests\UserRegisterRequest;
use News\ValidationRequests\ForgotPasswordRequest;
use News\ValidationRequests\LoginRequests;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Password;



class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * Register a new user.
     *
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        $validatedData = $request->validated(); // Use validated data directly

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->successResponse($this->respondWithToken($token), 'User registered successfully!', Response::HTTP_CREATED);
    }

    /**
     * Log in a user.
     *
     * @param LoginRequests $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequests $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->failureResponse('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->successResponse($this->respondWithToken($token), 'User logged in successfully!', Response::HTTP_OK);
    }

    /**
     * Log out a user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return $this->successResponse(null, 'Logged out successfully', Response::HTTP_OK);
    }

    /**
     * Prepare response with the token.
     *
     * @param string $token
     * @return array
     */
    protected function respondWithToken(string $token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
        ];
    }

    /**
     * Send reset link to the user's email.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $token = Password::createToken($user);
        $user->notify(new ResetPasswordNotification($token));

        return $this->successResponse(null, 'Password reset link sent successfully!', Response::HTTP_OK);
    }

    /**
     * Reset the user's password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $resetStatus = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->password = bcrypt($request->password);
                $user->save();
            }
        );
        if ($resetStatus === Password::PASSWORD_RESET) {
            return $this->successResponse(null, 'Password has been reset successfully!', Response::HTTP_OK);
        }
        return $this->failureResponse('Failed to reset password!', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}