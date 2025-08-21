<?php

namespace Ssh521\SimplePosts\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->setTable(config('simple-posts.table_name', 'simple_posts'));
    }
}