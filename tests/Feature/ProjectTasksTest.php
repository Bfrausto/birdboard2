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
