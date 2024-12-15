<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PartnerController extends Controller
{
    // Display the partner index view
    public function index()
    {
        return Inertia::render('Partner/Index');
    }

    // Fetch all partners with transformed status
    public function partners()
    {
        $partners = Partner::get();
        foreach ($partners as $partner) {

            $partner_detail = PartnerDetail::where('partner_id',$partner->id)->first();
            if($partner_detail->credit > 0)
            { 
                $partner->old_amount = -$partner_detail->credit;
            } 
            if($partner_detail->debit)
            {
                $partner->old_amount = $partner_detail->debit;
            }  
            $partner->status = $partner->status == 1 ? true : false;

            
            $partner->status = $partner->status == 1 ? 'Active' : 'Inactive';
        }
        return $partners;
    }

    // Store or update a partner
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'address' => 'required|string',
            'old_amount' => 'required',
            'status' => 'required',
        ]);

        // Check if updating or creating a new partner
        if ($request->id) {
            $partner = Partner::findOrFail($request->id);
            $partner_detail = PartnerDetail::where('partner_id', $partner->id)->first();

        } else {
            $partner = new Partner;
            $partner->id = Str::orderedUuid();
            $partner_detail = new PartnerDetail;
            $partner_detail->id = Str::orderedUuid();
        }
        $partner->name = $request->name;
        $partner->contact = $request->contact;
        $partner->address = $request->address;

        $partner->status = $request->status === "true" ? 1 : 0;
        $partner->save();
        // partner details
        $partner_detail->partner_id = $partner->id;
        $partner_detail->transaction_id = $partner->id;
        $partner_detail->type = "Old Amount";
        $partner_detail->date = date('Y-m-d');
        if ($request->old_amount) {

            if ($request->old_amount < 0) {
                $partner_detail->credit = abs($request->old_amount);
                $partner_detail->debit = 0;
            } else {
                $partner_detail->credit = 0;
                $partner_detail->debit = $request->old_amount;
            }
        }
        $partner_detail->save();

        return 'success';
    }

    // Display a specific partner with status as a boolean
    public function show($id)
    {
        $partner = Partner::select('id', 'name', 'contact', 'address', 'status', 'created_at', 'updated_at')
            ->findOrFail($id);

            $partner_detail = PartnerDetail::where('partner_id',$partner->id)->first();
            if($partner_detail->credit > 0)
            { 
                $partner->old_amount = -$partner_detail->credit;
            } 
            if($partner_detail->debit)
            {
                $partner->old_amount = $partner_detail->debit;
            }  
            $partner->status = $partner->status == 1 ? true : false;

            return $partner;
    }

    // Delete a specific partner
    public function delete($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return 'success';
    }
    public function pluck()
    {
        $partners = Partner::pluck('name', 'id');
        return $partners;
    }

    public function details($partner_id)
    {
        return Inertia::render('Partner/Details', ['partnerId' => $partner_id]);
    }

    public function partner_details_fetch($partner_id)
    {

        $partner = Partner::where('id', $partner_id)->first();
        $details = PartnerDetail::where('partner_id', $partner_id)->get();

        return ['partner' => $partner, 'details' => $details];

    }
}
