<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BioLinkFlowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_displays_links_in_saved_order(): void
    {
        $user = User::factory()->create(['handler' => '@player']);
        Link::factory()->create(['user_id' => $user->id, 'sort' => 3, 'name' => 'Last Link']);
        Link::factory()->create(['user_id' => $user->id, 'sort' => 1, 'name' => 'First Link']);

        $this->get('/@player')->assertOk()->assertSeeInOrder(['First Link', 'Last Link']);
    }

    public function test_authenticated_user_can_open_dashboard_and_append_links(): void
    {
        $user = User::factory()->create();
        Link::factory()->create(['user_id' => $user->id, 'sort' => 4]);
        $this->actingAs($user)->get('/')->assertOk();

        $this->post(route('links.store'), ['name' => 'New Link', 'link' => 'https://example.com'])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('links', ['user_id' => $user->id, 'name' => 'New Link', 'sort' => 5]);
    }

    public function test_link_reordering_handles_gaps_and_boundaries(): void
    {
        $user = User::factory()->create();
        $first = Link::factory()->create(['user_id' => $user->id, 'sort' => 1]);
        $last = Link::factory()->create(['user_id' => $user->id, 'sort' => 3]);

        $this->actingAs($user)->patch(route('links.up', $first))->assertRedirect();
        $this->patch(route('links.down', $last))->assertRedirect();
        $this->assertSame(1, $first->fresh()->sort);
        $this->assertSame(3, $last->fresh()->sort);

        $this->patch(route('links.up', $last))->assertRedirect();
        $this->assertSame(1, $last->fresh()->sort);
        $this->assertSame(3, $first->fresh()->sort);
        $this->patch(route('links.down', $last))->assertRedirect();
        $this->assertSame(3, $last->fresh()->sort);
    }

    public function test_users_cannot_change_another_users_links(): void
    {
        $link = Link::factory()->create(['user_id' => User::factory()->create()->id]);
        $this->actingAs(User::factory()->create());
        $this->get(route('links.edit', $link))->assertForbidden();
        $this->put(route('links.edit', $link), ['name' => 'Changed', 'link' => 'https://example.com'])->assertForbidden();
        $this->delete(route('links.destroy', $link))->assertForbidden();
        $this->patch(route('links.up', $link))->assertForbidden();
        $this->patch(route('links.down', $link))->assertForbidden();
        $this->assertModelExists($link);
    }

    public function test_profile_rejects_invalid_or_duplicate_handlers(): void
    {
        $user = User::factory()->create(['handler' => '@original']);
        User::factory()->create(['handler' => '@taken']);
        $this->actingAs($user);

        foreach (['missing-prefix', '@has spaces', '@has/slash', '@taken', ['invalid']] as $handler) {
            $this->putJson(route('profile'), ['name' => 'Player', 'handler' => $handler])
                ->assertUnprocessable()->assertJsonValidationErrors('handler');
        }
        $this->assertSame('@original', $user->fresh()->handler);
    }

    public function test_profile_accepts_current_handler_and_photo_upload(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['handler' => '@player']);

        $this->actingAs($user)->put(route('profile'), [
            'name' => 'Player',
            'handler' => '@player',
            'description' => 'My profile',
            'photo' => UploadedFile::fake()->image('avatar.jpg'),
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertSame('My profile', $user->fresh()->description);
        Storage::disk('public')->assertExists($user->fresh()->photo);
        $this->get(route('profile'))->assertOk()->assertSee('name="description"', false);
    }

    public function test_registration_login_and_logout_work(): void
    {
        $this->post('/register', [
            'name' => 'Player', 'email' => 'player@example.com',
            'email_confirmation' => 'player@example.com', 'password' => 'password123',
        ])->assertRedirect(route('dashboard'));
        $user = User::where('email', 'player@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
        $this->post(route('signin'), ['email' => $user->email, 'password' => 'wrong'])
            ->assertRedirect();
        $this->assertGuest();
        $this->post(route('signin'), ['email' => $user->email, 'password' => 'password123'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_profile_migration_rolls_back_both_columns(): void
    {
        $migration = require database_path('migrations/2026_06_16_001157_add_profile_columns_to_users_table.php');
        $migration->down();
        $this->assertFalse(Schema::hasColumn('users', 'handler'));
        $this->assertFalse(Schema::hasColumn('users', 'description'));
        $migration->up();
    }
}
