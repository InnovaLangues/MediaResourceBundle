<?php

namespace Innova\MediaResourceBundle\Migrations\drizzle_pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/04/24 09:32:09
 */
class Version20150424093208 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            DROP FOREIGN KEY FK_DC6F27BD6BBD148
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            ADD CONSTRAINT FK_DC6F27BD6BBD148 FOREIGN KEY (playlist_id) 
            REFERENCES innova_media_resource_playlist (id) 
            ON DELETE CASCADE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            DROP FOREIGN KEY FK_DC6F27BD6BBD148
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            ADD CONSTRAINT FK_DC6F27BD6BBD148 FOREIGN KEY (playlist_id) 
            REFERENCES innova_media_resource_playlist (id)
        ");
    }
}