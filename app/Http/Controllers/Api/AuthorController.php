<?php

namespace App\Http\Controllers\Api;

use App\Author;
//use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthorController extends Controller
{
    public function create(Request $request)
    {
        $author = Author::create([
            'name' => $request->name,
        ]);

        return response()->json(['author' => $author]);
    }
}
