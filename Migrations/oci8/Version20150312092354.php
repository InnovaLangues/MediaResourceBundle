<?php

namespace Innova\MediaResourceBundle\Migrations\oci8;

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
            DROP CONSTRAINT FK_BB5F60D066C02C1E
        ");
        $this->addSql("
            DROP INDEX IDX_BB5F60D066C02C1E
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD (
                media_resource_id VARCHAR2(255) NOT NULL
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            DROP (mediaResource_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP CONSTRAINT FK_5C0330F066C02C1E
        ");
        $this->addSql("
            DROP INDEX IDX_5C0330F066C02C1E
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD (
                media_resource_id VARCHAR2(255) NOT NULL
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP (mediaResource_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config MODIFY (
                region_id NUMBER(10) NOT NULL
            )
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD (
                mediaResource_id NUMBER(10) DEFAULT NULL NULL
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP (media_resource_id)
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
            ADD (
                mediaResource_id NUMBER(10) NOT NULL
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            DROP (media_resource_id)
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
            ALTER TABLE innova_media_resource_region_config MODIFY (
                region_id NUMBER(10) DEFAULT NULL NULL
            )
        ");
    }
}