<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Tests\Fixture;

use Doctrine\Persistence\ObjectManager;
use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\CoreBundle\Fixture\CatalogPromotionFixture;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;

final class CatalogPromotionFixtureTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /** @test */
    public function catalog_promotions_are_optional(): void
    {
        $this->assertConfigurationIsValid([[]], 'custom');
    }

    /** @test */
    public function catalog_promotions_can_be_generated_randomly(): void
    {
        $this->assertConfigurationIsValid([['random' => 4]], 'random');
        $this->assertPartialConfigurationIsInvalid([['random' => -1]], 'random');
    }

    /** @test */
    public function catalog_promotion_code_can_be_set(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['code' => 'CODE']]]], 'custom.*.code');
    }

    /** @test */
    public function catalog_promotion_name_can_be_set(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['name' => 'Name']]]], 'custom.*.name');
    }

    /** @test */
    public function catalog_promotion_description_can_be_set(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['description' => 'Description']]]], 'custom.*.description');
    }

    /** @test */
    public function catalog_promotion_channels_can_be_set(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['channels' => ['first_channel', 'second_channel']]]]], 'custom.*.channels');
    }

    /** @test */
    public function catalog_promotion_rules_can_be_set(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['rules' => [['type' => 'some_rule_type', 'configuration' => ['first_variant', 'second_variant']]]]]]], 'custom.*.rules');
    }

    /** @test */
    public function catalog_promotion_actions_can_be_set(): void
    {
        $this->assertConfigurationIsValid([['custom' => [['actions' => [['type' => 'some_action_type', 'configuration' => ['amount' => 500]]]]]]], 'custom.*.actions');
    }

    protected function getConfiguration(): CatalogPromotionFixture
    {
        return new CatalogPromotionFixture(
            $this->getMockBuilder(ObjectManager::class)->getMock(),
            $this->getMockBuilder(ExampleFactoryInterface::class)->getMock()
        );
    }
}
