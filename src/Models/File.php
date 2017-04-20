<?php

namespace Famousinteractive\Translators\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'famousinteractiveTranslators_file';
    public $timestamps = true;
    protected $fillable = ['name', 'url','disk','container'];

}
