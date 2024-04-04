<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testUserLogout()
    {
        $user = User::factory()->create();

        // Créer un jeton d'authentification pour l'utilisateur
        $token = $user->createToken('TestToken')->plainTextToken;

        // Faire une requête POST à l'API pour se déconnecter
        $response = $this->actingAs($user)->postJson('/api/logout');

        // Vérifier que l'utilisateur n'a plus de jetons d'authentification
        $this->assertEmpty($user->tokens);

        // Vérifier que la réponse contient le message "You Logged Out!"
        $response->assertStatus(200)->assertJson(['message' => 'You Logged Out!']);
    }

    // public function testUserLoginWithValidCredentials()
    // {
    //     $user = User::factory()->create([
    //         'email' => 'test@example.com',
    //         'password' => bcrypt('password123'),
    //     ]);

    //     // Faire une requête POST à l'API pour l'authentification
    //     $response = $this->postJson('/api/login', [
    //         'email' => 'test@example.com',
    //         'password' => 'password123',
    //     ]);

    //     // Vérifier que la réponse a un code de statut HTTP 200
    //     $response->assertStatus(200);

    //     // Vérifier que la réponse contient les données de l'utilisateur et le jeton d'authentification
    //     $response->assertJsonStructure([
    //         'user' => [
    //             'id',
    //             'name',
    //             'email',
    //             'created_at',
    //             'updated_at',
    //         ],
    //         'token' => [
    //             'access_token',
    //             'token_type',
    //             'expires_in',
    //         ],
    //     ]);

    //     // Vérifier que les données de l'utilisateur dans la réponse correspondent à celles de l'utilisateur de test
    //     $response->assertJsonFragment([
    //         'email' => $user->email,
    //         // Ajoutez d'autres attributs d'utilisateur ici selon vos besoins
    //     ]);
    // }

    protected function createToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 2880,
            'user' => auth()->user()
        ], 200);
    }

//     public function testUserLoginWithValidCredentials()
// {
//     // Créer un utilisateur de test
//     $user = User::factory()->create([
//         'email' => 'test@example.com',
//         'password' => bcrypt('password123'),
//     ]);

//     // Faire une requête POST à l'API pour l'authentification avec les informations d'identification correctes
//     $response = $this->postJson('/api/login', [
//         'email' => 'test@example.com',
//         'password' => 'password123',
//     ]);

//     // Vérifier que la réponse a un code de statut HTTP 200
//     $response->assertStatus(200);

//     // Vérifier que la réponse contient les données de l'utilisateur et le jeton d'authentification
//     $response->assertJsonStructure([
//         'user' => [
//             'id',
//             'name',
//             'email',
//             'created_at',
//             'updated_at',
//         ],
//         'token' => [
//             'access_token',
//             'token_type',
//             'expires_in',
//         ],
//     ]);
// }

}
