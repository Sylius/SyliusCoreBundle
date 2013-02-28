<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Uploader;

use Sylius\Bundle\CoreBundle\Model\ImageInterface;
use Sylius\Bundle\CoreBundle\Model\ImageOwnerInterface;

interface ImageUploaderInterface
{
    public function upload(ImageInterface $image);
    public function uploadAll(ImageOwnerInterface $owner);
}
