<?php

namespace App\Jobs;

use Flynsarmy\DbBladeCompiler\Facades\DbView;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendMailJob extends Job
{

    public $data;
    public $templateNewsletter;
    private $project;

    /**
     * SendMailJob constructor.
     * @param $data
     * @param $project
     * @param $templateNewsletter
     */
    public function __construct($data, $project, $templateNewsletter)
    {
        $this->data = $data;
        $this->project = $project;
        $this->templateNewsletter = $templateNewsletter;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $configurations = $this->project->configurations;
        Config::set('mail.mailers.smtp.encryption', $configurations->mail_encryption);
        Config::set('mail.mailers.smtp.from.address', $configurations->mail_from);
        Config::set('mail.mailers.smtp.from.name', $configurations->project->name);
        Config::set('mail.mailers.smtp.host', $configurations->project->website);
        Config::set('mail.mailers.smtp.password', $configurations->mail_password);
        Config::set('mail.mailers.smtp.username', $configurations->mail_username);

        $html = DbView::make($this->templateNewsletter)->with($this->data['template_data'])->toHtml();

        Mail::send([], [], function ($message) use ($configurations, $html) {
            $message->from($configurations->mail_from);
            $message->to($this->data['email_to']);
            $message->subject($this->templateNewsletter->subject ?? '');
            $message->setBody($html, 'text/html');
        });


    }
}
