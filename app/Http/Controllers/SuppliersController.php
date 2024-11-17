<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SuppliersController extends Controller
{
    public function index()
    {
        return Inertia::render('Supplier/Index');
    }

    public function suppliers()
    {
        $suppliers = Supplier::all();
        foreach ($suppliers as $supplier) {
            $supplier->status = $supplier->status == 1 ? 'Active' : 'Inactive';
            
        }
        return $suppliers;
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:254',
            'contact' => 'required|string|max:254',
            'old_amount' => 'required',
            'address' => 'required|string|max:499',
            'status' => 'required|string|in:true,false', // Validate as boolean
        ]);

        $supplier = $request->id ? Supplier::findOrFail($request->id) : new Supplier;
        $supplier->id = $supplier->id ?? Str::orderedUuid();

        $supplier->name = $request->name;
        $supplier->contact = $request->contact;
        $supplier->address = $request->address;
        $customer->old_amount = $request->old_amount;

        // Convert string to boolean
        $supplier->status = $request->status === "true" ? 1 : 0;

        $supplier->save();

        return 'success';
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Convert status to boolean
        $supplier->status = $supplier->status == 1 ? true : false;

        return $supplier;
    }

    public function delete($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return 'success';
    }

    public function pluck()
    {
        $supplier = Supplier::pluck('name', 'id'); 
        return $supplier;
    }

    public function details($supplier_id)
    {
        return Inertia::render('Supplier/Details', ['supplierId' => $supplier_id]);
    }

    public function supplier_details_fetch($supplier_id)
    {  
        $supplier = Supplier::where('id',$supplier_id)->first();
        $details = SupplierDetail::where('supplier_id', $supplier_id)->get();
  
        return ['supplier' => $supplier,'details'=>$details];
 
    }
}
