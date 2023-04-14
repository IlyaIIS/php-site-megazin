<?php

namespace App\Http\Controllers;

use App\Traits\ErrorsTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminCabinetController extends Controller
{
    use ErrorsTrait;
    public function getAdminPage(Request $request)
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        if (!$user->is_admin)
            return $this->getErrorPage("У вас нет прав администратора!", 403);

        return view('admin_cabinet');
    }

    public function setUserSeller(Request $request): Response
    {
        $response = $this->updateUserPropertyOrReturnError(
            $request,
            'is_seller',
            true,
            'Не удалось обновить права пользователя!'
        );

        return $response?: response(status: 200);
    }

    public function unsetUserSeller(Request $request): Response
    {
        $response = $this->updateUserPropertyOrReturnError(
            $request,
            'is_seller',
            0, //false
            'Не удалось обновить права пользователя!'
        );

        return $response?: response(status: 200);
    }

    public function banUser(Request $request): Response
    {
        $response = $this->updateUserPropertyOrReturnError(
            $request,
            'is_banned',
            1, //true
            'Не удалось забанить пользователя!'
        );

        return $response?: response(status: 200);
    }

    private function updateUserPropertyOrReturnError(Request $request, string $property, string $value, string $exceptMessage) : ?Response
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        if (!$user->is_admin)
            return response()->json(['message' => 'У вас нет прав администратора!'], 404);

        $otherNickname = $request->input('nickname');
        $otherUser = DB::table('users')->where('nickname', $otherNickname)->first();
        if (!$otherUser)
            return response()->json(['message' => 'Пользователь с таким логином не найден!'], 404);

        try {
            DB::table('users')->where('id', $otherUser->id)->update([$property => $value]);
        } catch (Exception $e) {
            return response()->json(['message' => $exceptMessage], 404);
        }

        return null;
    }
}
