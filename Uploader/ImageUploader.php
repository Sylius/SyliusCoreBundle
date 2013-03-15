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
use Gaufrette\Filesystem;

class ImageUploader implements ImageUploaderInterface
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function upload(ImageInterface $image)
    {
        if (!$image->hasFile()) {
            throw new \InvalidArgumentException('The given image has no file attached.');
        }

        do {
            $hash = md5(uniqid(mt_rand(), true));
            $path = $this->expandPath($hash.'.'.$image->getFile()->guessExtension());
        } while ($this->filesystem->has($path));

        $image->setPath($path);

        $this->filesystem->write(
            $image->getPath(),
            file_get_contents($image->getFile()->getPathname())
        );
    }

    private function expandPath($path)
    {
        return sprintf(
            '%s/%s/%s',
            substr($path, 0, 2),
            substr($path, 2, 2),
            substr($path, 4)
        );
    }
}
