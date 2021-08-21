<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Plm_board extends CI_Migration {

        public function up()
        {
            $fields = array(
                'BOARD_NOTICE' => array(
                  'type' => 'TEXT',
                  'default' => NULL,
                  'after' => 'UPD_IP',
                  'comment' => '공지글ID'
                )
              );

            $this->dbforge->add_column("PLM_BOARD", $fields);
        }

        public function down()
        {
            $this->dbforge->drop_column("PLM_BOARD", "BOARD_NOTICE");
        }
}
?>