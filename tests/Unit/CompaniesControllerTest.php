<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Companies;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CompaniesControllerTest extends TestCase
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

        Companies::factory()->count(10)->create();
        $response = $this->getJson('/api/companies');
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
            'name' => 'Tesla',
            'image_path' => 'path/to/image.jpg',
            'location' => 'California',
            'industry' => 'AI',
            'user_id' => $user->id
        ];

        // Make the authenticated POST request to the API endpoint
        $response = $this->postJson('/api/companies', $requestData);

        // Assertions
        $response->assertStatus(201); // Assert the response status code is 201 Created
        $response->assertJsonFragment([
            'name' => 'Tesla',
            'location' => 'California',
            'industry' => 'AI'
        ]);

        // Further assertion to confirm the data was saved in the database
        $this->assertDatabaseHas('companies', [
            'name' => 'Tesla',
            'location' => 'California',
            'industry' => 'AI'
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

        $company = Companies::factory()->create();

        $response = $this->getJson("/api/companies/{$company->id}");


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

        // Create a company, associating it with the authenticated user
        $company = Companies::factory()->create([
            'user_id' => $user->id, // Ensure the company is associated with our user
        ]);

        // Prepare the data for updating the company
        $updateData = [
            'name' => 'Updated Company',
            'image_path' => '/path/to/image.jpg',
            'location' => 'Silicon Valley',
            'industry' => 'Technology',
            'user_id' => $user->id, // Maintaining the same user, but it's good practice to ensure consistency
        ];

        // Make the PUT request to update the company
        $response = $this->putJson("/api/companies/{$company->id}", $updateData);

        // Assertions
        $response->assertOk(); // Assert the response status code is 200 OK
        $response->assertJsonFragment([
            'name' => 'Updated Company',
            'location' => 'Silicon Valley',
            'industry' => 'Technology',
        ]);

        // Additional assertion to confirm the data was updated in the database
        $this->assertDatabaseHas('companies', [
            'id' => $company->id, // Ensure we're looking at the correct company
            'name' => 'Updated Company',
            'location' => 'Silicon Valley',
            'industry' => 'Technology',
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

        // Create a company, associating it with the authenticated user
        // This assumes your Companies model has a 'user_id' field for ownership
        $company = Companies::factory()->create([
            'user_id' => $user->id,
        ]);

        // Make the DELETE request to destroy the company
        $response = $this->deleteJson("/api/companies/{$company->id}");

        // Assertions
        $response->assertOk();
        $response->assertJson(['message' => 'Data deleted successfully']);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
