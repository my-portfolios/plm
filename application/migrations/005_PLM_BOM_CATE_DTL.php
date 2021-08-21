<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Plm_bom_cate_dtl extends CI_Migration {

        public function up()
        {
            $this->dbforge->drop_column("PLM_BOM_CATE_DTL", "BCD_AMT");
        }

        public function down()
        {
            $fields = array(
                'BCD_AMT' => array(
                  'type' => 'INT',
                  'constraint' => 11,
                  'default' => 0,
                  'after' => 'BCD_ID'
                )
              );

            $this->dbforge->add_column("PLM_BOM_CATE_DTL", $fields);
        }
}
?>