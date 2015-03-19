<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/19 04:45:39
 */
class Version20150319164538 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            DROP INDEX IDX_5C0330F07E5AEFB6
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_media AS 
            SELECT id, 
            media_resource_id, 
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
                media_resource_id INTEGER NOT NULL, 
                url VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_5C0330F07E5AEFB6 FOREIGN KEY (media_resource_id) 
                REFERENCES innova_media_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_media (id, media_resource_id, url, type) 
            SELECT id, 
            media_resource_id, 
            url, 
            type 
            FROM __temp__innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_media
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F07E5AEFB6 ON innova_media_resource_media (media_resource_id)
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
                region_id INTEGER NOT NULL, 
                has_loop BOOLEAN NOT NULL, 
                has_backward BOOLEAN NOT NULL, 
                has_rate BOOLEAN NOT NULL, 
                help_text VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                help_region_uuid VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_FEBE556198260155 FOREIGN KEY (region_id) 
                REFERENCES innova_media_resource_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_region_config (
                id, region_id, has_loop, has_backward, 
                has_rate, help_text
            ) 
            SELECT id, 
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
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP INDEX IDX_5C0330F07E5AEFB6
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_media AS 
            SELECT id, 
            media_resource_id, 
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
                media_resource_id INTEGER DEFAULT NULL, 
                url VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_5C0330F07E5AEFB6 FOREIGN KEY (media_resource_id) 
                REFERENCES innova_media_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_media (id, media_resource_id, url, type) 
            SELECT id, 
            media_resource_id, 
            url, 
            type 
            FROM __temp__innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_media
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F07E5AEFB6 ON innova_media_resource_media (media_resource_id)
        ");
        $this->addSql("
            DROP INDEX UNIQ_FEBE556198260155
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_region_config AS 
            SELECT id, 
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
                region_id INTEGER NOT NULL, 
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
                id, region_id, has_loop, has_backward, 
                has_rate, help_text
            ) 
            SELECT id, 
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
}