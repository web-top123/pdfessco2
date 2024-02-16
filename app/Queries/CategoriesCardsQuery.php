<?php

namespace App\Queries;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesCardsQuery
{
    protected static $columns = [0, 0];

    public static function run()
    {
        $list = Category::with(['files' => function ($q) {
            self::filter($q);
        }])
            ->withCount(['files' => function ($q) {
                self::filter($q);
            }])
            ->where(function ($q) {
                if (request()->has('q') && request()->q && request()->has('cat')) {
                    $q->where('name', 'like', '%' . request()->q . '%');
                }
            })
            ->withDepth()
            ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name")
            ->get()
            ->toTree()
            ->slice(request()->offset ?: 0)
            ->values();

        $categories = collect([]);
        $columns = [0, 0];
        $limit = config('dashboard.soft-limit');

        foreach ($list as $key => $category) {
            $count = self::count($category);

            $columns[$key % 2] += $count;
            $category->total = $count;

            $categories->push($category);

            if (($columns[0] >= config('dashboard.manage.page') && $columns[1] >= config('dashboard.manage.page'))  &&  (abs($columns[0] - $columns[1]) <= $limit)) {
                break;
            }

        }

        return $categories;
    }

    // public static function run()
    // {
    //     $list = Category::with(['files' => function ($q) {
    //         self::filter($q);
    //     }])
    //         ->withCount(['files' => function ($q) {
    //             self::filter($q);
    //         }])
    //         ->where(function ($q) {
    //             if (request()->has('q') && request()->q && request()->has('cat')) {
    //                 $q->where('name', 'like', '%' . request()->q . '%');
    //             }
    //         })
    //         ->withDepth()
    //         ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name")
    //         ->get()
    //         ->toTree()
    //         // ->slice(request()->offset ?: 0)
    //         ->values();
    //
    //     $categories = collect([]);
    //     $columns = [0, 0];
    //     // $limit = config('dashboard.soft-limit');
    //     $count = 0;
    //
    //     foreach ($list as $key => $category) {
    //         $_count = self::count($category);
    //         $count =+ $_count;
    //         $columns[$key % 2] += $_count;
    //
    //         $categories->push($category);
    //
    //
    //         // if (($count / 2 > config('dashboard.manage.page')) && ($columns[0] >= config('dashboard.manage.page') && $columns[1] >= config('dashboard.manage.page'))  &&  (abs($columns[0] - $columns[1]) <= 10)) {
    //         //     break;
    //         // }
    //
    //         // $limit += 5;
    //     }
    //
    //     return $categories;
    // }

    // public static function run()
    // {
    //     $list = Category::with(['files' => function ($q) {
    //                 self::filter($q);
    //             }])
    //                 ->withCount(['files' => function ($q) {
    //                     self::filter($q);
    //                 }])
    //                 ->where(function ($q) {
    //                     if (request()->has('q') && request()->q && request()->has('cat')) {
    //                         $q->where('name', 'like', '%' . request()->q . '%');
    //                     }
    //                 })
    //                 ->withDepth()
    //                 ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name")
    //                 ->get()
    //                 ->toTree()
    //                 ->slice(request()->offset ?: 0)
    //                 ->values();
    //
    //     $columns = [
    //         0 => [
    //             'data' => [
    //
    //             ],
    //             'count' => 0,
    //         ],
    //         1 => [
    //             'data' => [
    //
    //             ],
    //             'count' => 0,
    //         ],
    //     ];
    //
    //     foreach ($list as $key => $category) {
    //
    //
    //         if ( ($columns[0]['count'] > 30 || $columns[1]['count'] > 30) && abs($columns[0]['count'] - $columns[1]['count']) < 10) {
    //
    //             break;
    //
    //         }
    //
    //
    //         $count = self::count($category);
    //
    //         if ($columns[0]['count'] <= $columns[1]['count']) {
    //
    //             $category->left = true;
    //             $columns[0]['data'][] = $category;
    //             $columns[0]['count'] += $count;
    //
    //         } else {
    //             $category->right = true;
    //             $columns[1]['data'][] = $category;
    //             $columns[1]['count'] += $count;
    //
    //         }
    //     }
    //
    //     $result = [];
    //
    //     for ($i = 0; $i < count($columns[0]['data']); $i++)
    //     {
    //
    //         $result[$i*2] = $columns[0]['data'][$i];
    //     }
    //
    //     for ($i = 0; $i < count($columns[1]['data']); $i++)
    //     {
    //         $result[$i*2 + 1] = $columns[1]['data'][$i];
    //     }
    //
    //     return collect($result)->values();
    // }

    protected static function count(&$category)
    {

        if (!$category->files_count && request()->q) {
            $category['hide'] = true;
        } else {
            $category['hide'] = false;
        }

        return 1 + $category->files_count + $category->children->reduce(function ($carry, &$item) {
                    return $carry + self::count($item);
        });;

    }

    protected static function filter($q)
    {
        if (request()->has('q') && request()->q && !request()->has('cat')) {
            $q->where('name', 'like', '%' . request()->q . '%');
        }
    }

    protected static function sortNat($collection)
    {
        return $collection->sort(function ($a, $b) {
            $lengthA = strlen($a->name);
            $lengthB = strlen($b->name);
            $valueA = $a->name;
            $valueB = $b->name;

            if ($lengthA == $lengthB) {
                if ($valueA == $valueB) {
                    return 0;
                }
                return $valueA > $valueB ? 1 : -1;
            }
            return $lengthA > $lengthB ? 1 : -1;
        });
    }
}
