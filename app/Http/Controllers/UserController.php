<?php

namespace App\Http\Controllers;

use App\Helpers\Device;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function tokens(Request $request)
    {
        $tokens = [];
        foreach($request->user()->tokens as $token) {
            $tokens[] = [
                'id' => $token->id,
                'name' => $token->name,
                'last_used_at' => $token->last_used_at
            ];
        }

        return response()->json([
            'tokens' => $tokens
        ]);
    }

    public function revokeAllTokens(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Tokens revoked, you will be logged out from all devices'
        ]);
    }
}
