<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\IssueCategory;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IssueControllerTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUser(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    private function actingAsAdmin(): void
    {
        $user = User::factory()->create();
        $user->admin = true;
        $this->actingAs($user);
    }

    public function test_can_create_issue()
    {
        $this->actingAsUser();
        $category = IssueCategory::factory()->create();
        $data = [
            'topic' => 'topic',
            'description' => 'desc',
            'category_id' => $category->id,
        ];
        $response = $this->post('/api/issues', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('issues', $data);
    }

    public function test_can_get_list_of_issues()
    {
        $this->actingAsAdmin();
        $category = IssueCategory::factory()->create();
        $issue = Issue::factory()->for($category)->create();
        $closed = Issue::factory()->for($category)->create(['status' => Status::CLOSED]);

        $response = $this->get('/api/issues');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $issue->id,
            'topic' => $issue->topic,
            'description' => $issue->description,
        ]);
        $response->assertJsonMissing([
            'id' => $closed->id,
        ]);
    }

    public function test_can_get_filter_list_of_issues()
    {
        $this->actingAsAdmin();
        $category = IssueCategory::factory()->create();
        $issue = Issue::factory()->for($category)->create();
        $closed = Issue::factory()->for($category)->create(['status' => Status::CLOSED]);

        $response = $this->get('/api/issues?status=closed');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $closed->id,
            'topic' => $closed->topic,
            'description' => $closed->description,
        ]);
        $response->assertJsonMissing([
            'id' => $issue->id,
        ]);
    }

    public function test_can_update_issue()
    {
        $this->actingAsAdmin();
        $category = IssueCategory::factory()->create();
        $issue = Issue::factory()->for($category)->create();

        $response = $this->put('/api/issues/'.$issue->id, [
            'status' => Status::CLOSED->value,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $issue->id,
            'status' => Status::CLOSED->value,
        ]);
    }
}
