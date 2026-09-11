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

    public function test_an_admin_can_update_a_project_and_manage_images(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post(route('admin.projects.store'), [
            'title' => 'Oude titel',
            'description' => 'Oude beschrijving',
            'images' => [UploadedFile::fake()->image('old.jpg')],
        ]);
        $project = \App\Models\Project::with('images')->first();
        $oldPath = $project->images->first()->path;

        $response = $this->actingAs($admin)->put(route('admin.projects.update', $project), [
            'title' => 'Nieuwe titel',
            'description' => 'Nieuwe beschrijving',
            'remove_images' => [$project->images->first()->id],
            'images' => [UploadedFile::fake()->image('new.jpg')],
        ]);

        $response->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', ['title' => 'Nieuwe titel', 'description' => 'Nieuwe beschrijving']);
        $this->assertDatabaseCount('project_images', 1);
        $this->assertFalse(Storage::disk('public')->exists($oldPath));
    }

    public function test_an_admin_can_delete_a_project_and_its_images(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->post(route('admin.projects.store'), [
            'title' => 'Te verwijderen',
            'description' => 'Beschrijving',
            'images' => [UploadedFile::fake()->image('delete.jpg')],
        ]);
        $project = \App\Models\Project::with('images')->first();
        $path = $project->images->first()->path;

        $this->actingAs($admin)->delete(route('admin.projects.destroy', $project))
            ->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseCount('project_images', 0);
        $this->assertFalse(Storage::disk('public')->exists($path));
    }
}
