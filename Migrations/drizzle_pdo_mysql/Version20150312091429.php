<?php

namespace Innova\MediaResourceBundle\Migrations\drizzle_pdo_mysql;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/03/12 09:14:31
 */
class Version20150312091429 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id INT AUTO_INCREMENT NOT NULL, 
                `start` DOUBLE PRECISION NOT NULL, 
                `end` DOUBLE PRECISION NOT NULL, 
                note TEXT DEFAULT NULL, 
                mediaResource_id INT NOT NULL, 
                INDEX IDX_BB5F60D066C02C1E (mediaResource_id), 
                PRIMARY KEY(id)
            ) COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id INT AUTO_INCREMENT NOT NULL, 
                url VARCHAR(255) NOT NULL, 
                type VARCHAR(255) NOT NULL, 
                mediaResource_id INT DEFAULT NULL, 
                INDEX IDX_5C0330F066C02C1E (mediaResource_id), 
                PRIMARY KEY(id)
            ) COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id INT AUTO_INCREMENT NOT NULL, 
                region_id INT DEFAULT NULL, 
                help_region_id INT DEFAULT NULL, 
                has_loop BOOLEAN NOT NULL, 
                has_backward BOOLEAN NOT NULL, 
                has_rate BOOLEAN NOT NULL, 
                help_text VARCHAR(255) NOT NULL, 
                UNIQUE INDEX UNIQ_FEBE556198260155 (region_id), 
                UNIQUE INDEX UNIQ_FEBE556198A93AD1 (help_region_id), 
                PRIMARY KEY(id)
            ) COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                resourceNode_id INT DEFAULT NULL, 
                UNIQUE INDEX UNIQ_AC4F9D03B87FAB32 (resourceNode_id), 
                PRIMARY KEY(id)
            ) COLLATE utf8_unicode_ci ENGINE = InnoDB
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD CONSTRAINT FK_BB5F60D066C02C1E FOREIGN KEY (mediaResource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD CONSTRAINT FK_5C0330F066C02C1E FOREIGN KEY (mediaResource_id) 
            REFERENCES innova_media_resource (id)
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
            ALTER TABLE innova_media_resource_region 
            DROP FOREIGN KEY FK_BB5F60D066C02C1E
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP FOREIGN KEY FK_5C0330F066C02C1E
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