<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_code',
        'fullname',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'address',
        'phone',
        'image',
    ];

    public function loan()
    {
        return $this->hasMany(Loan::class);
    }
}
