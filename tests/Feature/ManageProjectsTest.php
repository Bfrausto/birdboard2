<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
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
        $this->get($project->path().'/edit')->assertRedirect('login');
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

        $this->singIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => 'General notes here.'
        ];

        $response =  $this->post('/projects',$attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects',$attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);

    }
    /** @test */
    public function a_user_can_see_all_projects_they_have_benn_invited_to_on_their_dashboard()
    {
        $project = tap(ProjectFactory::create())->invite($this->singIn());

        $this->get('/projects')->assertSee($project->title);

    }
    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project= ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects',$project->only('id'));
    }
     /** @test */
     public function unauthorized_users_cannot_delete_a_project()
     {
         $project= ProjectFactory::create();

         $this->delete($project->path())
             ->assertRedirect('/login');

        $this->singIn();
        $this->delete($project->path())
        ->assertStatus(403);


     }
    /** @test */
    public function a_user_can_update_a_project()
    {
//        $this->singIn();
//        $this->withoutExceptionHandling();
//        $project = Project::factory()->create(['owner_id'=> auth()->id()]);

        $project= ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(),$attributes =['title'=>'change','description'=>'Changed','notes'=> 'Changed'])
            ->assertRedirect($project->path());

        $this->get($project->path().'/edit')->assertOk();

        $this->assertDatabaseHas('projects',$attributes);
    }


     /** @test */
     public function a_user_can_update_a_projects_general_notes()
     {
        $project= ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->path(),$attributes =['notes'=> 'Changed']);


        $this->assertDatabaseHas('projects',$attributes);

     }
     /** @test */
     public function a_user_can_view_their_project()
     {
//        $this->singIn();
//        $this->withoutExceptionHandling();
//        $project = Project::factory()->create(['owner_id'=> auth()->id()]);
         $project= ProjectFactory::create();

         $this->actingAs($project->owner)
             ->get($project->path())
            ->assertSee($project->title)
            ->assertSee(str_limit($project->description,150));
     }
    /** @test */
    public function an_authenticated_user_cannnot_view_the_projects_of_others()
    {
        $this->singIn();
        $project = Project::factory()->create();
        $this->get($project->path())
            ->assertStatus(403);
    }
    /** @test */
    public function an_authenticated_user_cannnot_update_the_projects_of_others()
    {
        $this->singIn();
        $project = Project::factory()->create();
        $this->patch($project->path())
             ->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->singIn();
        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('/projects',$attributes)->assertSessionHasErrors('title');
    }
     /** @test */
    public function a_project_requires_a_description()
    {
        $this->singIn();

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
