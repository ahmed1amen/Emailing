<?php

namespace App\Models\CRM;

use App\Models\CRM\Project;
use Illuminate\Database\Eloquent\Model;

class Projectconfigurations extends Model
{
    protected $guarded = [];
    protected $table = 'project_configurations';
    protected $connection = 'crmmarket_database';


    public function project()
    {
        return $this->belongsTo(Project::class);
    }

}
