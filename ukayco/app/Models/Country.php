<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'country'; // Set the correct table name

    protected $fillable = ['name', 'code']; // Fillable attributes

    // Other model methods, relationships, etc.
}
