<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        "user_id",
        "type",
        "paid",
        "payment_date",
        "value"
    ];

    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
