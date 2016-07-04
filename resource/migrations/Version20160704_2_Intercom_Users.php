<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20160704_2_Intercom_Users extends AbstractMigration
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
CREATE TABLE IF NOT EXISTS `intercom_users` (
  `id` int(10) unsigned NOT NULL,
  `intercom_app_id` int(10) unsigned NOT NULL,
  `item_id` varchar(36) NOT NULL,
  `item_user_id` varchar(36) NOT NULL,
  `email` varchar(100) NOT NULL,
  `origin_content` text NOT NULL,
  `properties` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '1970-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '1970-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `intercom_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_id` (`item_id`) USING BTREE;

ALTER TABLE `intercom_users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
EOD;

        $this->addSql($sql);
    }

}

