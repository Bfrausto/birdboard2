<?php

namespace Tests\Feature;

use App\Models\User;
use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase,WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    function now_owners_not_invite_users()
    {
        $project = ProjectFactory::create();

        $user = User::factory()->create();
        $assertInvitationsForbidden=function()use($user,$project){
            $this->actingAs($user)
            ->post($project->path().'/invitations')
            ->assertStatus(403);
        };
        $assertInvitationsForbidden();
        $project->invite($user);
        $assertInvitationsForbidden();

       
    }
    /** @test */
    function a_project_can_invite_a_user()
    {
        $this->withoutExceptionHandling();
        $project = ProjectFactory::create();

        $userToInvite = User::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invitations',[
                'email' => $userToInvite->email
        ])
        ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    function the_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path().'/invitations',[
                'email' => 'notauser@email.com'
            ])
            ->assertSessionHasErrors([
                'email'=>'The user you are inviting must have a Birdboar account.'
            ],null,'invitations');

    }
    /** @test */
    public function invited_users_may_update_project_details()
    {
        $project= ProjectFactory::create();

        $project->invite($newUser= User::factory()->create());

        $this->actingAs($newUser)
            ->post(action('ProjectsTasksController@store',$project),$task=['body'=>'Foo task']);

        $this->assertDatabaseHas('tasks',$task);
    }
}
