<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Events\CategoryBeforeDelete;

class Category extends Model
{
    use HasFactory,NodeTrait;

    protected $fillable = [
        'name',
    ];

    protected $events = [
        'deleting' => CategoryBeforeDelete::class,
    ];

    public function files()
    {
        return $this->hasMany(\App\Models\File::class)->with('user')
        ->orderByRaw(
            "name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name"
        )
        ;

    }
}
