<?php
use Illuminate\Support\Facades\Storage;

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