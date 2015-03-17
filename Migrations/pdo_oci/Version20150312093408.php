<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_oci;

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
                id NUMBER(10) NOT NULL, 
                \"start\" DOUBLE PRECISION NOT NULL, 
                end DOUBLE PRECISION NOT NULL, 
                note CLOB DEFAULT NULL NULL, 
                media_resource_id VARCHAR2(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'INNOVA_MEDIA_RESOURCE_REGION' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE INNOVA_MEDIA_RESOURCE_REGION ADD CONSTRAINT INNOVA_MEDIA_RESOURCE_REGION_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE INNOVA_MEDIA_RESOURCE_REGION_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER INNOVA_MEDIA_RESOURCE_REGION_AI_PK BEFORE INSERT ON INNOVA_MEDIA_RESOURCE_REGION FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT INNOVA_MEDIA_RESOURCE_REGION_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT INNOVA_MEDIA_RESOURCE_REGION_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'INNOVA_MEDIA_RESOURCE_REGION_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT INNOVA_MEDIA_RESOURCE_REGION_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id NUMBER(10) NOT NULL, 
                url VARCHAR2(255) NOT NULL, 
                type VARCHAR2(255) NOT NULL, 
                media_resource_id VARCHAR2(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'INNOVA_MEDIA_RESOURCE_MEDIA' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE INNOVA_MEDIA_RESOURCE_MEDIA ADD CONSTRAINT INNOVA_MEDIA_RESOURCE_MEDIA_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE INNOVA_MEDIA_RESOURCE_MEDIA_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER INNOVA_MEDIA_RESOURCE_MEDIA_AI_PK BEFORE INSERT ON INNOVA_MEDIA_RESOURCE_MEDIA FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT INNOVA_MEDIA_RESOURCE_MEDIA_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT INNOVA_MEDIA_RESOURCE_MEDIA_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'INNOVA_MEDIA_RESOURCE_MEDIA_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT INNOVA_MEDIA_RESOURCE_MEDIA_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id NUMBER(10) NOT NULL, 
                region_id NUMBER(10) NOT NULL, 
                help_region_id NUMBER(10) DEFAULT NULL NULL, 
                has_loop NUMBER(1) NOT NULL, 
                has_backward NUMBER(1) NOT NULL, 
                has_rate NUMBER(1) NOT NULL, 
                help_text VARCHAR2(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'INNOVA_MEDIA_RESOURCE_REGION_CONFIG' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE INNOVA_MEDIA_RESOURCE_REGION_CONFIG ADD CONSTRAINT INNOVA_MEDIA_RESOURCE_REGION_CONFIG_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE INNOVA_MEDIA_RESOURCE_REGION_CONFIG_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER INNOVA_MEDIA_RESOURCE_REGION_CONFIG_AI_PK BEFORE INSERT ON INNOVA_MEDIA_RESOURCE_REGION_CONFIG FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT INNOVA_MEDIA_RESOURCE_REGION_CONFIG_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT INNOVA_MEDIA_RESOURCE_REGION_CONFIG_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'INNOVA_MEDIA_RESOURCE_REGION_CONFIG_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT INNOVA_MEDIA_RESOURCE_REGION_CONFIG_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198260155 ON innova_media_resource_region_config (region_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_FEBE556198A93AD1 ON innova_media_resource_region_config (help_region_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource (
                id NUMBER(10) NOT NULL, 
                name VARCHAR2(255) NOT NULL, 
                resourceNode_id NUMBER(10) DEFAULT NULL NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'INNOVA_MEDIA_RESOURCE' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE INNOVA_MEDIA_RESOURCE ADD CONSTRAINT INNOVA_MEDIA_RESOURCE_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE INNOVA_MEDIA_RESOURCE_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER INNOVA_MEDIA_RESOURCE_AI_PK BEFORE INSERT ON INNOVA_MEDIA_RESOURCE FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT INNOVA_MEDIA_RESOURCE_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT INNOVA_MEDIA_RESOURCE_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'INNOVA_MEDIA_RESOURCE_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT INNOVA_MEDIA_RESOURCE_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_AC4F9D03B87FAB32 ON innova_media_resource (resourceNode_id)
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
            DROP CONSTRAINT FK_FEBE556198260155
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP CONSTRAINT FK_FEBE556198A93AD1
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