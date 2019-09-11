<?php

namespace App\Http\Controllers\Api;

use App\Author;
use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookController extends Controller
{

    public function create(Request $request)
    {
        $book = Book::create([
            'title'         => $request->title,
            'countPages'    => $request->countPages,
            'preview'       => $request->preview
        ]);

        if($request->input('authors')):
            $book->authors()->attach($request->input('authors'));
        endif;
    }

    public function view(Book $book)
    {
        return response()->json([
            'books' => $book::with('authors')->get(),
        ]);
    }
}
