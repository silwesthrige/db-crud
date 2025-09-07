<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    
    // Specify the correct table name from your SQL file
    protected $table = 'event1';
    
    protected $fillable = [
        'name',
        'description', 
        'priority',
        'event_date'
    ];
    
    protected $dates = ['deleted_at'];
    
    public $timestamps = true;
}