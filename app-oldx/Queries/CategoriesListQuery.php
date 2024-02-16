<?php

namespace App\Queries;

use App\Models\Category;

class CategoriesListQuery
{
    public static function run()
    {
        return Category::withCount('files')
            ->withDepth()
            ->orderBy('name', 'asc')
            ->get()
            ->toFlatTree();
    }
}
