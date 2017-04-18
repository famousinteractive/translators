<?php

namespace Famousinteractive\Translators\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTranslation extends Model
{
    protected $table = 'famousinteractiveTranslators_content_translation';
    public $timestamps = true;
    protected $fillable = [
        'id','content_id','value','lang'
    ];


}
