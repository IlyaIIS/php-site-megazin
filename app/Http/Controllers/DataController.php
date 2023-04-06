<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DataController extends Controller
{
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

    private function saveImageAndGetId(mixed $image, mixed $user, string $inputPath): int
    {
        $hash = md5_file($image);

        $sameImage = DB::table('images')->where('hash', $hash)->first();
        if ($sameImage) {
            $image = $sameImage;
            $imageId = $image->id;
        } else {
            $path = Storage::put('public/' . $inputPath, $image);
            $path_parts = explode('/', $path);
            $name = end($path_parts);

            $imageId = DB::table('images')->insertGetId([
                'name' => $name,
                'path' => 'storage/' . $inputPath,
                'hash' => $hash,
                'user_id' => $user->id
            ]);
        }

        return $imageId;
    }

    private function sendEmail(string $targetEmail, string $message) {
        $message = wordwrap($message, 70, "\r\n");
        mail($targetEmail, 'Notification', $message);
    }

    public function modifyUser(Request $request)
    {
        $request->validate([
            'inputEmail' => 'email|max:200',
            'inputPassword' => 'min:6|max:200',
            'inputPasswordRepeat' => 'same:inputPassword',
            'inputNickname' => 'max:200',
            'inputFirstName' => 'max:200',
            'inputLastName' => 'max:200',
            'inputBirthday' => 'date|before:-13 years',
            'inputCity' => 'max:200',
            'inputStreet' => 'max:200',
            'inputHouse' => 'max:200',
        ]);
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $updateData = [];

        if ($input = $request->input('inputFirstName'))
            $updateData['first_name'] = $input;
        if ($input = $request->input('inputLastName'))
            $updateData['last_name'] = $input;

        if ($input = $request->input('inputNickname'))
            $updateData['nickname'] = $input;
        if ($input = $request->input('inputBirthday'))
            $updateData['birthday'] = $input;

        if ($input = $request->input('inputCity'))
            $updateData['city'] = $input;
        if ($input = $request->input('inputStreet'))
            $updateData['street'] = $input;
        if ($input = $request->input('inputHouse'))
            $updateData['house'] = $input;
        if ($input = $request->input('inputApartment'))
            $updateData['apartment'] = $input;
        elseif ($user->apartment)
            $updateData['apartment'] = null;

        //todo: добавить проверку отправкой кода на email после выхода с localhost на публичный ip
        if ($input = $request->input('inputPassword'))
            $updateData['password_hash'] = password_hash($user->email . $input, PASSWORD_BCRYPT);

        if (count($updateData) == 0)
            return response()->json(['message' => 'Нечего изменять: поля пустые!'], 400);

        try {
            DB::table('users')->update($updateData);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Не удалось применить изменения: какие-то неполадки на сервере :('
            ], 500);
        }

        return response(status: 200);
    }

    public function modifyUserAvatar(Request $request)
    {
        $request->validate([
            'inputImage' => 'image',
        ]);
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $image = $request->file('inputImage');
        $imageId = $this->saveImageAndGetId($image, $user, 'images/avatars/');

        DB::table('users')->where('id', $user->id)->update(['image_id' => $imageId]);

        return response(status: 200);
    }

    public function modifyStoreAvatar(Request $request) : Response
    {
        $request->validate([
            'inputImage' => 'image',
        ]);

        $userAndErrorPage = $this->getUserAndErrorResponse($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        $store = DB::table('stores')->where('seller_id', $user->id)->first();
        if (!$store)
            return response()->json(['message' => 'Магазин не найден!'], 404);

        $image = $request->file('inputImage');
        $imageId = $imageId = $this->saveImageAndGetId($image, $user, 'images/avatars/store/');

        DB::table('stores')->where('id', $store->id)->update(['image_id' => $imageId]);

        return response(status: 200);
    }

    public function modifyStoreName(Request $request)
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $store = DB::table('stores')->where('seller_id', $user->id)->first();
        if (!$store)
            return response()->json(['message' => 'Магазин не найден!'], 404);

        $newName = $request->input('inputName');

        DB::table('stores')->where('id', $store->id)->update(['name' => $newName]);

        return response(status: 200);
    }

    public function addProduct(Request $request): Response
    {
        $request->validate([
            'inputImage' => 'image',
        ]);

        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $store = DB::table('stores')->where('seller_id', $user->id)->first();
        if (!$store) {
            return response()->json(['message' => 'У вас нет магазина!'], 404);
        }

        $description = $request->input('inputDescription');
        $name = $request->input('inputName');
        $price = $request->input('inputPrice');
        $count = $request->input('inputCount');
        $categoryId = $request->input('inputCategoryId');

        $productId = DB::table('products')->insertGetId([
            'name' => $name,
            'description' => $description,
            'count' => $count,
            'price' => $price,
            'category_id' => $categoryId,
            'store_id' => $store->id,
        ]);


        $image = $request->file('inputImage');

        $imageId = $this->saveImageAndGetId($image, $user, 'images/products/');

        DB::table('product_images')->insert([
            'product_id' => $productId,
            'image_id' => $imageId
        ]);


        $properties = [];
        foreach ($request->all() as $key => $value)
            if (str_contains($key, "inputProperty"))
                $properties[] = ['id' => explode('_', $key)[1], 'value' => $value];

        $updateData = [];
        foreach ($properties as $property) {
            $updateData[] = [
                'product_id' => $productId,
                'property_id' => $property['id'],
                'value' => $property['value']
            ];
        }
        DB::table('product_properties')->insert($updateData);

        return response(status: 200);
    }

    public function addComment(Request $request): Response
    {
        $request->validate([
            'inputElevation' => 'integer|min:1|max:5',
            'inputImage' => 'image'
        ]);

        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $productId = $request->input('productId');

        $image = $request->file('inputImage');
        if ($image)
            $imageId = $this->saveImageAndGetId($image, $user, 'images/reviews/');
        else
            $imageId = null;

        DB::table('reviews')->insert([
            'product_id' => $productId,
            'author_id' => $user->id,
            'content' => $request->input('inputContent'),
            'elevation' => $request->input('inputElevation'),
            'image_id' => $imageId,
        ]);

        return response(status: 200);
    }

    public function addCart(Request $request): Response
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $productId = $request->input('productId');

        $otherCart = DB::table('product_carts')
            ->where(['user_id' => $user->id, 'product_id' => $productId])
            ->first();
        if ($otherCart)
            return response()->json(['message' => 'Товар уже в корзине!'], 409);

        DB::table('product_carts')->insert(['user_id' => $user->id, 'product_id' => $productId]);

        return response(status: 200);
    }

    public function deleteCart(Request $request): Response
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $productId = $request->input('productId');

        DB::table('product_carts')->where('product_id', $productId)->delete();

        return response(status: 200);
    }

    public function addOrders(Request $request): Response
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $productsIds = $request->input('productsIds');
        $products = DB::table('products')->whereIn('id', $productsIds)->get();

        $data = [];
        $totalPrice = 0;
        foreach ($products as $product) {
            $data[] = [
                'product_id' => $product->id,
                'customer_id' => $user->id,
                'note' => null,
                'price' => $product->price,
                'state_id' => 2
            ];
            $totalPrice += $product->price;
        }

        try {
            DB::table('orders')->insert($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Не удалось произвести заказ: внутренняя ошибка!'], 500);
        }

        $this->sendEmail($user->email, "Ваш заказ " . count($products) .
            " товара(ов) на сумму " . $totalPrice . " рублей выполнен." . "Ожидайте доставки.");

        $this->sendEmail($user->email, $user->first_name . " " . $user->last_name . ' оформил заказ ' .
            count($products) . " товара(ов) на сумму " . $totalPrice . " рублей.");

        return response(status: 200);
    }

    public function cancelOrder(Request $request): Response
    {
        $userAndErrorResponse = $this->getUserAndErrorResponse($request);
        if ($userAndErrorResponse[1])
            return $userAndErrorResponse[1];
        else
            $user = $userAndErrorResponse[0];

        $productId = $request->input('productId');

        DB::table('orders')
            ->where([['customer_id', $user->id], ['product_id', $productId]])
            ->update(['state_id' => 6]);

        return response(status: 200);
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
