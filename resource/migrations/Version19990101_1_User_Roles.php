<?php
namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version19990101_1_User_Roles extends AbstractMigration
{
    /**
     *  Run the migrations
     */
    public function up(Schema $schema)
    {
        $this->createTable();
        $this->createData();
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

CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`name`);

ALTER TABLE `user_roles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

EOD;
        $this->addSql($sql);

    }

    /**
     *  不存在就新增資料
     *  基於資料留存的概念, 我們不能在 down() 的時候刪除資料
     *  所以 這裡的做法做了修正
     *  如果資料已存在, 就做一個 無意義的資料更新 行為
     */
    private function createData()
    {
        $sql = <<<EOD
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (1,  'normal',    'Normal Role'   ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (2,  'manager',   'Manager Role'  ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (3,  'developer', 'Developer Role') ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (4,  'type-4',    'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (5,  'type-5',    'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (6,  'type-6',    'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (7,  'type-7',    'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (8,  'type-8',    'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (9,  'type-9',    'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (10, 'type-10',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (11, 'type-11',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (12, 'type-12',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (13, 'type-13',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (14, 'type-14',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (15, 'type-15',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (16, 'type-16',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (17, 'type-17',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (18, 'type-18',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (19, 'type-19',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;
            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (20, 'type-20',   'Empty'         ) ON DUPLICATE KEY UPDATE description = description;

            INSERT INTO `user_roles` (`id`, `name`, `description`) VALUES (21, 'login',     'Login'         ) ON DUPLICATE KEY UPDATE description = description;
EOD;
        $this->addSql($sql);
    }

}
