<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Challenges;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ChallengesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Assuming a Company factory
        // $user = User::factory()->create(); // Create a user for authentication
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'] // Permissions or abilities, if necessary
        );

        Challenges::factory()->count(10)->create();
        $response = $this->getJson('/api/challenges');
        // Additional assertions as needed
        $response->assertOk();
        $response->assertJsonCount(10, 'data');
    }

    public function testStore()
    {
        // Create a new user using the User factory
        $user = User::factory()->create();

        // Authenticate the user using Sanctum
        Sanctum::actingAs(
            $user,
            ['*'] // Permissions or abilities, if necessary. Adjust as needed.
        );

        // Prepare the request data
        $requestData = [
            'title' => 'New Challenge',
            'description' => 'A detailed description.',
            'difficulty' => 'medium', // Assuming 'difficulty' is a string type in your schema
            'user_id' => $user->id,
        ];

        // Make the authenticated POST request to the API endpoint
        $response = $this->postJson('/api/challenges', $requestData);

        // Assertions
        $response->assertStatus(201); // Assert the response status code is 201 Created
        $response->assertJsonFragment([
            'title' => 'Tesla',
            'description' => 'Prueba para crear challenges',
            'difficulty' => 'hard'
        ]);

        // Further assertion to confirm the data was saved in the database
        $this->assertDatabaseHas('challenges', [
            'name' => 'Tesla',
            'description' => 'Prueba para crear challenges',
            'difficulty' => 'hard'
        ]);
    }
    // Add  test methods show
    public function testShow()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'] // Permissions or abilities, if necessary
        );
        $user = User::factory()->create();

        $company = Challenges::factory()->create();

        $response = $this->getJson("/api/challenges/{$company->id}");


        $response->assertOk();
        $response->assertJsonFragment(['id' => $company->id]);
    }

    // Add  test methods update
    public function testUpdate()
    {
        // First, create and authenticate a user
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*'] // Permissions or abilities, if necessary
        );

        // Create a challenges, associating it with the authenticated user
        $challenges = Challenges::factory()->create([
            'user_id' => $user->id, // Ensure the company is associated with our user
        ]);

        // Prepare the data for updating the company
        $updateData = [
            'title' => 'Tesla',
            'description' => 'Prueba para crear challenges',
            'difficulty' => 'hard',
            'user_id' => $user->id
        ];

        // Make the PUT request to update the company
        $response = $this->putJson("/api/challenges/{$challenges->id}", $updateData);

        // Assertions
        $response->assertOk(); // Assert the response status code is 200 OK
        $response->assertJsonFragment([
            'title' => 'Updated Challenge',
            'description' => 'Prueba para crear challenges',
            'difficulty' => 'hard',
        ]);

        // Additional assertion to confirm the data was updated in the database
        $this->assertDatabaseHas('companies', [
            'id' => $challenges->id, // Ensure we're looking at the correct company
            'title' => 'Updated Challenge',
            'location' => 'Prueba para crear challenges',
            'difficulty' => 'hard',
        ]);
    }

    // Add  test methods destroy
    public function testDestroy()
    {
        // Create and authenticate a user with Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*'] // Assuming the user has permission to delete companies
        );

        // Create a challenge, associating it with the authenticated user
        // This assumes your Companies model has a 'user_id' field for ownership
        $challenge = Challenges::factory()->create([
            'user_id' => $user->id,
        ]);

        // Make the DELETE request to destroy the company
        $response = $this->deleteJson("/api/challenges/{$challenge->id}");

        // Assertions
        $response->assertOk();
        $response->assertJson(['message' => 'Data deleted successfully']);
        $this->assertDatabaseMissing('challenges', ['id' => $challenge->id]);
    }
}
