<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OrdersController extends Controller
{
    // Display the order index view
    public function index()
    {
        return Inertia::render('Order/Index');
    }
    public function details($order_id)
    {
        return Inertia::render('Order/Details', ['orderId' => $order_id]);
    }
    public function order_details_fetch($order_id)
    {

        $order = Order::where('id', $order_id)->first();

        $customer = Customer::where('id', $order->customer_id)->first();

        $trips = Trip::where('order_id', $order_id)->get();
        foreach ($trips as $trip) {
            if ($trip) {
                $supplier = Supplier::where('id', $trip->supplier_id)->first();
                $trip->supplier_name = $supplier->name;
            }
        }

        return ['trips' => $trips, 'customer' => $customer,'order'=>$order];
    }

    // Fetch all orders with transformed status
    public function orders()
    {
        $orders = Order::get();
        foreach ($orders as $order) {
            $order->status = $order->status == 1 ? 'Active' : 'Inactive';
            $customer = Customer::where('id', $order->customer_id)->first();
            if ($customer) {
                $order->customer_name = $customer->name;
            }
        }
        return $orders;
    }

    // Store or update a order
    public function store(Request $request)
    {
        $request->validate([

            'customer' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required',
        ]);

        // Check if updating or creating a new order
        $order = $request->id ? Order::findOrFail($request->id) : new Order;
        $order->id = $order->id ?? Str::orderedUuid();

        $order->customer_id = $request->customer;
        $startDateString = $request->start_date;
        $startDateString = preg_replace('/\s*\(.*\)$/', '', $startDateString);
        $endDateString = $request->end_date;
        $endDateString = preg_replace('/\s*\(.*\)$/', '', $endDateString);

        $order->start_date = Carbon::parse($startDateString)->format('Y-m-d');
        $order->end_date = Carbon::parse($endDateString)->format('Y-m-d');

        $order->status = $request->status === "true" ? 1 : 0;

        $order->save();

        return 'success';
    }

    // Display a specific order with status as a boolean
    public function show($id)
    {
        $order = Order::where('id', $id)->first();

        $order->status = $order->status == 1 ? true : false;

        return $order;
    }

    // Delete a specific order
    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return 'success';
    }

    public function pluck()
    {
        $orders_customers = Order::join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.id as order_id', 'customers.name as customer_name')
            ->pluck('customer_name', 'order_id');

        return $orders_customers;
    }

    public function closeOrder($order_id)
    {
        return Inertia::render('Order/Close', ['orderId' => $order_id]);
    }

}
