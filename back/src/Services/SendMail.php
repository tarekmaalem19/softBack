<?php


namespace App\Services;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendMail
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

   public function send($message, $reciver) {
       $email = (new Email())
           ->from('hello@example.com')
           ->to('you@example.com')
           //->cc('cc@example.com')
           //->bcc('bcc@example.com')
           //->replyTo('fabien@example.com')
           //->priority(Email::PRIORITY_HIGH)
           ->subject('Time for Symfony Mailer!')
           ->text('Sending emails is fun again!')
           ->html('<p>See Twig integration for better HTML integration!</p>');

       $this->mailer->send($email);
   }
}