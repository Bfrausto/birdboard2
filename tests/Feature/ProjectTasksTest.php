<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase,WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function a_only_the_owner_of_a_project_may_add_tasks()
    {
        $this->singIn();

        $project = Project::factory()->create();

        $this->post($project->path().'/tasks',['body' =>'test Tasks'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks',['body' =>'test Tasks']);

    }
      /** @test */
      public function a_only_the_owner_of_a_project_may_update_tasks()
      {
          $this->singIn();
          $project= ProjectFactory::withTasks(1)->create();

//
//          $project = Project::factory()->create();
//
//          $task = $project->addTask('Test Task');

          $this->patch($project->tasks[0]->path(),['body' =>'test Tasks'])
              ->assertStatus(403);

          $this->assertDatabaseMissing('tasks',['body' =>'test Tasks']);

      }
    /** @test */
    public function a_project_can_have_tasks()
    {
//        $this->singIn();
//
//
//        $project = auth()->user()->projects()->create(
//            Project::factory()->raw()
//        );
        $project= ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks',['body' =>'test Tasks']);
        $this->get($project->path())
            ->assertSee('test Tasks');
    }

    /** @test */
    public function test_a_task_can_be_updated()
    {
        //
//        $project = auth()->user()->projects()->create(
//            Project::factory()->raw()
//        );
//
//        $task = $project->addTask('Test task');
//        $project= app(ProjectFactory::class)
//            ->ownedBy($this->singIn())
//            ->withTasks(1)
//            ->create();
//        $project= app(ProjectFactory::class)
//            ->withTasks(1)
//            ->create();
        $project= ProjectFactory::withTasks(1)->create();
        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
            'body' =>'changed task',
            'completed' => true
        ]);
        $this->assertDatabaseHas('tasks',[
            'body' =>'changed task',
            'completed' => true
        ]);
    }
     /** @test */
    public function a_task_requires_a_body()
    {
        $project= ProjectFactory::create();


        $attributes = Task::factory()->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path().'/tasks',$attributes)->assertSessionHasErrors('body');
    }
}
