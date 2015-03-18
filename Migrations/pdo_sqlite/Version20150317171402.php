<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlite;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/17 05:14:03
 */
class Version20150317171402 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD COLUMN published BOOLEAN NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD COLUMN modified BOOLEAN NOT NULL
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            DROP INDEX UNIQ_AC4F9D03B87FAB32
        ");
        $this->addSql("
            CREATE TEMPORARY TABLE __temp__innova_media_resource AS 
            SELECT id, 
            name, 
            resourceNode_id 
            FROM innova_media_resource
        ");
        $this->addSql("
            DROP TABLE innova_media_resource
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource (
                id INTEGER NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                resourceNode_id INTEGER DEFAULT NULL, 
                PRIMARY KEY(id), 
                CONSTRAINT FK_AC4F9D03B87FAB32 FOREIGN KEY (resourceNode_id) 
                REFERENCES claro_resource_node (id) 
                ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        ");
        $this->addSql("
            INSERT INTO innova_media_resource (id, name, resourceNode_id) 
            SELECT id, 
            name, 
            resourceNode_id 
            FROM __temp__innova_media_resource
        ");
        $this->addSql("
            DROP TABLE __temp__innova_media_resource
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_AC4F9D03B87FAB32 ON innova_media_resource (resourceNode_id)
        ");
    }
}