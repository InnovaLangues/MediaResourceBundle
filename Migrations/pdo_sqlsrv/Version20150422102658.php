<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_sqlsrv;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/04/22 10:27:00
 */
class Version20150422102658 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            sp_RENAME 'innova_media_resource_playlist_region.[order]', 
            'ordering', 
            'COLUMN'
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            sp_RENAME 'innova_media_resource_playlist_region.ordering', 
            '[order]', 
            'COLUMN'
        ");
    }
}