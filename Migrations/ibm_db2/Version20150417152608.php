<?php

namespace Innova\MediaResourceBundle\Migrations\ibm_db2;

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
            ALTER TABLE innova_media_resource_playlist 
            ADD COLUMN media_resource_id INTEGER DEFAULT NULL
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
            DROP FOREIGN KEY FK_A8D71DAD7E5AEFB6
        ");
        $this->addSql("
            DROP INDEX IDX_A8D71DAD7E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            DROP COLUMN media_resource_id
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_playlist'
            )
        ");
    }
}