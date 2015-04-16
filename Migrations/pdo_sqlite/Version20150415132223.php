<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/04/15 01:22:25
 */
class Version20150415132223 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist_region (
                id INTEGER NOT NULL, 
                region_id INTEGER DEFAULT NULL, 
                playlist_id INTEGER DEFAULT NULL, 
                \"order\" INTEGER NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD98260155 ON innova_media_resource_playlist_region (region_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD6BBD148 ON innova_media_resource_playlist_region (playlist_id)
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_playlist AS 
            SELECT id 
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
            INSERT INTO innova_media_resource_playlist (id) 
            SELECT id 
            FROM __temp__innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_playlist
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE innova_media_resource_playlist_region
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_playlist AS 
            SELECT id 
            FROM innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_playlist
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist (
                id INTEGER NOT NULL, 
                \"order\" INTEGER NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_playlist (id) 
            SELECT id 
            FROM __temp__innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_playlist
        ");
    }
}