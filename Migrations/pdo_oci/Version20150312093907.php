<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_oci;

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
            ALTER TABLE innova_media_resource_region MODIFY (
                media_resource_id NUMBER(10) DEFAULT NULL
            )
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
            ALTER TABLE innova_media_resource_media MODIFY (
                media_resource_id NUMBER(10) DEFAULT NULL
            )
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
            DROP INDEX IDX_5C0330F07E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media MODIFY (
                media_resource_id VARCHAR2(255) DEFAULT NULL
            )
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            DROP CONSTRAINT FK_BB5F60D07E5AEFB6
        ");
        $this->addSql("
            DROP INDEX IDX_BB5F60D07E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region MODIFY (
                media_resource_id VARCHAR2(255) DEFAULT NULL
            )
        ");
    }
}