<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411083450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shopping_cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_user_id INTEGER NOT NULL, id_product_id INTEGER NOT NULL, nb_product INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_72AAD4F679F37AE5 ON shopping_cart (id_user_id)');
        $this->addSql('CREATE INDEX IDX_72AAD4F6E00EE68D ON shopping_cart (id_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shopping_cart');
    }
}
