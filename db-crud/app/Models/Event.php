<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    
    // Specify the correct table name from your SQL file
    protected $table = 'event1';
    
    protected $fillable = [
        'name',
        'description', 
        'priority',
        'event_date'
    ];
    
    public $timestamps = true;
}