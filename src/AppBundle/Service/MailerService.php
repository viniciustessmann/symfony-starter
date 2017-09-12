<?php

namespace AppBundle\Service;

class MailerService
{

    protected $mailer;

    public function __construct($em, $encoder, $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $subject, $content)
    {   

        $message = (new \Swift_Message('Hello Email'))
            ->setSubject('[WebSite]')
            ->setFrom($email)
            ->setTo($email)
            ->setBody($content, 'text/html');

        $this->mailer->send($message);
    }
}
