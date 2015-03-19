<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/19 04:45:39
 */
class Version20150319164538 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_media CHANGE media_resource_id media_resource_id INT NOT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP FOREIGN KEY FK_FEBE556198A93AD1
        ");
        $this->addSql("
            DROP INDEX UNIQ_FEBE556198A93AD1 ON innova_media_resource_region_config
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD help_region_uuid VARCHAR(255) NOT NULL, 
            DROP help_region_id
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_media CHANGE media_resource_id media_resource_id INT DEFAULT NULL
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD help_region_id INT DEFAULT NULL, 
            DROP help_region_uuid
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD CONSTRAINT FK_FEBE556198A93AD1 FOREIGN KEY (help_region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198A93AD1 ON innova_media_resource_region_config (help_region_id)
        ");
    }
}