<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase,WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
     /** @test */
     public function it_belogs_to_a_project()
     {

         $task = Task::factory()->create();

        $this->assertInstanceOf(Project::class, $task->project);
     }
    /** @test */
    public function it_has_a_path()
    {
        $this->withoutExceptionHandling();

        $task = Task::factory()->create();

        $this->assertEquals('/projects/'.$task->project->id.'/tasks/'.$task->id,$task->path());
    }
     /** @test */
    public function it_can_be_completed()
    {
        $task = Task::factory()->create();

        $this->assertFalse($task->completed);

        $task->complete();

        $this->assertTrue($task->fresh()->completed);
    }
     /** @test */
     public function it_can_be_marked_as_incompleted()
     {
         $task = Task::factory()->create(['completed'=>true]);

         $this->assertTrue($task->completed);

         $task->incomplete();

         $this->assertFalse($task->fresh()->completed);
     }
}
