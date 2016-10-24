<?php

namespace App\Listeners;

use App\Events\EvaluationStart;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailEvalTest implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EvaluationStart  $event
     * @return void
     */
    public function handle(EvaluationStart $event)
    {
        //
        $email = $event->data['email'];
        $name = $event->data['name'];
        $evaluationName = $event->['evaluationName'];

        Mail::send('evalMail', $event->data, function ($message) use ($email, $name, $evaluationName) {
            $message->to($email, $name)->subject('Welcome To '.$evaluationName.' Evaluation Test!');
        });
    }
}
