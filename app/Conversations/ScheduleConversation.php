<?php

namespace App\Conversations;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ScheduleConversation extends Conversation
{
    /**
     * First question
     */
    public function askWhatSchedule()
    {
        $question = Question::create("Horario de que quisieras saber?")
            ->fallback('Houston tenemos un problema')
            ->callbackId('ask_what_schedule')
            ->addButtons([
                Button::create('Acreditacion Carpas')->value('carpas'),
                Button::create('Acreditacion Open Campus')->value('open-campus'),
                Button::create('Agenda')->value('agenda')
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                if ($answer->getValue() === 'carpas') {
                    $message = 'Las carpas se acreditan a partir de las 12:00hs del dia Miercoles';
                    $this->say($message);
                } else if ($answer->getValue() === 'open-campus') {
                    $message = 'El Open Campus abre sus puertas a partir de las 14:00hs del dia Miercoles';
                    $this->say($message);
                } else {
                    $agenda = json_decode(file_get_contents('https://campuse.ro/api/v2/events/campus-party-argentina-2018/schedule'));
                    $this->say($agenda);
                }
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askWhatSchedule();
    }
}
