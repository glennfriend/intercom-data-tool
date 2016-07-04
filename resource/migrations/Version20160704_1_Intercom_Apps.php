<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160704_1_Intercom_Apps extends AbstractMigration
{
    /**
     *  Run the migrations
     */
    public function up(Schema $schema)
    {
        $this->createTable();
    }

    /**
     *  Reverse the migrations.
     */
    public function down(Schema $schema)
    {
        // 不予許 drop table
    }

    // --------------------------------------------------------------------------------
    //  private
    // --------------------------------------------------------------------------------
    private function createTable()
    {
        $sql = <<<EOD
CREATE TABLE IF NOT EXISTS `intercom_apps` (
  `id` int(10) unsigned NOT NULL,
  `account` varchar(40) NOT NULL,
  `properties` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `intercom_apps`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `intercom_apps`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
EOD;

        $this->addSql($sql);
    }

}
