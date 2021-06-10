<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ManageProjectsTest extends TestCase
{
    use RefreshDatabase,WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    public function guess_cannot_manage_projects()
    {

        // $attributes = Project::factory()->raw();
        $project = Project::factory()->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');
    }
    // /** @test */
    // public function guess_cannot_view_projects()
    // {
    //     $this->get('/projects')->assertRedirect('login');
    // }
    // /** @test */
    // public function guess_cannot_view_a_single_project()
    // {
    //     $project = Project::factory()->create();
    //     $this->get($project->path())->assertRedirect('login');
    // }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(User::factory()->create());

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects',$attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects',$attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

     /** @test */
     public function a_user_can_view_their_project()
     {
        $this-> actingAs(User::factory()->create());
        $this->withoutExceptionHandling();
        $project = Project::factory()->create(['owner_id'=> auth()->id()]);
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
     }
     /** @test */
     public function a_user_cannnot_view_theprojects_of_others()
     {
        $this-> actingAs(User::factory()->create());
        $project = Project::factory()->create();
        $this->get($project->path())
            ->assertStatus(403);
     }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('/projects',$attributes)->assertSessionHasErrors('title');
    }
     /** @test */
    public function a_project_requires_a_description()
    {
        $this->actingAs(User::factory()->create());

        $attributes = Project::factory()->raw(['description' => '']);
        $this->post('/projects',$attributes)->assertSessionHasErrors('description');
    }
    // /** @test */
    // public function a_project_requires_an_owner()
    // {
    //     $this->actingAs(User::factory()->create());
    //     $attributes = Project::factory()->raw(['owner_id' => null]);

    //     $this->post('/projects',$attributes)->assertSessionHasErrors('owner_id');
    // }



}
