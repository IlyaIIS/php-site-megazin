<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

trait ErrorsTrait {
    private function getErrorPage(string $errorText, int $status): Response
    {
        return Response(view('error', ['errorText' => $errorText]), $status);
    }

    private function getUserAndErrorPage(Request $request): array
    {
        $sessionToken = $request->cookie('SESSION_TOKEN');
        $session = DB::table('sessions')->where('token', $sessionToken)->first();

        if ($session)
            $user = DB::table('users')->find($session->user_id);
        else
            return [null, $this->getErrorPage(
                "Не удалось определить пользователя. Попробуйте авторизоваться заново.",
                404
            )];

        if (!$user)
            return [null, $this->getErrorPage(
                "Не удалось определить пользователя. Попробуйте авторизоваться заново.",
                404
            )];

        return [$user, null];
    }

    private function getUserAndErrorResponse(Request $request): array
    {
        $sessionToken = $request->cookie('SESSION_TOKEN');
        $session = DB::table('sessions')->where('token', $sessionToken)->first();

        if ($session)
            $user = DB::table('users')->find($session->user_id);
        else
            return [null, response()
                ->json(['message' => 'Не удалось определить пользователя. Попробуйте авторизоваться заново.'], 401)
            ];

        if (!$user)
            return [null, response()
                ->json(['message' => 'Не удалось определить пользователя. Попробуйте авторизоваться заново.'], 401)
            ];

        return [$user, null];
    }
}
