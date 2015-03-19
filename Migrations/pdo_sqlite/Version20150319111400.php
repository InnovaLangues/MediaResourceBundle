<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/19 11:14:02
 */
class Version20150319111400 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD COLUMN uuid VARCHAR(255) NOT NULL
        ");
    }

    public function down(Schema $schema)
    {
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
                media_resource_id INTEGER NOT NULL, 
                start DOUBLE PRECISION NOT NULL, 
                \"end\" DOUBLE PRECISION NOT NULL, 
                note CLOB DEFAULT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_BB5F60D07E5AEFB6 FOREIGN KEY (media_resource_id) 
                REFERENCES innova_media_resource (id) NOT DEFERRABLE INITIALLY IMMEDIATE
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
        $this->addSql("
            CREATE INDEX IDX_BB5F60D07E5AEFB6 ON innova_media_resource_region (media_resource_id)
        ");
    }
}