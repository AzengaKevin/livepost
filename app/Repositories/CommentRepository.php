<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class CommentRepository extends BaseRepository
{
    
	/**
	 * @param array $attributes
	 * @return mixed
	 */
	public function create(array $attributes) {

		return Comment::query()->create($attributes);
		
	}
	
	/**
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @param array $attributes
	 * @return mixed
	 */
	public function update(Model $model, array $attributes) {
        return $model->update(['body' => data_get($attributes, 'body', $model->body)]);
	}
	
	/**
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @return mixed
	 */
	public function delete(Model $model) {

        return $model->delete();
        
	}
}
