<?php

namespace RvltDigital\SymfonyRevoltaBundle\Service;

use DOMDocument;
use Exception;
use Swift_Image;
use Swift_Mailer;
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
     * @param bool|null $convertInlineImages
     *
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
        ?string $replyToName = null,
        ?bool $convertInlineImages = false
    ) {
        /** @var Swift_Mailer $mailer */
        $mailer = StaticDI::get('mailer');
        $mailerConfig = StaticDI::getParameter('rvlt_digital_revolta.mailer');

        if (!$fromEmail) {
            $fromEmail = $mailerConfig['default_email'] ?? null;
        }
        if (!$fromName) {
            $fromName = $mailerConfig['default_name'] ?? null;
        }
        if ($replyTo === null) {
            $replyTo = $mailerConfig['default_reply_to_email'] ?? null;
        }
        if ($replyToName === null) {
            $replyToName = $mailerConfig['default_reply_to_name'] ?? null;
        }
        if ($fromEmail === null || $fromName === null) {
            throw new Exception('There is missing default email or default name parameter!');
        }

        $toArray = preg_split("/[\s,;]+/", $to);

        $message = (new Swift_Message($subject))
            ->setFrom($fromEmail, $fromName)
            ->setTo($toArray)
            ->setBody($body, $contentType);

        if ($convertInlineImages) {
            $this->convertBodyInlineImagesToAttachments($message);
        }

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

    /**
     * Convert body inline images (<img src="data:...;base64,...) to inline attachments,
     * only body contentType='text/html' will be converted.
     *
     * @param Swift_Message $message
     */
    protected function convertBodyInlineImagesToAttachments(Swift_Message $message)
    {
        if ($message->getBodyContentType() !== 'text/html') {
            return;
        }

        $html = $message->getBody();
        if (!empty($html)) {
            $dom = new DOMDocument('1.0');
            $dom->loadHTML($html);

            $images = $dom->getElementsByTagName('img');
            foreach ($images as $image) {
                $src = $image->getAttribute('src');
                if (strpos($src, ";base64,")) {
                    //data:...;base64,.....
                    list($type, $data) = explode(";base64,", $src);
                    $type = str_ireplace('data:', '', $type);
                    $entity = new Swift_Image(base64_decode($data), null, $type);
                    $message->setChildren(array_merge($message->getChildren(), [$entity]));
                    $image->setAttribute('src', 'cid:' . $entity->getId());
                }
            }

            $message->setBody($dom->saveHTML($dom->documentElement), 'text/html');
        }
    }
}
