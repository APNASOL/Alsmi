<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CustomerController extends Controller
{
    // Display the customer index view
    public function index()
    {
        return Inertia::render('Customer/Index');
    }

    // Fetch all customers with transformed status
    public function customers()
    {
        $customers = Customer::get();
        foreach ($customers as $customer) {
            $customer->status = $customer->status == 1 ? 'Active' : 'Inactive';
        }
        return $customers;
    }

    // Store or update a customer
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'address' => 'required|string',
            'old_amount' => 'required',
            'status' => 'required',
        ]);

        // Check if updating or creating a new customer
        if ($request->id) {
            $customer = Customer::findOrFail($request->id);
            $customer_detail = CustomerDetail::where('customer_id', $customer->id)->first();

        } else {
            $customer = new Customer;
            $customer->id = Str::orderedUuid();
            $customer_detail = new CustomerDetail;
            $customer_detail->id = Str::orderedUuid();
        }
        $customer->name = $request->name;
        $customer->contact = $request->contact;
        $customer->address = $request->address;

        $customer->status = $request->status === "true" ? 1 : 0;
        $customer->save();
        // customer details
        $customer_detail->customer_id = $customer->id; 
        $customer_detail->transaction_id = $customer->id; 
        $customer_detail->type = "Old Amount";
        $customer_detail->date = date('Y-m-d');
 
        if ($request->old_amount < 0) {
            $customer_detail->credit = abs($request->old_amount); 
            $customer_detail->debit = 0; 
        } else {
            $customer_detail->credit = 0; 
            $customer_detail->debit = $request->old_amount; 
        }
        $customer_detail->save();

        return 'success';
    }

    // Display a specific customer with status as a boolean
    public function show($id)
    {
        $customer = Customer::select('id', 'name', 'contact', 'address', 'status', 'created_at', 'updated_at')
            ->findOrFail($id);

        $customer->status = $customer->status == 1 ? true : false;

        return $customer;
    }

    // Delete a specific customer
    public function delete($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return 'success';
    }
    public function pluck()
    {
        $customers = Customer::pluck('name', 'id'); 
        return $customers;
    }

    public function details($customer_id)
    {
        return Inertia::render('Customer/Details', ['customerId' => $customer_id]);
    }

    public function customer_details_fetch($customer_id)
    {

        $customer = Customer::where('id', $customer_id)->first();
        $details = CustomerDetail::where('customer_id', $customer_id)->get();

        return ['customer' => $customer, 'details' => $details];

    }
}
