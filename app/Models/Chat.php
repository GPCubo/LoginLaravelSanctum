<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable =[
        'people'
    ];
    use HasFactory;
    public function messages(){
        return $this->hasMany('App\Models\Message');
    }
    public function users(){
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }
}
