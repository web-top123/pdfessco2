<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\File;
use App\Models\Category;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('pdf_files_exist', function ($attribute, $value, $parameters, $validator) {

            collect($value)->each(function ($item){

                if ($item['type'] === 'file' && File::where('id', $item['id'])->get()->isEmpty()) {
                    return false;
                } else if ($item['type'] === 'divider' && strlen($item['text']) > 440) {
                    return false;
                }

            });

            return true;

        });


        Validator::extend('old_password', function ($attribute, $value, $parameters, $Validator) {

            return \Hash::check($value, current($parameters));

        }, 'Current password does not match.');


        Validator::extend('category_depth', function ($attribute, $value, $parameters, $Validator) {

            return Category::withDepth()->findOrFail($value)->depth < 3;

        }, 'Category depth.');
    }
}
