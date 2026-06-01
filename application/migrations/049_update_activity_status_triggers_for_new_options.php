<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Update_activity_status_triggers_for_new_options extends CI_Migration
{
    public function up()
    {
        // Drop existing triggers if they exist
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        // Recreate trg_auto_activity_status_insert with new residential options
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

        // Recreate trg_auto_activity_status with new residential options
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

        // Update existing users who might have these statuses (just in case)
        $this->db->query("
            UPDATE user
            SET activity_status = 'active'
            WHERE deeni_status = 'Normal'
              AND (residential_status = 'Residing in Khar' 
                   OR residential_status = 'Residing in Local Jamaat'
                   OR residential_status = 'Madresa in Khar'
                   OR residential_status = 'FMB Thaali in Khar')
              AND (health_status = 'Healthy' OR health_status = 'Lazimul Firash')
              AND activity_status = 'inactive'
        ");
    }

    public function down()
    {
        // Revert triggers back to the definition in version 046
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        $sql_insert = "
        CREATE TRIGGER trg_auto_activity_status_insert
        BEFORE INSERT ON user
        FOR EACH ROW
        BEGIN
            IF NEW.deeni_status = 'Normal'
               AND (NEW.residential_status = 'Residing in Khar' OR NEW.residential_status = 'Residing in Local Jamaat')
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
               AND (NEW.residential_status = 'Residing in Khar' OR NEW.residential_status = 'Residing in Local Jamaat')
               AND (NEW.health_status = 'Healthy' OR NEW.health_status = 'Lazimul Firash') THEN
                SET NEW.activity_status = 'active';
            ELSE
                SET NEW.activity_status = 'inactive';
            END IF;
        END;
        ";
        $this->db->query($sql_update);
    }
}
