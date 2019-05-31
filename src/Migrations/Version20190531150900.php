<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190531150900 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE VIEW fabricantstypenutrition  AS  select `f`.`id` AS `id`,`f`.`libelle` AS `libelle`,`f`.`logo` AS `logo`,`t`.`libelle` AS `typeNutrition`,`t`.`id` AS `tid` from (`fabricants` `f` join `type_nutrition` `t`) where (`f`.`type_nutrition_id` = `t`.`id`) ;');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP VIEW fabricantstypenutrition');
    }
}
