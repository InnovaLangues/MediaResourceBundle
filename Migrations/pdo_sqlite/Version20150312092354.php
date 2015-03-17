<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

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
            DROP INDEX IDX_BB5F60D066C02C1E
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_region AS 
            SELECT id, 
            start, 
            \"end\", 
            note 
            FROM innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INTEGER NOT NULL, 
                start DOUBLE PRECISION NOT NULL, 
                \"end\" DOUBLE PRECISION NOT NULL, 
                note CLOB DEFAULT NULL COLLATE utf8_unicode_ci, 
                media_resource_id VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_region (id, start, \"end\", note) 
            SELECT id, 
            start, 
            \"end\", 
            note 
            FROM __temp__innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_region
        ");
        $this->addSql("
            DROP INDEX IDX_5C0330F066C02C1E
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_media AS 
            SELECT id, 
            url, 
            type 
            FROM innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_media
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id INTEGER NOT NULL, 
                url VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                media_resource_id VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_media (id, url, type) 
            SELECT id, 
            url, 
            type 
            FROM __temp__innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_media
        ");
        $this->addSql("
            DROP INDEX UNIQ_FEBE556198260155
        ");
        $this->addSql("
            DROP INDEX UNIQ_FEBE556198A93AD1
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_region_config AS 
            SELECT id, 
            help_region_id, 
            region_id, 
            has_loop, 
            has_backward, 
            has_rate, 
            help_text 
            FROM innova_media_resource_region_config
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region_config
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id INTEGER NOT NULL, 
                help_region_id INTEGER DEFAULT NULL, 
                region_id INTEGER NOT NULL, 
                has_loop BOOLEAN NOT NULL, 
                has_backward BOOLEAN NOT NULL, 
                has_rate BOOLEAN NOT NULL, 
                help_text VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_FEBE556198A93AD1 FOREIGN KEY (help_region_id) 
                REFERENCES innova_media_resource_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE, 
                CONSTRAINT FK_FEBE556198260155 FOREIGN KEY (region_id) 
                REFERENCES innova_media_resource_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_region_config (
                id, help_region_id, region_id, has_loop, 
                has_backward, has_rate, help_text
            ) 
            SELECT id, 
            help_region_id, 
            region_id, 
            has_loop, 
            has_backward, 
            has_rate, 
            help_text 
            FROM __temp__innova_media_resource_region_config
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_region_config
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198260155 ON innova_media_resource_region_config (region_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198A93AD1 ON innova_media_resource_region_config (help_region_id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_media AS 
            SELECT id, 
            url, 
            type 
            FROM innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_media
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id INTEGER NOT NULL, 
                url VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                mediaResource_id INTEGER DEFAULT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_5C0330F066C02C1E FOREIGN KEY (mediaResource_id) 
                REFERENCES innova_media_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_media (id, url, type) 
            SELECT id, 
            url, 
            type 
            FROM __temp__innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_media
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F066C02C1E ON innova_media_resource_media (mediaResource_id)
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_region AS 
            SELECT id, 
            start, 
            \"end\", 
            note 
            FROM innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INTEGER NOT NULL, 
                start DOUBLE PRECISION NOT NULL, 
                \"end\" DOUBLE PRECISION NOT NULL, 
                note CLOB DEFAULT NULL, 
                mediaResource_id INTEGER NOT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_BB5F60D066C02C1E FOREIGN KEY (mediaResource_id) 
                REFERENCES innova_media_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_region (id, start, \"end\", note) 
            SELECT id, 
            start, 
            \"end\", 
            note 
            FROM __temp__innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_region
        ");
        $this->addSql("
            CREATE INDEX IDX_BB5F60D066C02C1E ON innova_media_resource_region (mediaResource_id)
        ");
        $this->addSql("
            DROP INDEX UNIQ_FEBE556198260155
        ");
        $this->addSql("
            DROP INDEX UNIQ_FEBE556198A93AD1
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_region_config AS 
            SELECT id, 
            region_id, 
            help_region_id, 
            has_loop, 
            has_backward, 
            has_rate, 
            help_text 
            FROM innova_media_resource_region_config
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region_config
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
                PRIMARY KEY(id), 
                CONSTRAINT FK_FEBE556198260155 FOREIGN KEY (region_id) 
                REFERENCES innova_media_resource_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE, 
                CONSTRAINT FK_FEBE556198A93AD1 FOREIGN KEY (help_region_id) 
                REFERENCES innova_media_resource_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_region_config (
                id, region_id, help_region_id, has_loop, 
                has_backward, has_rate, help_text
            ) 
            SELECT id, 
            region_id, 
            help_region_id, 
            has_loop, 
            has_backward, 
            has_rate, 
            help_text 
            FROM __temp__innova_media_resource_region_config
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_region_config
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198260155 ON innova_media_resource_region_config (region_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198A93AD1 ON innova_media_resource_region_config (help_region_id)
        ");
    }
}