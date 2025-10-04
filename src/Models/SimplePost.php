<?php

namespace Ssh521\SimplePosts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimplePost extends Model
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

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('simple-posts.table_name', 'simple_posts');
    }
}