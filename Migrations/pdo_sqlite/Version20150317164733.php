<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/17 04:47:34
 */
class Version20150317164733 extends AbstractMigration
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
                media_resource_id INTEGER DEFAULT NULL, 
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
                media_resource_id INTEGER NOT NULL, 
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
    }
}