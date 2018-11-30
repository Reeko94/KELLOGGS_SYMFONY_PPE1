<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181130094459 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE compose(quantite int(10) not null, idFacture int(11) not null,idArticle int(11) not null)');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_BFDD3168E9D518F9 FOREIGN KEY (idFacture) REFERENCES factures (id)');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_BFDD3168E9D518F8 FOREIGN KEY (idArticle) REFERENCES articles (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compose DROP FOREIGN KEY FK_BFDD3168E9D518F9');
        $this->addSql('ALTER TABLE compose DROP FOREIGN KEY FK_BFDD3168E9D518F8');
        $this->addSql('DROP TABLE compose');
    }
}
