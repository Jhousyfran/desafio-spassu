<?php

namespace App\Http\Controllers;

use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Models\Author;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends ApiBaseController
{
    protected string $model = Author::class;
    protected $storeRequestClass = StoreAuthorRequest::class;
    protected $updateRequestClass = UpdateAuthorRequest::class;
    protected string $resource = 'Autor';

}
