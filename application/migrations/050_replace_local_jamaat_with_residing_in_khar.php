<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Replace_local_jamaat_with_residing_in_khar extends CI_Migration
{
    public function up()
    {
        // 1. Update existing records with 'Residing in Local Jamaat' to 'Residing in Khar'
        $this->db->query("
            UPDATE user
            SET residential_status = 'Residing in Khar'
            WHERE residential_status = 'Residing in Local Jamaat'
        ");

        // 2. Drop and recreate triggers without references to 'Residing in Local Jamaat'
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
    }

    public function down()
    {
        // Revert triggers back to the definition in version 049 (including 'Residing in Local Jamaat')
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        $sql_insert = "
        CREATE TRIGGER trg_auto_activity_status_insert
        BEFORE INSERT ON user
        FOR EACH ROW
        BEGIN
            IF NEW.deeni_status = 'Normal'
               AND (NEW.residential_status = 'Residing in Khar' 
                    OR NEW.residential_status = 'Residing in Local Jamaat'
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
                    OR NEW.residential_status = 'Residing in Local Jamaat'
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

        // Revert database records from 'Residing in Khar' to 'Residing in Local Jamaat'
        $this->db->query("
            UPDATE user
            SET residential_status = 'Residing in Local Jamaat'
            WHERE residential_status = 'Residing in Khar'
        ");
    }
}
