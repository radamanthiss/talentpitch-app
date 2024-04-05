<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Programs;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ProgramsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*'] // Permissions or abilities, if necessary
        );

        Programs::factory()->count(10)->create();
        $response = $this->getJson('/api/programs');
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
            'title' => 'New program',
            'description' => 'A detailed description.',
            'start_date' => '2024-04-01', 
            'end_date' =>'2024-07-30',// Assuming 'difficulty' is a string type in your schema
            'user_id' => $user->id,
        ];

        // Make the authenticated POST request to the API endpoint
        $response = $this->postJson('/api/programs', $requestData);

        // Assertions
        $response->assertStatus(201); // Assert the response status code is 201 Created
        $response->assertJsonFragment([
            'title' => 'New program',
            'description' => 'A detailed description.',
            'start_date' => '2024-04-01',
            'end_date' => '2024-07-30'
        ]);

        // Further assertion to confirm the data was saved in the database
        $this->assertDatabaseHas('programs', [
            'title' => 'New program',
            'description' => 'A detailed description.',
            'start_date' => '2024-04-01',
            'end_date' => '2024-07-30'
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

        $program = Programs::factory()->create();

        $response = $this->getJson("/api/programs/{$program->id}");


        $response->assertOk();
        $response->assertJsonFragment(['id' => $program->id]);
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
        $programs = Programs::factory()->create([
            'user_id' => $user->id, // Ensure the program is associated with our user
        ]);

        // Prepare the data for updating the program
        $updateData = [
            'title' => 'Updated program',
            'description' => 'A detailed description.',
            'start_date' => '2024-04-01',
            'end_date' => '2024-07-30',
            'user_id' => $user->id
        ];

        // Make the PUT request to update the program
        $response = $this->putJson("/api/programs/{$programs->id}", $updateData);

        // Assertions
        $response->assertOk(); // Assert the response status code is 200 OK
        $response->assertJsonFragment([
            'title' => 'Updated program',
            'description' => 'A detailed description.',
            'start_date' => '2024-04-01',
            'end_date' => '2024-07-30'
        ]);

        // Additional assertion to confirm the data was updated in the database
        $this->assertDatabaseHas('programs', [
            'id' => $programs->id, // Ensure we're looking at the correct program
            'title' => 'Updated program',
            'description' => 'A detailed description.',
            'start_date' => '2024-04-01',
            'end_date' => '2024-07-30'
        ]);
    }

    // Add  test methods destroy
    public function testDestroy()
    {
        // Create and authenticate a user with Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*'] // Assuming the user has permission to delete programs
        );

        // Create a challenge, associating it with the authenticated user
        // This assumes your programs model has a 'user_id' field for ownership
        $program = Programs::factory()->create([
            'user_id' => $user->id,
        ]);

        // Make the DELETE request to destroy the program
        $response = $this->deleteJson("/api/programs/{$program->id}");

        // Assertions
        $response->assertOk();
        $response->assertJson(['message' => 'Program deleted']);
        $this->assertDatabaseMissing('programs', ['id' => $program->id]);
    }
}
