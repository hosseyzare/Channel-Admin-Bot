<?php

namespace App;

use core\DB\Db;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Nutgram;

class MyConversation extends Conversation {

    private $ChannelLink = "https://t.me/amozesheforex_com";
    private $privateChannelLink = "https://t.me/fdvasf";

    public function start(Nutgram $bot)
    {

        

        if($bot->getChatMember("@amozesheforex_com",$bot->chatId())->status == "left"){
            //not inside channel
            $bot->sendMessage("سلام✌🏻
به ربات ثبت نام «کانال آموزش کاربردی فارکس» خوش اومدید.❤️
            
🎖️ثبت نام در کانال به مدت محدود رایگان است🎖️
            
🧑🏻‍💻 برای شروع ثبت نام ابتدا در کانال زیر عضو بشید و روی دکمه【عضو شدم ✅】کلیک کنید.",[
                'reply_markup' => InlineKeyboardMarkup::make()
                    ->addRow(InlineKeyboardButton::make('🔰کانال عمومی آموزش فارکس🔰', url: $this->ChannelLink))
                    ->addRow(InlineKeyboardButton::make('عضو شدم ✅', callback_data: 'check')),
            ]);
            $this->next('checkIfJoined');
        }else{
            //Ask Names
            $bot->sendMessage("⚜️ ثبت‌ نام در کانال
            📌 لطفاً نام و نام خانوادگی خود را وارد کنید.");
            $this->next('askContact');
        }
        
    }

    public function checkIfJoined(Nutgram $bot)
    {

        $message = $bot->Message();
        
        if($bot->getChatMember("@amozesheforex_com",$bot->chatId())->status == "left"){
            //not inside channel
            $bot->sendMessage("🚨 شما هنوز عضو کانال نیستید.

⚠️ در آموزش ها از اخبار و اطلاعات این کانال هم استفاده می‌کنیم.
            
بعد از عضویت روی دکمه【عضو شدم ✅】کلیک کنید.",[
                'reply_markup' => InlineKeyboardMarkup::make()
                    ->addRow(InlineKeyboardButton::make('🔰کانال عمومی آموزش فارکس🔰', url: $this->ChannelLink))
                    ->addRow(InlineKeyboardButton::make('عضو شدم ✅', callback_data: 'L')),
            ]);
        }else{
            $bot->sendMessage("⚜️ ثبت‌ نام در کانال
            📌 لطفاً نام و نام خانوادگی خود را وارد کنید.");
            $this->next('askContact');
        }


         
    }

    public function askContact(Nutgram $bot)
    {
        $message = $bot->Message();
        if ($message->text !== null) {     
            $fullName = $message->text;
            $bot->setUserData('name', $fullName);
            
            $bot->sendMessage('⚜️ ثبت‌نام در کانال 
📌 لطفاً روی دکمه زیر کلیک کنید و شماره تماس خود را ارسال کنید.', [
                'reply_markup' => [
                    'keyboard' => [
                        [['text' => '☎️ ارسال شماره تماس ☎️', 'request_contact' => true]]
                    ],
                    'resize_keyboard' => true,
                ],
            ]);

            $this->next('submit');
        }else{
            $this->next('start');
        }
        
    }


    public function submit(Nutgram $bot)
    {
        $db = Db::getInstance();

        $message = $bot->Message();
        $contact = $message->contact;

        $acc_name = $bot->user()->first_name ?? "";
        $acc_lastname = $bot->user()->last_name ?? "";
        $acc_username = $bot->user()->username ?? "";
        $account_fullname = $acc_lastname . $acc_name;
        $name = $bot->getUserData('name');
        $chatId = $bot->chatId();
        $phone = $contact->phone_number;

        

        
        $db->insert("INSERT INTO users(username,account_name,fullname,chat_id,contact) VALUES('$acc_username','$account_fullname','$name','$chatId','$phone')");
        
        
        $links = $db->first("SELECT * FROM variables WHERE id=1");
        $prev_link = $links["private_link"];
        $bot->sendMessage("🥳 تبریک میگم، ثبت نام شما با موفقیت انجام شد.
⚠️ Link :  $prev_link
        ");

        $this->end();
    }


}