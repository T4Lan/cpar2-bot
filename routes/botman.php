<?php
use App\Http\Controllers\BotManController;

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