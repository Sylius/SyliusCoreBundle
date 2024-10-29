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

namespace Sylius\Bundle\CoreBundle\DependencyInjection\Compiler;

use Sylius\Bundle\CoreBundle\Twig\FilterExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

trigger_deprecation(
    'sylius/sylius',
    '1.14',
    'The "%s" class is deprecated and will be removed in Sylius 2.0.',
    LiipImageFiltersPass::class,
);
/**
 * @internal
 */
final class LiipImageFiltersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $filterExtension = $container->getDefinition('liip_imagine.templating.filter_extension');

        $svgAwareFilterExtensionDefinition = new Definition(FilterExtension::class);
        $svgAwareFilterExtensionDefinition->setArguments([
            '/media/image/',
            new Reference('liip_imagine.cache.manager'),
        ]);
        $svgAwareFilterExtensionDefinition->addTag('twig.extension');
        $container->setDefinition('liip_imagine.templating.filter_extension', $svgAwareFilterExtensionDefinition);
    }
}
