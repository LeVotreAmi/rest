<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'countPages', 'preview',
    ];

    public function authors()
    {
        return $this->belongsToMany('App\Author');
    }
}
