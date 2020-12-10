<?php

namespace App\Http\Controllers;


use App\Jobs\SendMailJob;
use App\Models\CRM\Project;
use App\Models\CRM\Projectconfigurations;

use App\Models\CRM\TemplateNewsletter;
use Flynsarmy\DbBladeCompiler\Facades\DbView;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{


    public function index(Request $request)
    {
        $data = $request->only([
            'project_slug',
            'newsletter_slug',
            'email_to',
            'language',
            'template_data'
        ]);

        $validator = Validator::make($data,
            [
                'project_slug' => 'required|string',
                'newsletter_slug' => 'required|string',
                'email_to' => 'required|email',
                'language' => 'required|string',
                'template_data' => 'required|array',
            ]

        );


        if ($validator->fails()) return response()->json($validator->errors(), 403);
        $project = Project::query()->where('slug', $request->get('project_slug'))->first();
        if (!$project)
            return response()->json('Project Not Found !', 404);


        $templateNewsletter = TemplateNewsletter::query()
            ->where('slug', $request->get('newsletter_slug'))
            ->where('lang', $request->get('language'))
            ->first();

        if (!$templateNewsletter)
            return response()->json('Newsletter Template Not Found !', 404);


        Queue::later(10,new SendMailJob($data ,$project ,$templateNewsletter));
        dd("as");
        return response()->json('Done');

    }


}
