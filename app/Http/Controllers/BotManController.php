<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Attachments\Location;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Attachments\Audio;

class BotManController extends Controller
{
    // emoji codes consts
    const EARTH = "\u{1F30F}";
    const GRINNING_FACE = "\u{1F601}";
    
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }

    public function sendLocation(BotMan $bot)
    {
        // Make bot more human like
        $bot->typesAndWaits(2);

        $bot->reply('Esta es la dirección de Tecnópolis ' . self::EARTH . self::GRINNING_FACE);

        // Create attachment
        $attachment = new Location(-34.5614827695827, -58.50762742329734, [
            'custom_payload' => true,
        ]);
        
        // Build message object
        $message = OutgoingMessage::create()
                    ->withAttachment($attachment);
        
        // Reply message object
        $bot->reply($message);
    }

    public function sendLanPartyAudio(BotMan $bot)
    {
        // Create attachment
        $attachment = new Audio(url('/audio/no-lei-nada-sale-lan-party.ogg'), [
            'custom_payload' => true,
        ]);
        
        // Build message object
        $message = OutgoingMessage::create('SALE LAN PARTY!!')
                    ->withAttachment($attachment);
        
        // Reply message object
        $bot->reply($message);
    }
}