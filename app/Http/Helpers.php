<?php
use Illuminate\Support\Facades\Storage;

 
 
// function get_storage_url($path)
// {
//     // Get base URL from config
//     $baseUrl = config('app.url'); // This is your domain, e.g., 'http://alsmi.test'

//     // Get the relative URL from storage
//     $relativeUrl = Storage::url($path); // This will return something like '/storage/TransactionReceipts/...'

//     // Combine the base URL with the relative URL
//     return $baseUrl . $relativeUrl;
// }


function get_storage_url($path)
{
    return Storage::url($path);
}

function american_date($date){
    if ($date) {
        return Carbon::parse($date)->format('m-d-Y');
    }
    return '';
}