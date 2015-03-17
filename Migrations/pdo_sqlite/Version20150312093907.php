<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

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
            CREATE TEMPORARY TABLE __temp__innova_media_resource_region AS 
            SELECT id, 
            start, 
            \"end\", 
            note, 
            media_resource_id 
            FROM innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INTEGER NOT NULL, 
                media_resource_id INTEGER NOT NULL, 
                start DOUBLE PRECISION NOT NULL, 
                \"end\" DOUBLE PRECISION NOT NULL, 
                note CLOB DEFAULT NULL COLLATE utf8_unicode_ci, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_BB5F60D07E5AEFB6 FOREIGN KEY (media_resource_id) 
                REFERENCES innova_media_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_region (
                id, start, \"end\", note, media_resource_id
            ) 
            SELECT id, 
            start, 
            \"end\", 
            note, 
            media_resource_id 
            FROM __temp__innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_region
        ");
        $this->addSql("
            CREATE INDEX IDX_BB5F60D07E5AEFB6 ON innova_media_resource_region (media_resource_id)
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_media AS 
            SELECT id, 
            url, 
            type, 
            media_resource_id 
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
            INSERT INTO innova_media_resource_media (id, url, type, media_resource_id) 
            SELECT id, 
            url, 
            type, 
            media_resource_id 
            FROM __temp__innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_media
        ");
        $this->addSql("
            CREATE INDEX IDX_5C0330F07E5AEFB6 ON innova_media_resource_media (media_resource_id)
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
                url VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                media_resource_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                PRIMARY KEY(id)
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
            DROP INDEX IDX_BB5F60D07E5AEFB6
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_region AS 
            SELECT id, 
            media_resource_id, 
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
                media_resource_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_region (
                id, media_resource_id, start, \"end\", 
                note
            ) 
            SELECT id, 
            media_resource_id, 
            start, 
            \"end\", 
            note 
            FROM __temp__innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_region
        ");
    }
}