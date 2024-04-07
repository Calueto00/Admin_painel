<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable =[
        'thumbnail',
        'title',
        'color',
        'slug',
        'category_id',
        'content',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


}
