<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class)->withDefault([
            'address' => 'Not enterd',
        ]);
    }

    public function ratings(){
        return $this->morphMany(Rating::class, 'rateable');
    }
}