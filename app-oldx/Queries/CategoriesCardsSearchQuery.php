<?php
namespace App\Queries;

use App\Models\Category;

class CategoriesCardsSearchQuery {

    public static function run()
    {

        $page = 20;
        $count = 0;

        $columns = [
            0 => [],
            1 => []
        ];

        $categories = Category::withCount('files')->get()->toTree()->each( function ($category, $index) use (&$columns) {

            $side = $index%2;
            $len = count($columns[$side]);
            $columns[$side][] = [
                'data' => $category,
                'count' => self::count($category) + ($len < 1 ? 0 : $columns[$side][$len-1]['count']),
            ];
        });

        $results = [];



        dd($results);
    }

    public static function count($category)
    {
        return 1 + $category->files_count + $category->children->reduce(function ($carry, $item) {
            return $carry + self::count($item);
        });
    }

}
