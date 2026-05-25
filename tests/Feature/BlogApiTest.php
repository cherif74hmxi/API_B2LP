<?php

namespace Tests\Feature;

use App\Models\Billet;
use App\Models\Commentaire;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BlogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_read_billets(): void
    {
        Billet::factory()->create([
            'BIL_TITRE' => 'Premier billet',
            'BIL_CONTENU' => 'Contenu public',
        ]);

        $response = $this->getJson('/api/billets');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.Titre', 'Premier billet');
    }

    public function test_admin_can_create_billet(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/billets', [
            'titre' => 'Billet admin',
            'contenu' => 'Contenu du billet admin',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Titre', 'Billet admin');

        $this->assertDatabaseHas('billets', [
            'BIL_TITRE' => 'Billet admin',
            'user_id' => $admin->id,
        ]);
    }

    public function test_non_admin_cannot_create_billet(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/billets', [
            'titre' => 'Refus',
            'contenu' => 'Ce billet ne doit pas passer',
        ]);

        $response->assertForbidden()
            ->assertJsonPath('success', false);
    }

    public function test_authenticated_swimmer_can_add_commentaire(): void
    {
        $user = User::factory()->create();
        $billet = Billet::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/billets/'.$billet->id.'/commentaires', [
            'commentaire' => 'Tres bon billet.',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Contenu', 'Tres bon billet.');

        $this->assertDatabaseHas('commentaires', [
            'billet_id' => $billet->id,
            'user_id' => $user->id,
            'COM_CONTENU' => 'Tres bon billet.',
        ]);
    }

    public function test_guest_cannot_add_commentaire(): void
    {
        $billet = Billet::factory()->create();

        $response = $this->postJson('/api/billets/'.$billet->id.'/commentaires', [
            'commentaire' => 'Je ne suis pas connecte.',
        ]);

        $response->assertUnauthorized()
            ->assertJsonPath('success', false);
    }

    public function test_admin_can_delete_commentaire(): void
    {
        $admin = User::factory()->admin()->create();
        $commentaire = Commentaire::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->deleteJson('/api/commentaires/'.$commentaire->id);

        $response->assertNoContent();

        $this->assertDatabaseMissing('commentaires', [
            'id' => $commentaire->id,
        ]);
    }
}
