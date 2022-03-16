<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    //
    protected $table = "pages";
    public $timestamps = true;
    protected $fillable = [
        "pagename","pagetitle","form_template_linked","form_template_content","created_by"
    ];
}
