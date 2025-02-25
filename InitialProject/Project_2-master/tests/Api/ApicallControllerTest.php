<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Paper;
use App\Models\SourceData;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApicallControllerTest extends TestCase
{
    use RefreshDatabase; // คืนค่าฐานข้อมูลหลังจากทดสอบแต่ละครั้ง

    protected $validUser;
    protected $encryptedId;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock User
        $this->validUser = User::factory()->create();
        $this->encryptedId = encrypt($this->validUser->id);
    }

    /** @test */
    public function it_should_return_scopus_data_and_store_in_database()  // TC001
    {
        Http::fake([
            'https://api.elsevier.com/*' => Http::response([
                'search-results' => [
                    'entry' => [
                        [
                            'dc:title' => 'Sample Paper',
                            'dc:identifier' => '123456789',
                            'prism:doi' => '10.1016/example.doi',
                            'prism:coverDate' => '2024-01-01',
                            'author' => [['authname' => 'John Doe']],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get("/scopuscall/create/{$this->encryptedId}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('paper', [
            'title' => 'Sample Paper',
            'doi'   => '10.1016/example.doi',
        ]);

        $this->assertDatabaseHas('author', [
            'name' => 'John Doe'
        ]);
    }

    /** @test */
    public function it_should_handle_invalid_encrypted_id() // TC010
    {
        $invalidId = 'invalid_encrypted_id';

        $response = $this->get("/scopuscall/create/{$invalidId}");

        $response->assertStatus(400);
        $response->assertJson([
            'error' => 'Invalid encrypted ID.',
        ]);
    }

    /** @test */
    public function it_should_return_user_not_found_error() // TC011
    {
        $nonExistingId = encrypt(9999); // ID ที่ไม่มีในฐานข้อมูล

        $response = $this->get("/scopuscall/create/{$nonExistingId}");

        $response->assertStatus(404);
        $response->assertJson([
            'error' => 'User not found.',
        ]);
    }

    /** @test */
    public function it_should_handle_api_connection_failure() // TC012
    {
        Http::fake([
            'https://api.elsevier.com/*' => Http::response(null, 500),
        ]);

        $response = $this->get("/scopuscall/create/{$this->encryptedId}");

        $response->assertStatus(500);
        $response->assertJson([
            'error' => 'Failed to connect to Scopus API.',
        ]);
    }

    /** @test */
    public function it_should_handle_unauthorized_api_key() // TC013
    {
        Http::fake([
            'https://api.elsevier.com/*' => Http::response(['error' => 'Unauthorized'], 401),
        ]);

        $response = $this->get("/scopuscall/create/{$this->encryptedId}");

        $response->assertStatus(401);
        $response->assertJson([
            'error' => 'Unauthorized access to Scopus API.',
        ]);
    }

    /** @test */
    public function it_should_skip_duplicate_paper_entries() // TC015
    {
        Paper::create([
            'title' => 'Sample Paper',
            'doi'   => '10.1016/example.doi',
        ]);

        Http::fake([
            'https://api.elsevier.com/*' => Http::response([
                'search-results' => [
                    'entry' => [
                        [
                            'dc:title' => 'Sample Paper',
                            'prism:doi' => '10.1016/example.doi',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get("/scopuscall/create/{$this->encryptedId}");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Duplicate Entry Skipped',
        ]);
    }

    /** @test */
    public function it_should_handle_incomplete_response_data() // TC016
    {
        Http::fake([
            'https://api.elsevier.com/*' => Http::response([
                'search-results' => [
                    'entry' => [
                        [
                            'dc:title' => 'Incomplete Paper', 
                            // ไม่มี DOI หรือ author
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get("/scopuscall/create/{$this->encryptedId}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('paper', [
            'title' => 'Incomplete Paper',
        ]);
    }

    /** @test */
    public function it_should_handle_paper_with_no_authors() // TC017
    {
        Http::fake([
            'https://api.elsevier.com/*' => Http::response([
                'search-results' => [
                    'entry' => [
                        [
                            'dc:title' => 'Paper Without Author',
                            'prism:doi' => '10.1016/no-author',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get("/scopuscall/create/{$this->encryptedId}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('paper', [
            'title' => 'Paper Without Author',
        ]);
    }

    /** @test */
    public function it_should_handle_timeout_from_scopus_api() // TC023
    {
        Http::fake([
            'https://api.elsevier.com/*' => Http::response([], 504), // Gateway Timeout
        ]);

        $response = $this->get("/scopuscall/create/{$this->encryptedId}");

        $response->assertStatus(504);
        $response->assertJson([
            'error' => 'Request to Scopus API timed out.',
        ]);
    }
}