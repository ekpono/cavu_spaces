<?php

namespace App\Actions\User;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Lorisleiva\Actions\Concerns\AsAction;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Register
{
    use AsAction;

    public function handle(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $user = $this->createUser($data);

            return response()->json([
                'token' => $user->createToken('default')->plainTextToken,
                'user' => $user,
                'message' => 'User registration successful',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User registration failed',
            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
