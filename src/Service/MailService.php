<?php

namespace RvltDigital\SymfonyRevoltaBundle\Service;

use Exception;
use Swift_Message;
use RvltDigital\StaticDiBundle\StaticDI;

class MailService
{
    /**
     * send html mail
     *
     * @param string $subject
     *
     * @param string $to
     * @param string $body
     * @param null|string $fromEmail
     * @param null|string $fromName
     * @param null|string $contentType
     * @param string|null $replyTo
     * @param string|null $replyToName
     * @throws Exception
     */
    public function send(
        string $subject,
        string $to,
        string $body,
        ?string $fromEmail = null,
        ?string $fromName = null,
        ?string $contentType = 'text/html',
        ?string $replyTo = null,
        ?string $replyToName = null
    ) {
        /** @var \Swift_Mailer $mailer */
        $mailer = StaticDI::get('mailer');

        if (!$fromEmail) {
            $fromEmail = StaticDI::getParameter('rvlt_digital_revolta.mailer')['default_email'] ?? null;
        }
        if (!$fromName) {
            $fromName = StaticDI::getParameter('rvlt_digital_revolta.mailer')['default_name'] ?? null;
        }
        if ($replyTo === null) {
            $replyTo = StaticDI::getParameter('rvlt_digital_revolta.mailer')['default_reply_to_email'] ?? null;
        }
        if ($replyToName === null) {
            $replyToName = StaticDI::getParameter('rvlt_digital_revolta.mailer')['default_reply_to_name'] ?? null;
        }
        if ($fromEmail === null || $fromName === null) {
            throw new Exception('There is missing default email or default name parameter!');
        }

        $toArray = preg_split("/[\s,;]+/", $to);

        $message = (new Swift_Message($subject))
            ->setFrom($fromEmail, $fromName)
            ->setTo($toArray)
            ->setBody($body, $contentType);

        if ($replyTo !== null) {
            $message->setReplyTo($replyTo, $replyToName);
        }

        if (!$mailer->send($message)) {
            throw new Exception(sprintf(
                'Send mail failure: Subject: %s, From: %s <%s>, To: %s, Body: %s',
                $subject,
                $fromName,
                $fromEmail,
                $to,
                $body
             ));
        }
    }
}
