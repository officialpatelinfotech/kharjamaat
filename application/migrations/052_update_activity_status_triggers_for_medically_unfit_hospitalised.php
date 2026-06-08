<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Update_activity_status_triggers_for_medically_unfit_hospitalised extends CI_Migration
{
    public function up()
    {
        // 1. Drop existing triggers
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        // 2. Recreate trg_auto_activity_status_insert using blacklist logic
        $sql_insert = "
        CREATE TRIGGER trg_auto_activity_status_insert
        BEFORE INSERT ON user
        FOR EACH ROW
        BEGIN
            IF (NEW.deeni_status IN ('Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb', 'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka')
                OR NEW.health_status IN ('Wafaat')
                OR NEW.residential_status IN ('Moved for Job', 'Moved for Studies', 'Moved after Marriage', 'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred', 'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable')) THEN
                SET NEW.activity_status = 'inactive';
            ELSE
                SET NEW.activity_status = 'active';
            END IF;
        END;
        ";
        $this->db->query($sql_insert);

        // 3. Recreate trg_auto_activity_status using blacklist logic
        $sql_update = "
        CREATE TRIGGER trg_auto_activity_status
        BEFORE UPDATE ON user
        FOR EACH ROW
        BEGIN
            IF (NEW.deeni_status IN ('Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb', 'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka')
                OR NEW.health_status IN ('Wafaat')
                OR NEW.residential_status IN ('Moved for Job', 'Moved for Studies', 'Moved after Marriage', 'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred', 'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable')) THEN
                SET NEW.activity_status = 'inactive';
            ELSE
                SET NEW.activity_status = 'active';
            END IF;
        END;
        ";
        $this->db->query($sql_update);

        // 4. Set activity_status = 'inactive' for all users matching the blacklist
        $this->db->query("
            UPDATE user
            SET activity_status = 'inactive'
            WHERE (deeni_status IN ('Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb', 'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka')
                OR health_status IN ('Wafaat')
                OR residential_status IN ('Moved for Job', 'Moved for Studies', 'Moved after Marriage', 'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred', 'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable'))
        ");

        // 5. Set activity_status = 'active' for all other users (who don't match the blacklist)
        $this->db->query("
            UPDATE user
            SET activity_status = 'active'
            WHERE (deeni_status NOT IN ('Deen Badli Lidu che', 'Married Outside', 'Misaq Not Given', 'Mustajeeb', 'No Ashara / LQ', 'No Vajebaat / Sabeel', 'Zero Days Scanned in Ashara Mubaraka') OR deeni_status IS NULL)
              AND (health_status NOT IN ('Wafaat') OR health_status IS NULL)
              AND (residential_status NOT IN ('Moved for Job', 'Moved for Studies', 'Moved after Marriage', 'Moved Permanently but not taken transfer', 'Permanently moved but ITS not Transferred', 'Permanently Moved and ITS also Transferred', 'Permanently Migrated', 'Unknown or Not Traceable') OR residential_status IS NULL)
        ");
    }

    public function down()
    {
        // Revert triggers back to the definition in version 050
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        $sql_insert = "
        CREATE TRIGGER trg_auto_activity_status_insert
        BEFORE INSERT ON user
        FOR EACH ROW
        BEGIN
            IF NEW.deeni_status = 'Normal'
               AND (NEW.residential_status = 'Residing in Khar' 
                    OR NEW.residential_status = 'Madresa in Khar'
                    OR NEW.residential_status = 'FMB Thaali in Khar')
               AND (NEW.health_status = 'Healthy' OR NEW.health_status = 'Lazimul Firash') THEN
                SET NEW.activity_status = 'active';
            ELSE
                SET NEW.activity_status = 'inactive';
            END IF;
        END;
        ";
        $this->db->query($sql_insert);

        $sql_update = "
        CREATE TRIGGER trg_auto_activity_status
        BEFORE UPDATE ON user
        FOR EACH ROW
        BEGIN
            IF NEW.deeni_status = 'Normal'
               AND (NEW.residential_status = 'Residing in Khar' 
                    OR NEW.residential_status = 'Madresa in Khar'
                    OR NEW.residential_status = 'FMB Thaali in Khar')
               AND (NEW.health_status = 'Healthy' OR NEW.health_status = 'Lazimul Firash') THEN
                SET NEW.activity_status = 'active';
            ELSE
                SET NEW.activity_status = 'inactive';
            END IF;
        END;
        ";
        $this->db->query($sql_update);

        // Revert activity status by marking active for only those on the old whitelist, others inactive
        $this->db->query("
            UPDATE user
            SET activity_status = 'active'
            WHERE deeni_status = 'Normal'
               AND (residential_status = 'Residing in Khar' 
                    OR residential_status = 'Madresa in Khar'
                    OR residential_status = 'FMB Thaali in Khar')
               AND (health_status = 'Healthy' OR health_status = 'Lazimul Firash')
        ");

        $this->db->query("
            UPDATE user
            SET activity_status = 'inactive'
            WHERE NOT (deeni_status = 'Normal'
               AND (residential_status = 'Residing in Khar' 
                    OR residential_status = 'Madresa in Khar'
                    OR residential_status = 'FMB Thaali in Khar')
               AND (health_status = 'Healthy' OR health_status = 'Lazimul Firash'))
        ");
    }
}
