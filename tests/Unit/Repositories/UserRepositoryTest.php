<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->app->make(UserRepository::class);

    }

    public function test_create_method_works_properly()
    {
        $payload = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(8)
        ];

        $result = $this->userRepository->create($payload);

        $this->assertNotNull($result, "The user was not even create");

        $this->assertEquals($payload['name'], $result->name, "The created user name do not match");

        $this->assertEquals($payload['email'], $result->email, "The created user email do not match");

        $this->assertTrue(Hash::check($payload['password'], $result->password), "The password was not stored properly");

    }

    public function test_create_method_throws_exception_when_required_fields_are_missing()
    {

        $payload = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(8)
        ];

        $fields = collect(array_keys($payload));

        $fields->each(function ($field) use ($payload) {

            $this->expectException(QueryException::class);

            $this->userRepository->create([...$payload, $field => null]);

        });
        
    }

    public function test_update_method_works_properly_for_all_fields()
    {
        /** @var User */
        $user = User::factory()->create();

        $payload = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(8)
        ];

        $result = $this->userRepository->update($user, $payload);

        $this->assertTrue($result, "The user was not even updated successfully");

        $this->assertEquals($payload['name'], $user->fresh()->name, "The updated user name do not match");

        $this->assertEquals($payload['email'], $user->fresh()->email, "The updated user email do not match");

        $this->assertTrue(Hash::check($payload['password'], $user->fresh()->password), "The password was not updated properly");

    }

    public function test_update_method_works_properly_for_individual_required_fields()
    {
        /** @var User */
        $user = User::factory()->create();

        $payload = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => fake()->password(8)
        ];

        $fields = collect(array_keys($payload));

        $fields->each(function ($field) use ($user, $payload) {

            $this->userRepository->update($user, [$field => $payload[$field]]);

            if ($field == 'password') {

                $this->assertTrue(Hash::check($payload[$field], $user->fresh()->password), "The password was not individually updated properly");

            } else {

                $this->assertEquals($payload[$field], $user->$field, "The field, {$field} was not individually updated well");
            }

        });

    }

    public function test_delete_method_works_properly()
    {
        /** @var User */
        $user = User::factory()->create();

        $result = $this->userRepository->delete($user);

        $this->assertTrue($result, "The user was not deleted");

        $this->assertModelMissing($user);

    }
}