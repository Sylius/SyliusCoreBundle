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

namespace Sylius\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMySqlMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203115813 extends AbstractMySqlMigration
{
    public function getDescription(): string
    {
        return 'Add messenger transport table for doctrine transports.';
    }

    public function up(Schema $schema): void
    {
        if ($this->isUsingDoctrineTransport() && !$schema->hasTable('messenger_messages')) {
            $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
    }

    public function down(Schema $schema): void
    {
    }

    private function isUsingDoctrineTransport(): bool
    {
        return array_key_exists('MESSENGER_TRANSPORT_DSN', $_ENV) && str_contains($_ENV['MESSENGER_TRANSPORT_DSN'], 'doctrine');
    }
}
