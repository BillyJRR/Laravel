<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['courses_id', 'name', 'description'];

    protected $dates = ['deleted_at'];
    
    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }
}
