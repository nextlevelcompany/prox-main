<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Order\Http\Resources\OrderCollection;
use Modules\Order\Models\Order;

class OrderController extends Controller
{
    public function records(Request $request)
    {
        $records = Order::query()
            ->latest();

        return new OrderCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function store(Request $request)
    {
        $order = Order::query()
            ->findOrFail($request->id);
        $order->status_order_id = $request->status_order_id;
        $order->save();

        return [
            'success' => true,
            'message' => 'Orden actualizada con éxito'
        ];
    }

}
