<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'register_date',
        'stock',
        'description',
        'image',
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
