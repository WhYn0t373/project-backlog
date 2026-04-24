<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles token refresh flow for authenticated users.
 *
 * The controller expects a valid refresh token to be supplied
 * in the request payload. Upon successful validation, it
 * revokes the old refresh token, issues a new access token,
 * and returns it to the client.
 */
class RefreshTokenController extends Controller
{
    /**
     * Refresh an access token using a refresh token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request): JsonResponse
    {
        // Validate incoming request.
        $request->validate([
            'refresh_token' => 'required|string',
        ]);

        $refreshTokenString = $request->input('refresh_token');

        try {
            // Retrieve the Sanctum token record.
            $token = Sanctum::findToken($refreshTokenString);

            if (! $token || $token->revoked) {
                return response()->json([
                    'message' => 'Invalid or revoked refresh token.',
                ], Response::HTTP_UNAUTHORIZED);
            }

            /** @var \App\Models\User $user */
            $user = $token->user;

            // Issue a new access token for the user.
            $newAccessToken = $user->createToken(
                'refresh',
                $token->abilities ?? ['*']
            );

            // Revoke the old refresh token.
            $token->delete();

            // Log the refresh action.
            Log::info('Refresh token revoked', [
                'user_id'     => $user->id,
                'old_token_id' => $token->id,
                'new_token_id' => $newAccessToken->accessToken->id,
            ]);

            return response()->json([
                'access_token' => $newAccessToken->plainTextToken,
                'token_type'   => 'Bearer',
                'expires_in'   => config('sanctum.expiration') * 60, // in seconds
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Token refresh error', ['exception' => $e]);

            return response()->json([
                'message' => 'An error occurred while refreshing the token.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}