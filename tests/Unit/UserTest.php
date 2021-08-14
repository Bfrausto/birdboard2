<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    /** @test */
    public function a_user_has_projects()
    {
        $user=User::factory()->create();
        // dd($user->projects());
        $this->assertInstanceOf(HasMany::class, $user->projects());
    }
    /** @test */
    public function a_user_has_accessible_projects()
    {
        $john = $this->singIn();

        ProjectFactory::ownedBy($john)->create();

        $this->assertCount(1,$john->accessibleProjects());

        $sally = User::factory()->create();
        $nick = User::factory()->create();

        $sallyProject=tap(ProjectFactory::ownedBy($sally)->create())->invite($nick);

        $this->assertCount(1,$john->accessibleProjects());

        $sallyProject->invite($john);

        $this->assertCount(2,$john->accessibleProjects());



    }

}
