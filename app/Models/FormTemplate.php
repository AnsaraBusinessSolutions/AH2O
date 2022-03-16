<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormTemplate extends Model
{
    //
    public $timestamps = true;
    protected $fillable = ["name","template_content","created_by","template_form_url","name_slug"];
    protected $table = "form_template";
}
