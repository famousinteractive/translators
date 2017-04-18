<?php

namespace Famousinteractive\Translators\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'famousinteractiveTranslators_content';
    public $timestamps = true;
    protected $fillable = [
        'id','key','description','html'
    ];

    public function translation() {
        return $this->hasMany('\Famousinteractive\Translators\Models\ContentTranslation', 'content_id','id');
    }
}
