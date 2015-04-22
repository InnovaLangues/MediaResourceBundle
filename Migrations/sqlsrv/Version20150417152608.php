<?php

namespace Innova\MediaResourceBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/04/17 03:26:10
 */
class Version20150417152608 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            ADD media_resource_id INT
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            ADD CONSTRAINT FK_A8D71DAD7E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            CREATE INDEX IDX_A8D71DAD7E5AEFB6 ON innova_media_resource_playlist (media_resource_id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            DROP CONSTRAINT FK_A8D71DAD7E5AEFB6
        ");
        $this->addSql("
            IF EXISTS (
                SELECT * 
                FROM sysobjects 
                WHERE name = 'IDX_A8D71DAD7E5AEFB6'
            ) 
            ALTER TABLE innova_media_resource_playlist 
            DROP CONSTRAINT IDX_A8D71DAD7E5AEFB6 ELSE 
            DROP INDEX IDX_A8D71DAD7E5AEFB6 ON innova_media_resource_playlist
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            DROP COLUMN media_resource_id
        ");
    }
}