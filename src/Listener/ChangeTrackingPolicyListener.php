<?php

namespace RvltDigital\SymfonyRevoltaBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use RvltDigital\StaticDiBundle\StaticDI;

class ChangeTrackingPolicyListener implements EventSubscriber
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

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        try {
            StaticDI::getInstance();
        } catch (\LogicException $e) {
            return;
        }
        $configValue = StaticDI::getParameter('rvlt_digital_revolta.change_tracking_policy');
        if (is_null($configValue)) {
            return;
        }
        switch ($configValue) {
            case 'implicit':
                $policy = ClassMetadataInfo::CHANGETRACKING_DEFERRED_IMPLICIT;
                break;
            case 'explicit':
                $policy = ClassMetadataInfo::CHANGETRACKING_DEFERRED_EXPLICIT;
                break;
            case 'notify':
                $policy = ClassMetadataInfo::CHANGETRACKING_NOTIFY;
                break;
            default:
                throw new \UnexpectedValueException('The config value must be one of "implicit", "explicit" or "notify"');
        }
        $classMetadata = $args->getClassMetadata();
        if (!$classMetadata instanceof ClassMetadataInfo) {
            throw new \LogicException('The class metadata must be instance of ' . ClassMetadataInfo::class);
        }
        $classMetadata->setChangeTrackingPolicy($policy);
    }
}
