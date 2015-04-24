<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_oci;

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
            ALTER TABLE innova_media_resource_playlist_region MODIFY (
                playlist_id NUMBER(10) NOT NULL
            )
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region MODIFY (
                playlist_id NUMBER(10) DEFAULT NULL NULL
            )
        ");
    }
}