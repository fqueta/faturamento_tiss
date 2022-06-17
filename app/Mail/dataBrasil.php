<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use stdClass;

class dataBrasil extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    public function __construct(stdClass $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Novo teste');
        $this->to($this->user->email,$this->user->name);
        return $this->markdown('mail.dataBrasil',[
            'user'=>$this->user,
        ]);
    }
}
