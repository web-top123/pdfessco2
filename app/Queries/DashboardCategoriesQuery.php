<?php

namespace App\Queries;

use App\Models\Category;

class DashboardCategoriesQuery
{

    public static function run ()
    {

        return Category::withCount('files')->orderBy('name', 'asc')->get()->toFlatTree();

    }

}
