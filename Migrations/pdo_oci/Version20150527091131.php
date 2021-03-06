<?php

namespace Innova\MediaResourceBundle\Migrations\pdo_oci;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated migration based on mapping information: modify it with caution
 *
 * Generation date: 2015/05/27 09:11:37
 */
class Version20150527091131 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("
            CREATE TABLE innova_media_resource_region (
                id NUMBER(10) NOT NULL, 
                media_resource_id NUMBER(10) NOT NULL, 
                \"start\" DOUBLE PRECISION NOT NULL, 
                end DOUBLE PRECISION NOT NULL, 
                note CLOB DEFAULT NULL NULL, 
                uuid VARCHAR2(255) NOT NULL, 
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
            CREATE INDEX IDX_BB5F60D07E5AEFB6 ON innova_media_resource_region (media_resource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_media (
                id NUMBER(10) NOT NULL, 
                media_resource_id NUMBER(10) NOT NULL, 
                url VARCHAR2(255) NOT NULL, 
                type VARCHAR2(255) NOT NULL, 
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
            CREATE INDEX IDX_5C0330F07E5AEFB6 ON innova_media_resource_media (media_resource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_region_config (
                id NUMBER(10) NOT NULL, 
                region_id NUMBER(10) NOT NULL, 
                has_loop NUMBER(1) NOT NULL, 
                has_backward NUMBER(1) NOT NULL, 
                has_rate NUMBER(1) NOT NULL, 
                help_text VARCHAR2(255) NOT NULL, 
                help_region_uuid VARCHAR2(255) NOT NULL, 
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
            CREATE TABLE innova_media_resource (
                id NUMBER(10) NOT NULL, 
                name VARCHAR2(255) NOT NULL, 
                published NUMBER(1) NOT NULL, 
                modified NUMBER(1) NOT NULL, 
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
            CREATE TABLE innova_media_resource_playlist_region (
                id NUMBER(10) NOT NULL, 
                region_id NUMBER(10) DEFAULT NULL NULL, 
                playlist_id NUMBER(10) NOT NULL, 
                ordering NUMBER(10) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION ADD CONSTRAINT INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION_AI_PK BEFORE INSERT ON INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT INNOVA_MEDIA_RESOURCE_PLAYLIST_REGION_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD98260155 ON innova_media_resource_playlist_region (region_id)
        ");
        $this->addSql("
            CREATE INDEX IDX_DC6F27BD6BBD148 ON innova_media_resource_playlist_region (playlist_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_playlist (
                id NUMBER(10) NOT NULL, 
                media_resource_id NUMBER(10) DEFAULT NULL NULL, 
                name VARCHAR2(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'INNOVA_MEDIA_RESOURCE_PLAYLIST' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE INNOVA_MEDIA_RESOURCE_PLAYLIST ADD CONSTRAINT INNOVA_MEDIA_RESOURCE_PLAYLIST_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE INNOVA_MEDIA_RESOURCE_PLAYLIST_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER INNOVA_MEDIA_RESOURCE_PLAYLIST_AI_PK BEFORE INSERT ON INNOVA_MEDIA_RESOURCE_PLAYLIST FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT INNOVA_MEDIA_RESOURCE_PLAYLIST_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT INNOVA_MEDIA_RESOURCE_PLAYLIST_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'INNOVA_MEDIA_RESOURCE_PLAYLIST_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT INNOVA_MEDIA_RESOURCE_PLAYLIST_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE INDEX IDX_A8D71DAD7E5AEFB6 ON innova_media_resource_playlist (media_resource_id)
        ");
        $this->addSql("
            CREATE TABLE innova_media_resource_context (
                id NUMBER(10) NOT NULL, 
                media_resource_id NUMBER(10) NOT NULL, 
                playlist_id NUMBER(10) DEFAULT NULL NULL, 
                hasActiveListening NUMBER(1) DEFAULT '0' NOT NULL, 
                hasAutoPause NUMBER(1) DEFAULT '0' NOT NULL, 
                hasLiveListening NUMBER(1) DEFAULT '0' NOT NULL, 
                PRIMARY KEY(id)
            )
        ");
        $this->addSql("
            DECLARE constraints_Count NUMBER; BEGIN 
            SELECT COUNT(CONSTRAINT_NAME) INTO constraints_Count 
            FROM USER_CONSTRAINTS 
            WHERE TABLE_NAME = 'INNOVA_MEDIA_RESOURCE_CONTEXT' 
            AND CONSTRAINT_TYPE = 'P'; IF constraints_Count = 0 
            OR constraints_Count = '' THEN EXECUTE IMMEDIATE 'ALTER TABLE INNOVA_MEDIA_RESOURCE_CONTEXT ADD CONSTRAINT INNOVA_MEDIA_RESOURCE_CONTEXT_AI_PK PRIMARY KEY (ID)'; END IF; END;
        ");
        $this->addSql("
            CREATE SEQUENCE INNOVA_MEDIA_RESOURCE_CONTEXT_SEQ START WITH 1 MINVALUE 1 INCREMENT BY 1
        ");
        $this->addSql("
            CREATE TRIGGER INNOVA_MEDIA_RESOURCE_CONTEXT_AI_PK BEFORE INSERT ON INNOVA_MEDIA_RESOURCE_CONTEXT FOR EACH ROW DECLARE last_Sequence NUMBER; last_InsertID NUMBER; BEGIN 
            SELECT INNOVA_MEDIA_RESOURCE_CONTEXT_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; IF (
                : NEW.ID IS NULL 
                OR : NEW.ID = 0
            ) THEN 
            SELECT INNOVA_MEDIA_RESOURCE_CONTEXT_SEQ.NEXTVAL INTO : NEW.ID 
            FROM DUAL; ELSE 
            SELECT NVL(Last_Number, 0) INTO last_Sequence 
            FROM User_Sequences 
            WHERE Sequence_Name = 'INNOVA_MEDIA_RESOURCE_CONTEXT_SEQ'; 
            SELECT : NEW.ID INTO last_InsertID 
            FROM DUAL; WHILE (last_InsertID > last_Sequence) LOOP 
            SELECT INNOVA_MEDIA_RESOURCE_CONTEXT_SEQ.NEXTVAL INTO last_Sequence 
            FROM DUAL; END LOOP; END IF; END;
        ");
        $this->addSql("
            CREATE INDEX IDX_DD5CBE327E5AEFB6 ON innova_media_resource_context (media_resource_id)
        ");
        $this->addSql("
            CREATE UNIQUE INDEX UNIQ_DD5CBE326BBD148 ON innova_media_resource_context (playlist_id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            ADD CONSTRAINT FK_BB5F60D07E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            ADD CONSTRAINT FK_5C0330F07E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            ADD CONSTRAINT FK_FEBE556198260155 FOREIGN KEY (region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource 
            ADD CONSTRAINT FK_AC4F9D03B87FAB32 FOREIGN KEY (resourceNode_id) 
            REFERENCES claro_resource_node (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            ADD CONSTRAINT FK_DC6F27BD98260155 FOREIGN KEY (region_id) 
            REFERENCES innova_media_resource_region (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            ADD CONSTRAINT FK_DC6F27BD6BBD148 FOREIGN KEY (playlist_id) 
            REFERENCES innova_media_resource_playlist (id) 
            ON DELETE CASCADE
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            ADD CONSTRAINT FK_A8D71DAD7E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_context 
            ADD CONSTRAINT FK_DD5CBE327E5AEFB6 FOREIGN KEY (media_resource_id) 
            REFERENCES innova_media_resource (id)
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_context 
            ADD CONSTRAINT FK_DD5CBE326BBD148 FOREIGN KEY (playlist_id) 
            REFERENCES innova_media_resource_playlist (id)
        ");
    }

    public function down(Schema $schema)
    {
        $this->addSql("
            ALTER TABLE innova_media_resource_region_config 
            DROP CONSTRAINT FK_FEBE556198260155
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            DROP CONSTRAINT FK_DC6F27BD98260155
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_region 
            DROP CONSTRAINT FK_BB5F60D07E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_media 
            DROP CONSTRAINT FK_5C0330F07E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist 
            DROP CONSTRAINT FK_A8D71DAD7E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_context 
            DROP CONSTRAINT FK_DD5CBE327E5AEFB6
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_playlist_region 
            DROP CONSTRAINT FK_DC6F27BD6BBD148
        ");
        $this->addSql("
            ALTER TABLE innova_media_resource_context 
            DROP CONSTRAINT FK_DD5CBE326BBD148
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
        $this->addSql("
            DROP TABLE innova_media_resource_playlist_region
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_playlist
        ");
        $this->addSql("
            DROP TABLE innova_media_resource_context
        ");
    }
}