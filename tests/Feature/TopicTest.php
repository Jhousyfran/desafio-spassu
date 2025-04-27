<?php

namespace Tests\Feature;

use App\Models\Topic;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;



class TopicTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * testa a criação de um assunto com sucesso.
     */
    public function testCreateTopicSucccess()
    {
        $response = $this->postJson(route('topics.store'), [
            'name' => 'Ficção Científica',
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'name' => 'Ficção Científica',
            ],
        ]);
    }

    /**
     * testa a criação de um assunto com falha
     * por questões de validação.
     */
    public function testCreateTopicFail()
    {
        $response = $this->postJson(route('topics.store'), [
            'name' => 'Ab',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        
        $response = $this->postJson(route('topics.store'), [
            'name' => '',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->postJson(route('topics.store'), [
            'name' => Topic::formatField('Romance'),
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'name' => Topic::formatField('Romance'),
            ],
        ]);

        $response = $this->postJson(route('topics.store'), [
            'name' => Topic::formatField('Romance'),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     * testa a atualização de um assunto com sucesso.
     */
    public function testUpdateTopicSucccess()
    {
        $topic = Topic::create([
            'name' => 'Ficção Científica',
        ]);

        $response = $this->putJson(route('topics.update', ['topic' => $topic->id]), [
            'name' => 'Romance',
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'name' => 'Romance',
            ],
        ]);
    }

    /**
     * testa a atualização de um assunto com falha
     * por questões de validação.
     */
    public function testUpdateTopicFail()
    {
        // Testa atualizar um assunto com nome que já esta cadastardo
        $option1 = Topic::create([
            'name' => 'Ficção Científica',
        ]);
        $option2 = Topic::create([
            'name' => 'Romance',
        ]);

        $response = $this->putJson(route('topics.update', ['topic' => $option1->id]), [
            'name' => $option2->name,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Testa atualizar um assunto com nome que já esta cadastardo Diferenciando minisculas e maiúsculas
        $response = $this->putJson(route('topics.update', ['topic' => $option1->id]), [
            'name' => strtoupper($option2->name),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Com espaço em branco no começo e no final do nome e letra minisculas e maiúsculas
        $response = $this->putJson(route('topics.update', ['topic' => $option2->id]), [
            'name' => ' ' . $option1->name .' ',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * testa a exclusão de um assunto com sucesso.
     */
    public function testDeleteTopicSucccess()
    {
        $topic = Topic::create([
            'name' => 'Ficção Científica',
        ]);

        $response = $this->deleteJson(route('topics.destroy', ['topic' => $topic->id]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * testa a exclusão de um assunto com falha
     * por questões de validação.
     */
    public function testDeleteTopicFail()
    {
        $response = $this->deleteJson(route('topics.destroy', ['topic' => 999]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}

