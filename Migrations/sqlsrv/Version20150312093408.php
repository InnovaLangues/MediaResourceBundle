<?php

namespace Innova\MediaResourceBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/12 09:34:10
 */
class Version20150312093408 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INT IDENTITY NOT NULL, 
                start DOUBLE PRECISION NOT NULL, 
                [end] DOUBLE PRECISION NOT NULL, 
                note VARCHAR(MAX), 
                media_resource_id NVARCHAR(255) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id INT IDENTITY NOT NULL, 
                url NVARCHAR(255) NOT NULL, 
                type NVARCHAR(255) NOT NULL, 
                media_resource_id NVARCHAR(255) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id INT IDENTITY NOT NULL, 
                region_id INT NOT NULL, 
                help_region_id INT, 
                has_loop BIT NOT NULL, 
                has_backward BIT NOT NULL, 
                has_rate BIT NOT NULL, 
                help_text NVARCHAR(255) NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198260155 ON innova_media_resource_region_config (region_id) 
            WHERE region_id IS NOT NULL
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198A93AD1 ON innova_media_resource_region_config (help_region_id) 
            WHERE help_region_id IS NOT NULL
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource (
                id INT IDENTITY NOT NULL, 
                name NVARCHAR(255) NOT NULL, 
                resourceNode_id INT, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_AC4F9D03B87FAB32 ON innova_media_resource (resourceNode_id) 
            WHERE resourceNode_id IS NOT NULL
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
            DROP CONSTRAINT FK_FEBE556198260155
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP CONSTRAINT FK_FEBE556198A93AD1
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