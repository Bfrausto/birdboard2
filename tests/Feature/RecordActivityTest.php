<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class RecordActivityTest extends TestCase
{
    use RefreshDatabase,WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    public function creating_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1,$project->activity);
        tap($project->activity->last(),function($activity){
            $this->assertEquals('created_project',$activity->description) ;
            $this->assertNull($activity->changes);
        });
    }
    /** @test */
    public function updating_a_project()
    {
        $project = ProjectFactory::create();

        $originalTitle = $project->title;

        $project->update(['title'=>'Changed']);

        $this->assertCount(2,$project->activity);

        tap($project->activity->last(),function($activity) use($originalTitle){
            $this->assertEquals('updated_project',$activity->description);

            $expected = [
                'before' => ['title' => $originalTitle],
                'after' =>[ 'title' => 'Changed']
            ];
            $this->assertEquals($expected,$activity->changes);
        });

    }
    /** @test */
    public function creating_a_task()
    {
        $project = ProjectFactory::create();

        $project->addTask('some task');

        $this->assertCount(2,$project->activity);
        tap($project->activity->last(),function($activity){
            $this->assertEquals('created_task',$activity->description);
            $this->assertInstanceOf(Task::class,$activity->subject);
            $this->assertEquals('some task',$activity->subject->body);
        });
        // $this->assertEquals('created_task',$project->activity->last()->description);

    }

     /** @test */
    public function completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->ActingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
                'body' => 'foobar',
                'completed' => true
        ]);

        $this->assertCount(3,$project->activity);

        tap($project->activity->last(),function($activity){
            $this->assertEquals('completed_task',$activity->description);
            $this->assertInstanceOf(Task::class,$activity->subject);

        });
    }
    /** @test */
    public function incompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->ActingAs($project->owner)
            ->patch($project->tasks[0]->path(),[
                'body' => 'foobar',
                'completed' => true
        ]);

        $this->assertCount(3,$project->activity);

         $this->patch($project->tasks[0]->path(),[
            'body' => 'foobar',
            'completed' => false
        ]);

        $project->refresh();

        $this->assertCount(4,$project->activity);

        $this->assertEquals('incompleted_task',$project->activity->last()->description);

    }
    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3,$project->activity);

    }

}
