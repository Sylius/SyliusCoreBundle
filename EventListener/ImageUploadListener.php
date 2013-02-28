<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\EventListener;

use Sylius\Bundle\CoreBundle\Uploader\ImageUploaderInterface;
use Sylius\Bundle\CoreBundle\Model\ImageOwnerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ImageUploadListener
{
    protected $uploader;

    public function __construct(ImageUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function upload(GenericEvent $event)
    {
        $owner = $event->getSubject()->getMasterVariant();
        if (!$owner instanceof ImageOwnerInterface) {
            throw new \InvalidArgumentException(sprintf(
                'Got %s ImageOwnerInterface expected.', get_class($owner)
            ));
        }

        $this->uploader->uploadAll($owner);
    }
}
