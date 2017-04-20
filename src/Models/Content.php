<?php

namespace Famousinteractive\Translators\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'famousinteractiveTranslators_content';
    public $timestamps = true;
    protected $fillable = [
        'id','key','description','html','container'
    ];

    public function translations() {
        return $this->hasMany('\Famousinteractive\Translators\Models\ContentTranslation', 'content_id','id');
    }
}
