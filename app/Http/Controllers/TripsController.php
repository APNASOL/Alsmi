<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Order;
use App\Models\Customer;
use App\Models\CustomerDetail;
use App\Models\Supplier;
use App\Models\SupplierDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TripsController extends Controller
{
    // Display the trip index view
    public function index()
    {
        return Inertia::render('Trip/Index');
    }

    // Fetch all trips with transformed status
    public function trips()
    {
        $trips = Trip::get();
        foreach ($trips as $trip) {
            $trip->status = $trip->status == 1 ? 'Active' : 'Inactive';
            $order = Order::where('id', $trip->order_id)->first();
            if($order)
            {
                $customer = Customer::where('id',$order->customer_id)->first();   
                $trip->customer_name = $customer->name;
                $trip->customer_id = $order->customer_id;
                // dd($order->customer_id);
                
            }
            $supplier = Supplier::where('id',$trip->supplier_id)->first();   
            $trip->supplier_name = $supplier->name;
             
        }
        return $trips;
    }

    // Store or update a trip
    public function store(Request $request)
    { 
        $request->validate([
               
            'order_id' => 'required',
            'supplier_id' => 'required',
            'mine_name' => 'required|string|max:255',
            'truck_weight' => 'required|numeric',
            'price_per_ton' => 'required|numeric',
            'truck_price' => 'required|numeric',
            'vehicle' => 'required|string|max:255',
            'mine_expense' => 'required|numeric',
            'transport' => 'required|numeric',
            'tax' => 'required|numeric',
            'total_bill' => 'required|numeric',
            'date' => 'required',
        ]);

        // Check if updating or creating a new trip
          
        if($request->id)
        { 
            $trip = Trip::findOrFail($request->id);
            $customer_details = CustomerDetail::where('transaction_id',$request->id)->where('order_id',$request->order_id)->first();
          
            $supplier_details = SupplierDetail::where('transaction_id',$request->id)->where('order_id',$request->order_id)->first();
        }else
        {
            $trip = new Trip;
            $trip->id = Str::orderedUuid();
            $customer_details = new CustomerDetail;
            $customer_details->id = Str::orderedUuid();
            $supplier_details = new SupplierDetail;
            $supplier_details->id = Str::orderedUuid();
        }
        
        // Handle date fields
        $startDateString = $request->date;
        $startDateString = preg_replace('/\s*\(.*\)$/', '', $startDateString);  // Remove timezone name
        
        $trip->date = Carbon::parse($startDateString)->format('Y-m-d');
        

        // Assign the other fields
        $trip->order_id = $request->order_id;
        $trip->supplier_id = $request->supplier_id;
        $trip->mine_name = $request->mine_name;
        $trip->truck_weight = $request->truck_weight;
        $trip->price_per_ton = $request->price_per_ton;
        $trip->truck_price = $request->truck_price;
        $trip->vehicle = $request->vehicle;
        $trip->mine_expense = $request->mine_expense;
        $trip->transport = $request->transport;
        $trip->tax = $request->tax;
        $trip->total_bill = $request->total_bill;

        // Set status as 1 for "Active" or 0 for "Inactive"
        $trip->status = 1;

        // Calculation ..............................................
        $truck_total_price = $request->truck_price * $request->price_per_ton;
        $mine_expense = $request->mine_expense;
        $tax = $request->tax;
        $transport = $request->transport; 
        // Calculation ..............................................

        // Save the trip
        $trip->save();
        $order_record = Order::where('id',$request->order_id)->first();
         
        // customer record 
        $customer_details->customer_id = $order_record->customer_id;
        $customer_details->transaction_id = $trip->id;
        // $customer_details->order_id = $request->order_id;
        $customer_details->type = "Order";
        $customer_details->date = date('Y-m-d');
        $customer_details->credit = 0;
        
        $customer_details->status = 1;
        $customer_details->debit = $truck_total_price + $mine_expense + $tax + $transport;
        $customer_details->save();
    

        // supplier record 
         
        $supplier_details->supplier_id = $trip->supplier_id;
        $supplier_details->transaction_id = $trip->id;
       
        $supplier_details->type = "Order";
        $supplier_details->date = date('Y-m-d');
        $supplier_details->status = 1;
        $supplier_details->credit = $truck_total_price;
        $supplier_details->debit = 0;
        $supplier_details->save();

        

        return 'success';
    }

    // Display a specific trip with status as a boolean
    public function show($id)
    { 
        $trip = Trip::where('id', $id)->first();
        // Convert status to boolean
        $trip->status = $trip->status == 1 ? true : false;

        return $trip;
    }

    // Delete a specific trip
    public function delete($id)
    {
        $trip = Trip::findOrFail($id);
        
        CustomerDetail::where('transaction_id',$id)->delete();
        SupplierDetail::where('transaction_id',$id)->delete();
           
        $trip->delete();

        return 'success';
    }
}
