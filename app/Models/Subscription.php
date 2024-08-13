<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public $guarded=[];


    public function Plan(){
        return $this->belongsTo(Plan::class);
        }



    public function User(){
        return $this->belongsTo(User::class);
        }

}
