<?php

namespace Innova\MediaResourceBundle\Migrations\sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/17 05:14:04
 */
class Version20150317171402 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD published BIT NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD modified BIT NOT NULL
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource 
            DROP COLUMN published
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            DROP COLUMN modified
        ");
    }
}