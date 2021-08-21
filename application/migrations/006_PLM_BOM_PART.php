<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Plm_bom_part extends CI_Migration {

        public function up()
        {
            $fields = array(
                'BP_WTB' => array(
                  'type' => 'VARCHAR',
                  'constraint' => 255,
                  'default' => NULL,
                  'after' => 'BP_MTR',
                  'comment' => '구입처'
                )
              );

            $this->dbforge->add_column("PLM_BOM_PART", $fields);
        }

        public function down()
        {
            $this->dbforge->drop_column("PLM_BOM_PART", "BP_WTB");
        }
}
?>