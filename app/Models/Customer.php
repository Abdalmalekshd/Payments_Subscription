<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id',
        'business_id',
        'phone',
        'password',
        'address',
        'country',
        'currency',
    ];



    public function users(){
        return $this->hasOne(User::class);
        }

}