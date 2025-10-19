<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ListOrderRequest;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\VerifyOrderRequest;
use App\Http\Requests\Order\CancelOrderRequest;
use App\Http\Requests\Order\ModifyOrderRequest;
use App\Models\Order;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(ListOrderRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Order::class);
        return $this->orderService->getOrders($request->all(), $request->user());
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $this->authorize('create', Order::class);
        return $this->orderService->createOrder($request->all(), $request->user());
    }

    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);
        return $this->orderService->getOrderDetails($order);
    }

    public function verify(VerifyOrderRequest $request, Order $order): JsonResponse
    {
        $this->authorize('verify', $order);
        return $this->orderService->verifyOrder($request->all(), $order);
    }

    public function resendVerification(Order $order): JsonResponse
    {
        $this->authorize('verify', $order);
        return $this->orderService->resendVerificationCode($order);
    }

    public function cancel(CancelOrderRequest $request, Order $order): JsonResponse
    {
        $this->authorize('cancel', $order);
        return $this->orderService->cancelOrder($request->all(), $order);
    }

    public function approve(Order $order): JsonResponse
    {
        $this->authorize('approve', $order);
        return $this->orderService->changeOrderStatus($order, Order::STATUS_VERIFIED, request()->user());
    }

    public function process(Order $order): JsonResponse
    {
        $this->authorize('process', $order);
        return $this->orderService->changeOrderStatus($order, Order::STATUS_UNDER_PROCESSING, request()->user());
    }

    public function complete(Order $order): JsonResponse
    {
        $this->authorize('complete', $order);
        return $this->orderService->changeOrderStatus($order, Order::STATUS_COMPLETED, request()->user());
    }

    public function modify(ModifyOrderRequest $request, Order $order): JsonResponse
    {
        $this->authorize('modify', $order);
        return $this->orderService->modifyOrder($request->validated(), $order, $request->user());
    }
}
