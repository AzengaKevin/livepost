<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function testUsersIndexRoute()
    {

        $this->withoutExceptionHandling();

        User::factory()->count($count = 2)->create();

        /** @var User */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('api.v1.users.index'));

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                $count + 1,
                fn (AssertableJson $userAssertableJson) =>
                $userAssertableJson->hasAll(["id", "name", "email", "created_at", "updated_at"])
            )->etc()
        );
    }

    public function testUsersShowRoute()
    {
        $this->withoutExceptionHandling();

        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $currentUser = User::factory()->create();

        $response = $this->actingAs($currentUser)->getJson(route('api.v1.users.show', $user));

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $userAssertableJson) =>
                $userAssertableJson->hasAll(["id", "name", "email", "created_at", "updated_at"])
            )
        );
    }

    public function testUsersStoreRoute()
    {
        $this->withoutExceptionHandling();

        /** @var User */
        $user = User::factory()->create();

        $payload = [
            "name" => fake()->name(),
            "email" => fake()->unique()->safeEmail(),
            "password" => fake()->password(8)
        ];

        $response = $this->actingAs($user)->postJson(route('api.v1.users.store'), $payload);

        $response->assertStatus(201)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $userAssertableJson) =>
                $userAssertableJson->where("name", $payload["name"])->where("email", $payload["email"])->etc()
            )
        );

        /** @var User */
        $user = User::query()->where("email", $payload["email"])->first();

        $this->assertNotNull($user, "The user was not create in the database");

        $this->assertEquals($payload["name"], $user->name, "The created user name do not match");

        $this->assertTrue(Hash::check($payload["password"], $user->password), "The created user password was not store appropriated");
    }

    public function testUsersUpdateRoute()
    {
        $this->withoutExceptionHandling();

        /** @var User */
        $user = User::factory()->create();

        $payload = [
            "name" => fake()->name(),
            "email" => fake()->unique()->safeEmail(),
            "password" => fake()->password(8)
        ];

        /** @var User */
        $currentUser = User::factory()->create();

        $response = $this->actingAs($currentUser)->patchJson(route('api.v1.users.update', $user), $payload);

        $response->assertStatus(200)->assertJson(
            fn (AssertableJson $assertableJson) =>
            $assertableJson->has(
                "data",
                fn (AssertableJson $userAssertableJson) =>
                $userAssertableJson->where("name", $payload["name"])->where("email", $payload["email"])->etc()
            )
        );

        $this->assertEquals($payload["name"], $user->fresh()->name, "The updated user name do not match");

        $this->assertEquals($payload["email"], $user->fresh()->email, "The updated user email do not match");

        $this->assertTrue(Hash::check($payload["password"], $user->fresh()->password), "The updated user password was not done correctly");
    }

    public function testUsersDeleteRoute()
    {
        $this->withoutExceptionHandling();

        /** @var User */
        $user = User::factory()->create();

        /** @var User */
        $currentUser = User::factory()->create();

        $response = $this->actingAs($currentUser)->deleteJson(route('api.v1.users.destroy', $user));

        $response->assertStatus(204);

        $this->assertModelMissing($user);
    }
}
