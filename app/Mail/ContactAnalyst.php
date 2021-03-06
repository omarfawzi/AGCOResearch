<?php

namespace App\Mail;

use App\Models\SQL\Analyst;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\View\View;

class ContactAnalyst extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Analyst $analyst */
    protected $analyst;

    /** @var View $analystView */
    protected $analystView;

    /**
     * Create a new message instance.
     *
     * @param Analyst $analyst
     */
    public function __construct(Analyst $analyst, View $analystView)
    {
        $this->analyst     = $analyst;
        $this->analystView = $analystView;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->analystView->getData();

        $title = "Contact Analysis _ ".$data['report_title'];

        return $this->subject($title)->view(
            $this->analystView->getName(),
            array_merge(
                $data,
                [
                    'analyst' => $this->analyst,
                ])
        );
    }
}
