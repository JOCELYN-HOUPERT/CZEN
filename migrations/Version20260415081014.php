<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260415081014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_ressource (user_id INT NOT NULL, ressource_id INT NOT NULL, INDEX IDX_937FC8A0A76ED395 (user_id), INDEX IDX_937FC8A0FC6CD52A (ressource_id), PRIMARY KEY (user_id, ressource_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_ressource ADD CONSTRAINT FK_937FC8A0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_ressource ADD CONSTRAINT FK_937FC8A0FC6CD52A FOREIGN KEY (ressource_id) REFERENCES ressource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD questionnaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494ECE07E8FF ON question (questionnaire_id)');
        $this->addSql('ALTER TABLE resultat_diagnostic ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE resultat_diagnostic ADD CONSTRAINT FK_B5C39CF3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B5C39CF3A76ED395 ON resultat_diagnostic (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_ressource DROP FOREIGN KEY FK_937FC8A0A76ED395');
        $this->addSql('ALTER TABLE user_ressource DROP FOREIGN KEY FK_937FC8A0FC6CD52A');
        $this->addSql('DROP TABLE user_ressource');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ECE07E8FF');
        $this->addSql('DROP INDEX IDX_B6F7494ECE07E8FF ON question');
        $this->addSql('ALTER TABLE question DROP questionnaire_id');
        $this->addSql('ALTER TABLE resultat_diagnostic DROP FOREIGN KEY FK_B5C39CF3A76ED395');
        $this->addSql('DROP INDEX IDX_B5C39CF3A76ED395 ON resultat_diagnostic');
        $this->addSql('ALTER TABLE resultat_diagnostic DROP user_id');
    }
}
