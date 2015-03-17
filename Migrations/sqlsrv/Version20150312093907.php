<?php

namespace Innova\MediaResourceBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/12 09:39:09
 */
class Version20150312093907 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_region ALTER COLUMN media_resource_id INT NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD CONSTRAINT FK_BB5F60D07E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            CREATE INDEX IDX_BB5F60D07E5AEFB6 ON innova_media_resource_region (media_resource_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media ALTER COLUMN media_resource_id INT NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD CONSTRAINT FK_5C0330F07E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F07E5AEFB6 ON innova_media_resource_media (media_resource_id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP CONSTRAINT FK_5C0330F07E5AEFB6
        ");
        $this->addSql("
            IF EXISTS (
                SELECT * 
                FROM sysobjects 
                WHERE name = 'IDX_5C0330F07E5AEFB6'
            ) 
            ALTER TABLE innova_media_resource_media 
            DROP CONSTRAINT IDX_5C0330F07E5AEFB6 ELSE 
            DROP INDEX IDX_5C0330F07E5AEFB6 ON innova_media_resource_media
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media ALTER COLUMN media_resource_id NVARCHAR(255) COLLATE utf8_unicode_ci NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            DROP CONSTRAINT FK_BB5F60D07E5AEFB6
        ");
        $this->addSql("
            IF EXISTS (
                SELECT * 
                FROM sysobjects 
                WHERE name = 'IDX_BB5F60D07E5AEFB6'
            ) 
            ALTER TABLE innova_media_resource_region 
            DROP CONSTRAINT IDX_BB5F60D07E5AEFB6 ELSE 
            DROP INDEX IDX_BB5F60D07E5AEFB6 ON innova_media_resource_region
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region ALTER COLUMN media_resource_id NVARCHAR(255) COLLATE utf8_unicode_ci NOT NULL
        ");
    }
}