<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => fake()->paragraphs(),
        ];
    }

    public function randomUser()
    {
        $usersIds = User::query()->get(['id'])->pluck('id')->all();
        
        return $this->state([
            'user_id' => fake()->randomElement($usersIds)
        ]);
    }

    public function randomPost()
    {
        $postsIds = Post::query()->get(['id'])->pluck('id')->all();
        
        return $this->state([
            'post_id' => fake()->randomElement($postsIds)
        ]);
    }
}
