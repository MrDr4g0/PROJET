<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419091233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, login, password, name, first_name, date, is_admin, is_super_admin FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, birth_date DATE NOT NULL, is_admin BOOLEAN NOT NULL, is_super_admin BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO user (id, login, password, name, first_name, birth_date, is_admin, is_super_admin) SELECT id, login, password, name, first_name, date, is_admin, is_super_admin FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL COLLATE BINARY, price DOUBLE PRECISION NOT NULL, stock INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE shopping_cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user_id INTEGER NOT NULL, id_product_id INTEGER NOT NULL, nb_product INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_72AAD4F6E00EE68D ON shopping_cart (id_product_id)');
        $this->addSql('CREATE INDEX IDX_72AAD4F679F37AE5 ON shopping_cart (id_user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, login, password, name, first_name, birth_date, is_admin, is_super_admin FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, date DATE NOT NULL, is_admin BOOLEAN NOT NULL, is_super_admin BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO user (id, login, password, name, first_name, date, is_admin, is_super_admin) SELECT id, login, password, name, first_name, birth_date, is_admin, is_super_admin FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
