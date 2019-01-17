<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181206123443 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql('ALTER TABLE compose ADD PRIMARY KEY (id_facture, id_article)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compose DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE compose ADD idFacture INT NOT NULL, ADD idArticle INT NOT NULL, DROP id_facture, DROP id_article');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_BFDD3168E9D518F8 FOREIGN KEY (idArticle) REFERENCES articles (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_BFDD3168E9D518F9 FOREIGN KEY (idFacture) REFERENCES factures (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX FK_BFDD3168E9D518F8 ON compose (idArticle)');
        $this->addSql('CREATE INDEX FK_BFDD3168E9D518F9 ON compose (idFacture)');
    }
}
