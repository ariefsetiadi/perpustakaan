<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'register_date',
        'stock',
        'description',
        'image',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function loanDetail()
    {
        return $this->hasMany(LoanDetail::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
