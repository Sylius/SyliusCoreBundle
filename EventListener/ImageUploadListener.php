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
use Symfony\Component\EventDispatcher\GenericEvent;
use Sylius\Bundle\AssortmentBundle\Model\CustomizableProductInterface;

class ImageUploadListener
{
    protected $uploader;

    public function __construct(ImageUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function upload(GenericEvent $event)
    {
        $product = $event->getSubject();
        if (!$product instanceof CustomizableProductInterface) {
            throw new \InvalidArgumentException('CustomizableProductInterface expected.');
        }

        foreach ($product->getMasterVariant()->getImages() as $image) {
            if (null === $image->getId()) {
                $this->uploader->upload($image);
            }
        }
    }
}
