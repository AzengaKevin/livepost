<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{

	/**
	 * @param array $attributes
	 * @return mixed
	 */
	public function create(array $attributes)
	{

		return User::query()->create($attributes);
	}

	/**
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @param array $attributes
	 * @return mixed
	 */
	public function update(\Illuminate\Database\Eloquent\Model $model, array $attributes)
	{
		return $model->update([
			'name' => data_get($attributes, 'name', $model->name),
			'email' => data_get($attributes, 'email', $model->email),
			'password' => data_get($attributes, 'password', $model->name)
		]);
	}

	/**
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @return mixed
	 */
	public function delete(\Illuminate\Database\Eloquent\Model $model)
	{
		return $model->delete();
	}
}