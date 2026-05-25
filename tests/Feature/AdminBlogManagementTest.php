<?php

namespace Tests\Feature;

use App\Models\Billet;
use App\Models\Commentaire;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminBlogManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_create_billet_via_api(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/billets', [
            'BIL_DATE' => '2026-04-22',
            'BIL_TITRE' => 'Nouveau billet',
            'BIL_CONTENU' => 'Contenu de test',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_update_and_delete_billet_via_api(): void
    {
        $admin = User::factory()->admin()->create();

        Sanctum::actingAs($admin);

        $createResponse = $this->postJson('/api/billets', [
            'BIL_DATE' => '2026-04-22',
            'BIL_TITRE' => 'Billet admin',
            'BIL_CONTENU' => 'Contenu admin',
        ]);
        $createResponse->assertCreated();

        $billet = Billet::firstOrFail();

        $updateResponse = $this->putJson('/api/billets/'.$billet->id, [
            'BIL_DATE' => '2026-04-23',
            'BIL_TITRE' => 'Billet admin modifie',
            'BIL_CONTENU' => 'Contenu admin modifie',
        ]);
        $updateResponse->assertOk();

        $this->assertDatabaseHas('billets', [
            'id' => $billet->id,
            'BIL_TITRE' => 'Billet admin modifie',
        ]);

        $deleteResponse = $this->deleteJson('/api/billets/'.$billet->id);
        $deleteResponse->assertNoContent();

        $this->assertDatabaseMissing('billets', [
            'id' => $billet->id,
        ]);
    }

    public function test_admin_can_delete_commentaire_via_api(): void
    {
        $admin = User::factory()->admin()->create();
        $author = User::factory()->create();
        $billet = Billet::factory()->create();
        $commentaire = Commentaire::factory()->create([
            'billet_id' => $billet->id,
            'user_id' => $author->id,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->deleteJson('/api/commentaires/'.$commentaire->id);

        $response->assertNoContent();
        $this->assertDatabaseMissing('commentaires', [
            'id' => $commentaire->id,
        ]);
    }

    public function test_guest_is_redirected_when_opening_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk();
        $response->assertSeeText('Panel Admin Blog');
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertForbidden();
    }
}
