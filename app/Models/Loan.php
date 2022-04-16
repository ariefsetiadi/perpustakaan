<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'officer_id',
        'member_id',
        'loan_date',
        'return_date',
        'status',
    ];

    public function officer()
    {
        return $this->belongsTo(User::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loanDetail()
    {
        return $this->hasMany(LoanDetail::class);
    }
}
