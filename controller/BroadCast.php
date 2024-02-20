<?php
namespace bot;

use SergiX44\Nutgram\Nutgram;

class BroadCast{
    
    public function handle(Nutgram $bot)
    {
        $db = Db::getInstance();

        $records = $db->query("select chat_id from config_test");
        foreach($records as $record){
            $chatId = $record['chat_id'];
            $bot->sendMessage("$chatId");
        }
    }
}