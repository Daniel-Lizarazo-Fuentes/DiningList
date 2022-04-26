<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425220332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD u_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart DROP status');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B76782F39A FOREIGN KEY (u_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B76782F39A ON cart (u_id_id)');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f52993982f3323f6');
        $this->addSql('DROP INDEX idx_f52993982f3323f6');
        $this->addSql('ALTER TABLE "order" ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP created_at');
        $this->addSql('ALTER TABLE "order" RENAME COLUMN cart_ref_id TO cart_id');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993981AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F52993981AD5CDBF ON "order" (cart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B76782F39A');
        $this->addSql('DROP INDEX UNIQ_BA388B76782F39A');
        $this->addSql('ALTER TABLE cart ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE cart DROP u_id_id');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993981AD5CDBF');
        $this->addSql('DROP INDEX IDX_F52993981AD5CDBF');
        $this->addSql('ALTER TABLE "order" ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE "order" DROP status');
        $this->addSql('ALTER TABLE "order" RENAME COLUMN cart_id TO cart_ref_id');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f52993982f3323f6 FOREIGN KEY (cart_ref_id) REFERENCES cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f52993982f3323f6 ON "order" (cart_ref_id)');
    }
}
