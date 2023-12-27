<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Post extends Model
{
    use HasFactory;


    public $fillable = [
        'title',
        'description',
        'image'
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class,"user_id");
    }

      public function image(): MorphOne
    {
        return $this->morphOne(File::class,"post");
    }
}
