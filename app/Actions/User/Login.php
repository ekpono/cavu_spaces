<?php

namespace App\Actions\User;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserDetail;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Login
{
    use AsAction;

    public function asController(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->handle($request->validated());

            if (! $data) {
                return response()->json([
                    "message" => 'Invalid credentials.',
                ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
            }

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                "message" => 'Login failed.',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function handle(array $credentials): ?array
    {
        $success = null;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $success = [
                'token' => $user->createToken('default')->plainTextToken,
                'user' => new UserDetail($user),
                'message' => 'User login successful'
            ];
        }

        return $success;
    }
}
