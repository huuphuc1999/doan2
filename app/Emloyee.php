<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emloyee extends Model
{
    protected $fillable = [
        'name', 'email', 'salary', 'qrcode'
    ];
}
