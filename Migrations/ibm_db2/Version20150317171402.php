<?php

namespace Innova\MediaResourceBundle\Migrations\ibm_db2;

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
            ADD COLUMN published SMALLINT NOT NULL WITH DEFAULT 
            ADD COLUMN modified SMALLINT NOT NULL WITH DEFAULT
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource 
            DROP COLUMN published 
            DROP COLUMN modified
        ");
        $this->addSql("
            CALL SYSPROC.ADMIN_CMD (
                'REORG TABLE innova_media_resource'
            )
        ");
    }
}