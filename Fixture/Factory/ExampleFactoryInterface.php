<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Fixture\Factory;

/** @template T of object */
interface ExampleFactoryInterface
{
    /**
     * @param array<string, mixed> $options
     *
     * @return T
     */
    public function create(array $options = []);
}
