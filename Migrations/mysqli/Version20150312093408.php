<?php

namespace Innova\MediaResourceBundle\Migrations\mysqli;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/12 09:34:10
 */
class Version20150312093408 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INT AUTO_INCREMENT NOT NULL, 
                start DOUBLE PRECISION NOT NULL, 
                end DOUBLE PRECISION NOT NULL, 
                note LONGTEXT DEFAULT NULL, 
                media_resource_id VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id INT AUTO_INCREMENT NOT NULL, 
                url VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                media_resource_id VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id INT AUTO_INCREMENT NOT NULL, 
                region_id INT NOT NULL, 
                help_region_id INT DEFAULT NULL, 
                has_loop TINYINT(1) NOT NULL, 
                has_backward TINYINT(1) NOT NULL, 
                has_rate TINYINT(1) NOT NULL, 
                help_text VARCHAR(255) NOT NULL, 
                UNIQUE INDEX UNIQ_FEBE556198260155 (region_id), 
                UNIQUE INDEX UNIQ_FEBE556198A93AD1 (help_region_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                resourceNode_id INT DEFAULT NULL, 
                UNIQUE INDEX UNIQ_AC4F9D03B87FAB32 (resourceNode_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD CONSTRAINT FK_FEBE556198260155 FOREIGN KEY (region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD CONSTRAINT FK_FEBE556198A93AD1 FOREIGN KEY (help_region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD CONSTRAINT FK_AC4F9D03B87FAB32 FOREIGN KEY (resourceNode_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE CASCADE
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP FOREIGN KEY FK_FEBE556198260155
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP FOREIGN KEY FK_FEBE556198A93AD1
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_media
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_region_config
        ");
        $this->addSql("
            DROP TABLE innova_media_resource
        ");
    }
}