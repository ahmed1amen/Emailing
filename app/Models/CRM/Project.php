<?php

namespace App\Models\CRM;


use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $connection = 'crmmarket_database';
    protected $guarded = [];
    protected $with = ['configurations'];

    public function configurations()
    {

        return $this->hasOne(Projectconfigurations::class);
    }
}
