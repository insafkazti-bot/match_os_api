<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Matches;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatchApiTest extends TestCase
{
    use RefreshDatabase;

    private function adminToken(): string
    {
        $admin = Admin::create([
            'first_name' => 'Admin',
            'last_name' => 'Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('secret123'),
            'avatar_url' => null,
        ]);

        return $admin->createToken('test')->plainTextToken;
    }

    public function test_index_requires_authentication(): void
    {
        $this->getJson('/api/matches')->assertStatus(401);
    }

    public function test_admin_can_list_create_and_delete_matches(): void
    {
        $token = $this->adminToken();

        $this->getJson('/api/matches', [
            'Authorization' => 'Bearer '.$token,
        ])->assertOk()->assertJsonCount(0, 'data');

        $payload = [
            'title' => 'Finale',
            'team_a_name' => 'A',
            'team_b_name' => 'B',
            'match_date' => '2026-05-04T20:00',
            'location' => 'Paris',
            'status' => 'planifie',
        ];

        $create = $this->postJson('/api/matches', $payload, [
            'Authorization' => 'Bearer '.$token,
        ]);

        $create->assertCreated();
        $id = $create->json('data.id');
        $this->assertNotNull($id);

        $this->getJson('/api/matches', [
            'Authorization' => 'Bearer '.$token,
        ])->assertOk()->assertJsonCount(1, 'data');

        $this->getJson('/api/matches/'.$id, [
            'Authorization' => 'Bearer '.$token,
        ])->assertOk()->assertJsonPath('data.title', 'Finale');

        $this->putJson(
            '/api/matches/'.$id,
            [
                'title' => 'Finale',
                'team_a_name' => 'A',
                'team_b_name' => 'B',
                'match_date' => '2026-05-04T20:00',
                'location' => 'Paris',
                'status' => 'en_cours',
            ],
            ['Authorization' => 'Bearer '.$token],
        )->assertOk()->assertJsonPath('data.status', 'en_cours');

        $this->deleteJson('/api/matches/'.$id, [], [
            'Authorization' => 'Bearer '.$token,
        ])->assertOk();

        $this->assertSame(0, Matches::query()->count());
    }
}
