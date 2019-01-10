<?php

namespace RvltDigital\SymfonyRevoltaBundle\Listeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

class DoctrineMetaDataListener implements EventSubscriber
{

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getName();

        $prePersist = $metadata->getLifecycleCallbacks(Events::prePersist);
        $preUpdate = $metadata->getLifecycleCallbacks(Events::preUpdate);

        if (
            method_exists($class, 'setCreatedUpdated') &&
            method_exists($class, 'setUpdated')
        ) {
            if (!in_array('setCreatedUpdated', $prePersist)) {
                $metadata->addLifecycleCallback('setCreatedUpdated', Events::prePersist);
            }
            if (!in_array('setUpdated', $preUpdate)) {
                $metadata->addLifecycleCallback('setUpdated', Events::preUpdate);
            }
        }
    }
}
