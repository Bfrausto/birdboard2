<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

          $project = Project::factory()->create();

          $task = $project->addTask('Test Task');

          $this->patch($project->path().'/tasks/'.$task->id,['body' =>'test Tasks'])
              ->assertStatus(403);

          $this->assertDatabaseMissing('tasks',['body' =>'test Tasks']);

      }
    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->singIn();


        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $this->post($project->path().'/tasks',['body' =>'test Tasks']);
        $this->get($project->path())
            ->assertSee('test Tasks');
    }

    /** @test */
    public function test_a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->singIn();


        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $task = $project->addTask('Test task');
        $this->patch($project->path().'/tasks/'.$task->id,[
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
        $this->singIn();
        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );

        $attributes = Task::factory()->raw(['body' => '']);
        $this->post($project->path().'/tasks',$attributes)->assertSessionHasErrors('body');
    }
}
