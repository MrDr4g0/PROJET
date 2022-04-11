<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411080139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shopping_cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nb_product INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE shopping_cart_product (shopping_cart_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(shopping_cart_id, product_id))');
        $this->addSql('CREATE INDEX IDX_FA1F5E6C45F80CD ON shopping_cart_product (shopping_cart_id)');
        $this->addSql('CREATE INDEX IDX_FA1F5E6C4584665A ON shopping_cart_product (product_id)');
        $this->addSql('CREATE TABLE shopping_cart_user (shopping_cart_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(shopping_cart_id, user_id))');
        $this->addSql('CREATE INDEX IDX_7712EEA345F80CD ON shopping_cart_user (shopping_cart_id)');
        $this->addSql('CREATE INDEX IDX_7712EEA3A76ED395 ON shopping_cart_user (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('DROP TABLE shopping_cart_product');
        $this->addSql('DROP TABLE shopping_cart_user');
    }
}
