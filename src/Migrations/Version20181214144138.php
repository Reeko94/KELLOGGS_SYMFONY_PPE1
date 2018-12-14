<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181214144138 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('DROP TABLE medias;');
        $this->addSql('DROP TABLE nouveautes;');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("CREATE TABLE `medias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_media` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
");
        $this->addSql("CREATE TABLE `nouveautes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` datetime NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
    }
}
