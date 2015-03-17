<?php

namespace Innova\MediaResourceBundle\Migrations\ibm_db2;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/12 09:23:56
 */
class Version20150312092354 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            DROP FOREIGN KEY FK_BB5F60D066C02C1E
        ");
        $this->addSql("
            DROP INDEX IDX_BB5F60D066C02C1E
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD COLUMN media_resource_id VARCHAR(255) NOT NULL WITH DEFAULT 
            DROP COLUMN mediaResource_id
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_region'
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP FOREIGN KEY FK_5C0330F066C02C1E
        ");
        $this->addSql("
            DROP INDEX IDX_5C0330F066C02C1E
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD COLUMN media_resource_id VARCHAR(255) NOT NULL WITH DEFAULT 
            DROP COLUMN mediaResource_id
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_media'
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config ALTER COLUMN region_id 
            SET 
                NOT NULL
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_region_config'
            )
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD COLUMN mediaResource_id INTEGER DEFAULT NULL 
            DROP COLUMN media_resource_id
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_media'
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD CONSTRAINT FK_5C0330F066C02C1E FOREIGN KEY (mediaResource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F066C02C1E ON innova_media_resource_media (mediaResource_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD COLUMN mediaResource_id INTEGER NOT NULL WITH DEFAULT 
            DROP COLUMN media_resource_id
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_region'
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD CONSTRAINT FK_BB5F60D066C02C1E FOREIGN KEY (mediaResource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            CREATE INDEX IDX_BB5F60D066C02C1E ON innova_media_resource_region (mediaResource_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config ALTER COLUMN region_id 
            DROP NOT NULL
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_region_config'
            )
        ");
    }
}