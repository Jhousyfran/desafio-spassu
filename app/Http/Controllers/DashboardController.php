<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Topic;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => [
                'bookCount' => Book::count(),
                'topicCount' => Topic::count(),
                'authorCount' => Author::count(),
                'booksByAuthor' => Author::with(['books.topics'])->orderBy('name')->get(),
            ]
        ]);
    }
}
