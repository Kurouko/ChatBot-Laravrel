<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class BotManController extends Controller
{
    public function handle()
    {
        $botman = app('botman');

        $botman->hears('.*', function ($botman) {
            $this->startConversation($botman);
        });

        $botman->listen();
    }

    public function startConversation($botman)
    {
        $botman->startConversation(new AskNameConversation());
    }
}
    /**
     * Ask the user for their name when they say 'hi'.
     */
    class AskNameConversation extends Conversation
    {
        protected $name;

        public function askName()
        {
            $this->ask('Hello! What is your name?', function (Answer $answer) {
                $this->name = $answer->getText();
                $this->askEmail();
            });
        }

        public function askEmail()
        {
            $this->ask('Great, thanks ' . $this->name . '! What is your email address?', function (Answer $answer) {
                $email = $answer->getText();
                $this->say('Thank you, we will reach you as soon as possible ');
            });
        }

        public function run()
        {
            $this->askName();
        }
    }
