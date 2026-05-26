<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Fix_activity_status_triggers extends CI_Migration
{
    public function up()
    {
        // Drop existing triggers if they exist
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        // Recreate trg_auto_activity_status_insert
        $sql_insert = "
        CREATE TRIGGER trg_auto_activity_status_insert
        BEFORE INSERT ON user
        FOR EACH ROW
        BEGIN
            IF NEW.deeni_status = 'Normal'
               AND (NEW.residential_status = 'Residing in Khar' OR NEW.residential_status = 'Residing in Local Jamaat')
               AND NEW.health_status = 'Healthy' THEN
                SET NEW.activity_status = 'active';
            ELSE
                SET NEW.activity_status = 'inactive';
            END IF;
        END;
        ";
        $this->db->query($sql_insert);

        // Recreate trg_auto_activity_status
        $sql_update = "
        CREATE TRIGGER trg_auto_activity_status
        BEFORE UPDATE ON user
        FOR EACH ROW
        BEGIN
            IF NEW.deeni_status = 'Normal'
               AND (NEW.residential_status = 'Residing in Khar' OR NEW.residential_status = 'Residing in Local Jamaat')
               AND NEW.health_status = 'Healthy' THEN
                SET NEW.activity_status = 'active';
            ELSE
                SET NEW.activity_status = 'inactive';
            END IF;
        END;
        ";
        $this->db->query($sql_update);

        // Also update any existing users that should be active but are inactive due to the trigger
        $this->db->query("
            UPDATE user
            SET activity_status = 'active'
            WHERE deeni_status = 'Normal'
              AND (residential_status = 'Residing in Khar' OR residential_status = 'Residing in Local Jamaat')
              AND health_status = 'Healthy'
              AND activity_status = 'inactive'
        ");
    }

    public function down()
    {
        // Revert triggers to original definition (checking only 'Residing in Khar')
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status_insert");
        $this->db->query("DROP TRIGGER IF EXISTS trg_auto_activity_status");

        $sql_insert = "
        CREATE TRIGGER trg_auto_activity_status_insert
        BEFORE INSERT ON user
        FOR EACH ROW
        BEGIN
            IF NEW.deeni_status = 'Normal'
               AND NEW.residential_status = 'Residing in Khar'
               AND NEW.health_status = 'Healthy' THEN
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
               AND NEW.residential_status = 'Residing in Khar'
               AND NEW.health_status = 'Healthy' THEN
                SET NEW.activity_status = 'active';
            ELSE
                SET NEW.activity_status = 'inactive';
            END IF;
        END;
        ";
        $this->db->query($sql_update);
    }
}
