<?php

namespace Innova\MediaResourceBundle\Migrations\ibm_db2;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/04/23 03:25:02
 */
class Version20150423152500 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region ALTER COLUMN playlist_id 
            SET 
                NOT NULL
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_playlist_region'
            )
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region ALTER COLUMN playlist_id 
            DROP NOT NULL
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource_playlist_region'
            )
        ");
    }
}