<?php

namespace Innova\MediaResourceBundle\Migrations\sqlanywhere;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/17 02:09:14
 */
class Version20150317140912 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INT IDENTITY NOT NULL, 
                media_resource_id INT NOT NULL, 
                \"start\" DOUBLE PRECISION NOT NULL, 
                \"end\" DOUBLE PRECISION NOT NULL, 
                note TEXT DEFAULT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_BB5F60D07E5AEFB6 ON innova_media_resource_region (media_resource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id INT IDENTITY NOT NULL, 
                media_resource_id INT NOT NULL, 
                url VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F07E5AEFB6 ON innova_media_resource_media (media_resource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id INT IDENTITY NOT NULL, 
                region_id INT NOT NULL, 
                help_region_id INT DEFAULT NULL, 
                has_loop BIT NOT NULL, 
                has_backward BIT NOT NULL, 
                has_rate BIT NOT NULL, 
                help_text VARCHAR(255) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198260155 ON innova_media_resource_region_config (region_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198A93AD1 ON innova_media_resource_region_config (help_region_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource (
                id INT IDENTITY NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                resourceNode_id INT DEFAULT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_AC4F9D03B87FAB32 ON innova_media_resource (resourceNode_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD CONSTRAINT FK_BB5F60D07E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD CONSTRAINT FK_5C0330F07E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD CONSTRAINT FK_FEBE556198260155 FOREIGN KEY (region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD CONSTRAINT FK_FEBE556198A93AD1 FOREIGN KEY (help_region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD CONSTRAINT FK_AC4F9D03B87FAB32 FOREIGN KEY (resourceNode_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE CASCADE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP FOREIGN KEY FK_FEBE556198260155
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP FOREIGN KEY FK_FEBE556198A93AD1
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            DROP FOREIGN KEY FK_BB5F60D07E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP FOREIGN KEY FK_5C0330F07E5AEFB6
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region_config
        ");
        $this->addSql("
            DROP TABLE innova_media_resource
        ");
    }
}