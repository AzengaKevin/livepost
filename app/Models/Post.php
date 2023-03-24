<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id'
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
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Post - User relationship (M:N)
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
