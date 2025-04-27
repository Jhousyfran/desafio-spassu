<?php

namespace Tests\Feature;

use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use DatabaseMigrations;
    use AuthorTestTrait;

    /**
     * testa a criação de um autor com sucesso.
     */
    public function testCreateAuthorSucccess()
    {
        $response = $this->postJson(route('authors.store'), [
            'name' => 'John Doe',
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'name' => 'John Doe',
            ],
        ]);
    }

    /**
     * testa a criação de um autor com falha
     * por questões de validação.
     */
    public function testCreateAuthorFail()
    {
        $response = $this->postJson(route('authors.store'), [
            'name' => 'Jo',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        
        $response = $this->postJson(route('authors.store'), [
            'name' => '',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('authors.store'), [
            'name' => Author::formatName('Antoine de Saint-Exupéry'),
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'name' => Author::formatName('Antoine de Saint-Exupéry'),
            ],
        ]);

        $response = $this->postJson(route('authors.store'), [
            'name' => Author::formatName('Antoine de Saint-Exupéry'),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     * testa a atualização de um autor com sucesso.
     */
    public function testUpdateAuthorSucccess()
    {
        $author = Author::create([
            'name' => 'John Doe',
        ]);

        $response = $this->putJson(route('authors.update', ['author' => $author->id]), [
            'name' => 'Jane Doe',
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'name' => 'Jane Doe',
            ],
        ]);
    }

    /**
     * testa a atualização de um autor com falha
     * por questões de validação.
     */
    public function testUpdateAuthorFail()
    {
        // Testa atualizar um autor com nome que já esta cadastardo
        $john = Author::create([
            'name' => 'John Doe',
        ]);
        $jKRowling = Author::create([
            'name' => 'J.K. Rowling',
        ]);

        $response = $this->putJson(route('authors.update', ['author' => $john->id]), [
            'name' => 'J.K. Rowling',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Testa atualizar um autor com nome que já esta cadastardo Diferenciando minisculas e maiúsculas
        $response = $this->putJson(route('authors.update', ['author' => $john->id]), [
            'name' => 'J.k. rowling',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Com espaço em branco no começo e no final do nome e letra minisculas e maiúsculas
        $response = $this->putJson(route('authors.update', ['author' => $jKRowling->id]), [
            'name' => ' joHn DOe ',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * testa a exclusão de um autor com sucesso.
     */
    public function testDeleteAuthorSucccess()
    {
        $author = Author::create([
            'name' => 'John Doe',
        ]);

        $response = $this->deleteJson(route('authors.destroy', ['author' => $author->id]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * testa a exclusão de um autor com falha
     * por questões de validação.
     */
    public function testDeleteAuthorFail()
    {
        $response = $this->deleteJson(route('authors.destroy', ['author' => 999]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
