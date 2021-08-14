<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    public function it_has_a_user()
    {
        $user= $this->singIn();
        $project = ProjectFactory::ownedBy($user)->create();

        $this->assertEquals($user->id,$project->activity()->first()->user->id);
    }
}
