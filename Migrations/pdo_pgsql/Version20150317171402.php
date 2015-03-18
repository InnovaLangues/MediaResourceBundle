<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_pgsql;

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
            ADD published BOOLEAN NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD modified BOOLEAN NOT NULL
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource 
            DROP published
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            DROP modified
        ");
    }
}