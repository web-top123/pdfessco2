<?php

namespace App\Http\Controllers;

// use App\Queries\CategoriesCardsQuery;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesCardsController extends Controller
{
    public function index()
    {
        // return CategoriesCardsQuery::run();
        return Category::where('parent_id', null)
            ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name")
            ->withCount('files')
            ->withCount('children')
            ->withDepth()
            ->get()
            ->toTree();

    }
}
