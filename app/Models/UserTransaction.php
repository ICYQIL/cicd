<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TransactionItem;

class UserTransaction extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi Laravel
    protected $table = 'transactions';  // Gunakan tabel transactions

    // Tentukan kolom yang bisa diisi
    protected $fillable = [
        'user_id', 
        'transaction_id', 
        'total', 
        'status', 
        'payment_url',
        'invoice_url',
        'expiry_time',
    ];

    // Model UserTransaction
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }
}