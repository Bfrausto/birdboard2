<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    public function it_has_a_path()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $this->assertEquals('/projects/'.$project->id,$project->path());
    }
     /** @test */
     public function it_belongs_to_an_owner()
     {
         $project = Project::factory()->create();
         $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $project->owner());
     }
}
