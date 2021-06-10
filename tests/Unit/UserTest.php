<?php

namespace Tests\Unit;

use App\Models\User;
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
}
