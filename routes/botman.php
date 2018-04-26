<?php
use App\Http\Controllers\BotManController;
use Carbon\Carbon;

$botman = resolve('botman');

// $botman->hears('Hi', function ($bot) {
//     $bot->reply('Hello!');
// });

//$botman->hears('Start conversation', BotManController::class.'@startConversation');

//test
$botman->hears('/greet {text}', function($bot, $text){
    $bot->reply('Hola '.$text);
});

// fallback
// $botman->fallback(function($bot) {
//     $bot->reply('Perdon, no entiendo el comando que me mandaste..');
// });

// commands
$botman->hears('/mapa',  BotManController::class.'@sendLocation');

$botman->hears('/lanparty', BotManController::class.'@sendLanPartyAudio');

$botman->hears('/horarios', BotManController::class.'@startScheduleConversation');

$botman->hears('/test', function ($bot) {
    $agenda = json_decode(file_get_contents('https://campuse.ro/api/v2/events/campus-party-argentina-2018/schedule'));
    $first = $agenda->results[0];
    $bot->reply($first->title . " - ". Carbon::parse($first->start_date)->format('H:i') . " " . Carbon::parse($first->end_date)->format('H:i') . " - " . $first->stadium_name);
});