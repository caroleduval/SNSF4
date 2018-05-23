<?php

namespace App\DoctrineListener;

use App\Domain\Model\Video;
use App\Service\IframeBuilder;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class VideoCreationListener
{
    /**
     * @var IframeBuilder
     */
    private $iframeBuilder;

    public function __construct(IframeBuilder $iframeBuilder)
    {
        $this->iframeBuilder = $iframeBuilder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Video) {
            return;
        }

        $this->iframeBuilder->analyseUrl($entity);
    }
}
