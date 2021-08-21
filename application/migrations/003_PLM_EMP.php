<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Plm_emp extends CI_Migration {

        public function up()
        {
            $fields = array(
                'PG_ID' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'default' => null,
                    'after' => 'PE_ID',
                    'comment' => '그룹ID'
                  )
              );

            $this->dbforge->add_column("PLM_EMP", $fields);
        }

        public function down()
        {
            $this->dbforge->drop_column("PLM_EMP", "PG_ID");
        }
}
?>