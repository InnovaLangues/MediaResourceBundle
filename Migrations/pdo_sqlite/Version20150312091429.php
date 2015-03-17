<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/12 09:14:31
 */
class Version20150312091429 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INTEGER NOT NULL, 
                start DOUBLE PRECISION NOT NULL, 
                \"end\" DOUBLE PRECISION NOT NULL, 
                note CLOB DEFAULT NULL, 
                mediaResource_id INTEGER NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_BB5F60D066C02C1E ON innova_media_resource_region (mediaResource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id INTEGER NOT NULL, 
                url VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                mediaResource_id INTEGER DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F066C02C1E ON innova_media_resource_media (mediaResource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id INTEGER NOT NULL, 
                region_id INTEGER DEFAULT NULL, 
                help_region_id INTEGER DEFAULT NULL, 
                has_loop BOOLEAN NOT NULL, 
                has_backward BOOLEAN NOT NULL, 
                has_rate BOOLEAN NOT NULL, 
                help_text VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
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
                id INTEGER NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                resourceNode_id INTEGER DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_AC4F9D03B87FAB32 ON innova_media_resource (resourceNode_id)
        ");
    }

    public function down(Schema $schema)
    {
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