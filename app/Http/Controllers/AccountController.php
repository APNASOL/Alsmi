<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AccountController extends Controller
{ 
    public function index()
    {
        return Inertia::render('Banks/Account');
    }
    public function fetch()
    {
        $accounts_details = BankAccount::all();
        foreach($accounts_details as $single_record)
        {
            if($single_record->process_type == 'Bank')
            {
                $bank_record = Bank::where('id',$single_record->consumer_id)->first();
                $single_record->consumer_name = $bank_record->name;
                $single_record->bank_title = $bank_record->title;
                $single_record->bank_account = $bank_record->account;
            }

        }
        return $accounts_details;
    }
    
}
