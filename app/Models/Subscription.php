<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $guarded=[];


    public function getCurrentPeriodStartFormattedAttribute()
    {
       return Carbon::parse($this->current_period_start)->format( 'd  M Y');
    }

    public function getCurrentPeriodEndFormattedAttribute()
    {
       return Carbon::parse($this->current_period_end)->format( 'd  M Y');
    }

    public function scopeActive($q)
    {
        return $q->where('status','active');
    }


    public function scopeNotActive($q)
    {
        return $q->where('status','=','canceled');
    }


    public function scopePending($q)
    {
        return $q->where('status','=','pending');
    }


    public function scopePaused($q)
    {
        return $q->where('status','=','paused');
    }


    public function Plan(){
        return $this->belongsTo(Plan::class);
        }



    public function User(){
        return $this->belongsTo(User::class);
        }


        public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}