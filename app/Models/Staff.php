<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $primaryKey = 'staff_id'; 
    protected $fillable = ['first_name', 'last_name', 'email', 'phone_number', 'role', 'salary', 'password_hash', 'work_schedule'];
}
