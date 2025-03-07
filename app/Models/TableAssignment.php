<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableAssignment extends Model
{
    use HasFactory;

    protected $primaryKey = 'assignment_id';
    protected $fillable = ['table_id', 'reservation_id'];

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}
