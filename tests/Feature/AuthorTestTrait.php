<?php

namespace Tests\Feature;

Trait AuthorTestTrait
{
    public function postCreate($data)
    {
        return $this->postJson('/api/authors', [
            'name' => 'John Doe',
        ]);
    }
}