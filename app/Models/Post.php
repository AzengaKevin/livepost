<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body'
    ];

    protected $casts = [
        'body' => 'array'
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $appends = [
        'uppercase_title'
    ];

    /**
     * Post uppercase title accessor
     */
    public function uppercaseTitle(): Attribute
    {
        return Attribute::get(fn ($value, $attributes) => str()->upper($attributes['title']));
    }

    /**
     * Post - Comment relationship (1:M)
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Post - User relationship (M:1)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Post - User relationship (M:N)
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
