<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_create_a_project_with_multiple_images(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post(route('admin.projects.store'), [
            'title' => 'Mijn nieuwe project',
            'description' => 'Een korte projectbeschrijving.',
            'images' => [
                UploadedFile::fake()->image('project-one.jpg'),
                UploadedFile::fake()->image('project-two.jpg'),
            ],
        ]);

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', ['title' => 'Mijn nieuwe project']);
        $this->assertDatabaseCount('project_images', 2);
        $this->assertTrue(Storage::disk('public')->exists(
            \App\Models\ProjectImage::first()->path
        ));
    }

    public function test_a_guest_cannot_open_project_management(): void
    {
        $this->get(route('admin.projects.index'))->assertRedirect(route('login'));
    }
}
