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

    public function send($payload)
    {
        try {
            $email = (new Email())
                ->from($payload['from'])
                ->to($payload['to'])
                ->subject($payload['subject'])
                ->text($payload['text'])
                ->html($payload['html']);

            $this->mailer->send($email);

        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
    }
}