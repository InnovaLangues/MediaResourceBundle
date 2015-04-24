<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/04/24 09:32:09
 */
class Version20150424093208 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            DROP INDEX IDX_DC6F27BD98260155
        ");
        $this->addSql("
            DROP INDEX IDX_DC6F27BD6BBD148
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_playlist_region AS 
            SELECT id, 
            playlist_id, 
            region_id, 
            ordering 
            FROM innova_media_resource_playlist_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_playlist_region
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist_region (
                id INTEGER NOT NULL, 
                playlist_id INTEGER NOT NULL, 
                region_id INTEGER DEFAULT NULL, 
                ordering INTEGER NOT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_DC6F27BD6BBD148 FOREIGN KEY (playlist_id) 
                REFERENCES innova_media_resource_playlist (id) 
                ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, 
                CONSTRAINT FK_DC6F27BD98260155 FOREIGN KEY (region_id) 
                REFERENCES innova_media_resource_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_playlist_region (
                id, playlist_id, region_id, ordering
            ) 
            SELECT id, 
            playlist_id, 
            region_id, 
            ordering 
            FROM __temp__innova_media_resource_playlist_region
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_playlist_region
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD98260155 ON innova_media_resource_playlist_region (region_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD6BBD148 ON innova_media_resource_playlist_region (playlist_id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP INDEX IDX_DC6F27BD98260155
        ");
        $this->addSql("
            DROP INDEX IDX_DC6F27BD6BBD148
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource_playlist_region AS 
            SELECT id, 
            region_id, 
            playlist_id, 
            ordering 
            FROM innova_media_resource_playlist_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_playlist_region
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist_region (
                id INTEGER NOT NULL, 
                region_id INTEGER DEFAULT NULL, 
                playlist_id INTEGER NOT NULL, 
                ordering INTEGER NOT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_DC6F27BD98260155 FOREIGN KEY (region_id) 
                REFERENCES innova_media_resource_region (id) NOT DEFERRABLE INITIALLY IMMEDIATE, 
                CONSTRAINT FK_DC6F27BD6BBD148 FOREIGN KEY (playlist_id) 
                REFERENCES innova_media_resource_playlist (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource_playlist_region (
                id, region_id, playlist_id, ordering
            ) 
            SELECT id, 
            region_id, 
            playlist_id, 
            ordering 
            FROM __temp__innova_media_resource_playlist_region
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource_playlist_region
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD98260155 ON innova_media_resource_playlist_region (region_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD6BBD148 ON innova_media_resource_playlist_region (playlist_id)
        ");
    }
}