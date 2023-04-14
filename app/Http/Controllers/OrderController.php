<?php

namespace App\Http\Controllers;

use App\Traits\EmailTrait;
use App\Traits\ErrorsTrait;
use App\Traits\ImageTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use ErrorsTrait, ImageTrait, EmailTrait;

    public function getOrdersPage(Request $request): Response
    {
        $userAndErrorPage = $this->getUserAndErrorPage($request);
        if ($userAndErrorPage[1])
            return $userAndErrorPage[1];
        else
            $user = $userAndErrorPage[0];

        $orders = DB::table('orders')->where('customer_id', $user->id)->get();
        $productsIds = [];

        foreach ($orders as $order)
            $productsIds[] = $order->product_id;

        $products = DB::table('products')->whereIn('id', $productsIds)->get();
        $productsImagesPaths = $this->getProductsImagesPaths($productsIds);

        $statesIds = [];
        foreach ($orders as $order)
            $statesIds[] = $order->state_id;
        $states = [];
        foreach ($statesIds as $stateId) {
            $states[] = DB::table('order_states')->find($stateId);
        }

        return Response(view('orders', [
            'products' => $products,
            'productsImagesPaths' => $productsImagesPaths,
            'states' => $states,
            'orders' => $orders
        ]));
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
}
