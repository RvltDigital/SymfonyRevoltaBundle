<?php

namespace RvltDigital\SymfonyRevoltaBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FixApacheAuthorizationHeaderListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['addAuthorizationHeader', 9],
        ];
    }

    public function addAuthorizationHeader(GetResponseEvent $event)
    {
        $headers = $event->getRequest()->headers;
        if (!$headers->has('Authorization') && function_exists('getallheaders')) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $rawHeaders = getallheaders();
            foreach ($rawHeaders as $key => $value) {
                if (strtolower($key) === 'authorization') {
                    $headers->set('Authorization', $value);
                }
            }
        }
    }
}
