<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260112000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create user, event and reservation tables';
    }

    public function up(Schema $schema): void
    {
        // Create user table
        $this->addSql('CREATE TABLE "user" (
            id SERIAL NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');

        // Create event table
        $this->addSql('CREATE TABLE event (
            id SERIAL NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            location VARCHAR(255) NOT NULL,
            capacity INT NOT NULL,
            PRIMARY KEY(id)
        )');

        // Create reservation table
        $this->addSql('CREATE TABLE reservation (
            id SERIAL NOT NULL,
            event_id INT NOT NULL,
            user_id INT NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_42C8495571F7E88B ON reservation (event_id)');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');

        // Add foreign keys
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495571F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C8495571F7E88B');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C84955A76ED395');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE "user"');
    }
}
