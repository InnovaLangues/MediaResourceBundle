<?php

namespace Innova\MediaResourceBundle\Migrations\sqlsrv;

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
                id INT IDENTITY NOT NULL, 
                region_id INT, 
                playlist_id INT, 
                [order] INT NOT NULL, 
                PRIMARY KEY (id)
            )
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD98260155 ON innova_media_resource_playlist_region (region_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD6BBD148 ON innova_media_resource_playlist_region (playlist_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            ADD CONSTRAINT FK_DC6F27BD98260155 FOREIGN KEY (region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            ADD CONSTRAINT FK_DC6F27BD6BBD148 FOREIGN KEY (playlist_id) 
            REFERENCES innova_media_resource_playlist (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            ADD name NVARCHAR(255) NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            DROP COLUMN [order]
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP TABLE innova_media_resource_playlist_region
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            ADD [order] INT NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            DROP COLUMN name
        ");
    }
}