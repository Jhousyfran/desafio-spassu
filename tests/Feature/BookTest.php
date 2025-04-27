<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Topic;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BookTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * testa a criação de um livro com sucesso.
     */
    public function testCreateBookSucccess()
    {
        $topic = Topic::factory()->create();
        $author = Author::factory()->create();
        
        $data = [
            'title' => 'Dom Quixote',
            'publisher' => 'Saraiva',
            'edition' => '1',
            'year_of_publication' => '1820',
            'price' => '40.40',
            'authors' => [
                intval($author->id),
            ],
            'topics' => [
                intval($topic->id),
            ],
        ];

        $response = $this->postJson(route('books.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'title' => $data['title'],
                'publisher' => $data['publisher'],
                'edition' => $data['edition'],
                'year_of_publication' => $data['year_of_publication'],
                'price' => $data['price'],
            ],
        ]);
    }

    /**
     * testa a criação de um livro com falha
     * por questões de validação.
     */
    public function testCreateBookFail()
    {
        $response = $this->postJson(route('books.store'), [
            'name' => 'Ab',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        
        $response = $this->postJson(route('books.store'), [
            'name' => '',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);


        // 
        $topic = Topic::factory()->create();
        $author = Author::factory()->create();
        
        $data = [
            'title' => 'Dom Quixote',
            'publisher' => 'Saraiva',
            'edition' => '1',
            'year_of_publication' => '1820',
            'price' => '40.40',
            'authors' => [
                intval($author->id),
            ],
            'topics' => [
                intval($topic->id),
            ],
        ];
        $response = $this->postJson(route('books.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'title' => $data['title'],
            ],
        ]);

        $response = $this->postJson(route('books.store'), $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     * testa a atualização de um livro com sucesso.
     */
    public function testUpdateBookSucccess()
    {
        $topics = Topic::factory()->count(3)->create();
        $authors = Author::factory()->count(5)->create();
        
        $data = [
            'title' => 'Dom Quixote',
            'publisher' => 'Saraiva',
            'edition' => 1,
            'year_of_publication' => '1820',
            'price' => '40.40',
            'authors' => Arr::pluck($authors, 'id'),
            'topics' => Arr::pluck($topics, 'id'),
        ];

        $book = $this->postJson(route('books.store'), $data);
        $book->assertStatus(Response::HTTP_CREATED);

        $response = $this->putJson(route('books.update', ['book' => $book['data']['id'] ]), [
            'title' => 'Dom Quixote 2',
            'publisher' => 'Saraiva 2',
            'edition' => '1',
            'year_of_publication' => '1820',
            'price' => '40.40',
            'authors' => [ $authors[0]['id'] ],
            'topics' => Arr::pluck($topics, 'id'),
        ]);

        $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'data' => [
                'title' => 'Dom Quixote 2',
                'publisher' => 'Saraiva 2',
            ],
        ]);

        $reponse = $this->getJson(route('books.show', ['book' => $book['data']['id'] ]));
        $reponse->assertStatus(Response::HTTP_OK);
        $reponse->assertJson([
            'data' => [
                'title' => 'Dom Quixote 2',
                'publisher' => 'Saraiva 2',
                'edition' => $data['edition'],
                'year_of_publication' => $data['year_of_publication'],
                'price' => $data['price'],
                'authors' => [
                    [ 'id' => $authors[0]['id'] ]
                ],
                'topics' => [
                    [ 'id' => $topics[0]['id'] ],
                    [ 'id' => $topics[1]['id'] ],
                    [ 'id' => $topics[2]['id'] ],
                ],
            ],
        ]);
    }

    /**
     * testa a atualização de um livro com falha
     * por questões de validação.
     */
    public function testUpdateBookFail()
    {
        // Testa atualizar um livro com nome que já esta cadastardo
        $author = Author::factory()->create();
        $topic = Topic::factory()->create();
        $option1 = Book::factory()->create();
        $option1->authors()->attach($author);
        $option1->topics()->attach($topic);
        $option2 = Book::factory()->create();

        $response = $this->putJson(route('books.update', ['book' => $option1->id]), [
            'title' => $option2->title,
            'publisher' => $option2->publisher,
            'edition' => $option2->edition,
            'year_of_publication' => $option2->year_of_publication,
            'price' => $option2->price,
            'authors' => [
                $author->id,
            ],
            'topics' => [
                $topic->id,
            ],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'O campo título já está sendo utilizado.',
        ]);

        // Testa atualizar um livro com nome que já esta cadastardo Diferenciando minisculas e maiúsculas
        $response = $this->putJson(route('books.update', ['book' => $option1->id]), [
            'name' => strtoupper($option2->name),
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        // Com espaço em branco no começo e no final do nome e letra minisculas e maiúsculas
        $response = $this->putJson(route('books.update', ['book' => $option2->id]), [
            'name' => ' ' . $option1->name .' ',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * testa a exclusão de um livro com sucesso.
     */
    public function testDeleteBookSucccess()
    {
        $author = Author::factory()->count(3)->create();
        $topic = Topic::factory()->count(3)->create();
        $book = Book::factory()->create();
        $book->authors()->attach($author);
        $book->topics()->attach($topic);

        $response = $this->deleteJson(route('books.destroy', ['book' => $book->id]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * testa a exclusão de um livro com falha
     * por questões de validação.
     */
    public function testDeleteBookFail()
    {
        $response = $this->deleteJson(route('books.destroy', ['book' => 999]));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
