<?php
require_once (getcwd(). '/core/loader.php');
require_once (getcwd(). '/controller/MyConversation.php');



use App\MyConversation;
use core\DB\Db;
use GuzzleHttp\Client;
use bot\Hello;
use bot\BarodCast;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Webhook;
use SergiX44\Nutgram\Telegram\Attributes\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
use SergiX44\Nutgram\Telegram\Types\Media;



global $Text;


$psr6Cache = new FilesystemAdapter();
$psr16Cache = new Psr16Cache($psr6Cache);

$bot = new Nutgram($_ENV['BOT_TOKEN'],[
    'cache' => $psr16Cache,
    'enableHttp2' =>false,
]);
$bot->setRunningMode(Webhook::class);

// ::class

$bot->onCommand('start',function (Nutgram $bot){

    $db = Db::getInstance();
    $chat_id = $bot->chatId();
    if($db->first("SELECT * FROM users WHERE chat_id='$chat_id'") && $bot->getChatMember("@amozesheforex_com",$bot->chatId())->status !== "left"){
        $bot->sendMessage('⚠️ ⭕️  شما قبلا لینک را دریافت کردید  ...   ');
    }else{
        MyConversation::begin($bot); 
    }
    
});


$bot->onText('ChangePrevLink {link}', function (Nutgram $bot, $link) {
    $db = Db::getInstance();
    $adminList = [
        '249135462',
        '178985969',
        '85907323'
    ];
    if(in_array($bot->chatId(),$adminList)){
        $db->modify("UPDATE variables
        SET private_link = '$link'
        WHERE id = 1;
        ");
        $bot->sendMessage("New Link Submited ! link: {$link}");
    }else{
        $bot->sendMessage("You are not an admin user.");
    }

});




$bot->run(); // after this, the script continues execution



