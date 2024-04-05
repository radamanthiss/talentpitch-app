<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Companies;
use Tests\TestCase;

class CompaniesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        // Assuming a Company factory

        Companies::factory()->count(10)->create();
        $response = $this->getJson('/api/companies');
        // Additional assertions as needed
        $response->assertOk();
        $response->assertJsonCount(10, 'data');
    }

    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/companies', [
            'name' => 'New Company',
            'image_path' => '/path/to/image.jpg',
            'location' => 'Silicon Valley',
            'industry' => 'Technology',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('name', 'New Company');
        // Additional assertions as needed
    }
    // Add  test methods show
    public function testShow()
    {
        $company = Companies::factory()->create();

        $response = $this->getJson("/api/companies/{$company->id}");

        $response->assertOk();
        $response->assertJsonFragment(['id' => $company->id]);
    
    }

    // Add  test methods update
    public function testUpdate()
    {
        $company = Companies::factory()->create();

        $response = $this->putJson("/api/companies/{$company->id}", [
            'name' => 'Updated Company',
            'image_path' => '/path/to/image.jpg',
            'location' => 'Silicon Valley',
            'industry' => 'Technology',
            'user_id' => $company->user_id,
        ]);

        $response->assertOk();
        $response->assertJsonPath('name', 'Updated Company');
    }

    // Add  test methods destroy
    public function testDestroy()
    {
        $company = Companies::factory()->create();

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertOk();
        $response->assertJson(['message' => 'Company deleted successfully']);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);

    }
}
