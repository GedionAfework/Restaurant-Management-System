<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'customer_id'; // If using a custom primary key
    protected $fillable = ['first_name', 'last_name', 'email', 'phone_number', 'address', 'preferences', 'date_of_birth', 'password_hash'];
}