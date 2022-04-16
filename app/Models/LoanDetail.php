<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDetail extends Model
{
    use HasFactory;
    public $timestamps  =   false;

    protected $fillable = [
        'loan_id',
        'collection_id',
        'quantity',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
