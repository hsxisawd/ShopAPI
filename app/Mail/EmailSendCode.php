<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class EmailSendCode extends Mailable
{
    use Queueable, SerializesModels;
    protected $email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email=$email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //生成code，并发送到用户邮箱
        $code=rand(1000,9999);
        //缓存到邮箱
        Cache::put('email_code'.$this->email,$code,now()->addMinute(15));
        return $this->view('email.sendcode',['code'=>$code]);
    }
}
