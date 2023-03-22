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

    public function uppercaseTitle(): Attribute
    {
        return Attribute::get(fn ($value, $attributes) => str()->upper($attributes['title']));
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
