<?php

namespace App\Http\Controllers;
use App\Jobs\ExampleJob;
use App\Jobs\SendMailJob;
use App\Models\CRM\Project;
use App\Models\CRM\TemplateNewsletter;
use Illuminate\Http\Request;
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

        dispatch(new SendMailJob($data, $project, $templateNewsletter));

        return response()->json('done');

    }


}
