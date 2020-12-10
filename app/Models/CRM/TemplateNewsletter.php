<?php

namespace App\Models\CRM;

use Illuminate\Database\Eloquent\Model;

class TemplateNewsletter extends Model
{
    protected $guarded = [];
    protected $table = 'template_newsletters';
    protected $connection = 'crmmarket_database';


}
