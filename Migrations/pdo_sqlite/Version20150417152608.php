<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/04/17 03:26:09
 */
class Version20150417152608 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_playlist AS 
            SELECT id, 
            name 
            FROM innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_playlist
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist (
                id INTEGER NOT NULL, 
                media_resource_id INTEGER DEFAULT NULL, 
                name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_A8D71DAD7E5AEFB6 FOREIGN KEY (media_resource_id) 
                REFERENCES innova_media_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_playlist (id, name) 
            SELECT id, 
            name 
            FROM __temp__innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_playlist
        ");
        $this->addSql("
            CREATE INDEX IDX_A8D71DAD7E5AEFB6 ON innova_media_resource_playlist (media_resource_id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP INDEX IDX_A8D71DAD7E5AEFB6
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_playlist AS 
            SELECT id, 
            name 
            FROM innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_playlist
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist (
                id INTEGER NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_playlist (id, name) 
            SELECT id, 
            name 
            FROM __temp__innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_playlist
        ");
    }
}