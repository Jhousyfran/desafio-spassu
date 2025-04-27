<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Models\Book;

class BookController extends ApiBaseController
{
    protected string $model = Book::class;
    protected $storeRequestClass = StoreBookRequest::class;
    protected $updateRequestClass = UpdateBookRequest::class;
    protected string $resource = 'Livro';
}
