<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Plm_group_list extends CI_Migration {

        public function up()
        {
            $this->dbforge->add_field(array(
                    'GROUPLIST_ID' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                    ),
                    'PLM_TYPE' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 50,
                        'comment' => 'PLM유형'
                    ),
                    'PARENT_ID' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'comment' => '게시글ID'
                    ),
                    'PG_ID' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'comment' => '그룹ID'
                    ),
                    'INS_ID' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'default' => null,
                        'comment' => '등록ID'
                    ),
                    'INS_DT' => array(
                        'type' => 'DATETIME',
                        'default' => null,
                        'comment' => '등록일'
                    ),
                    'INS_IP' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'default' => null,
                        'comment' => '등록IP'
                    ),
                    'UPD_ID' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'default' => null,
                        'comment' => '수정ID'
                    ),
                    'UPD_DT' => array(
                        'type' => 'DATETIME',
                        'default' => null,
                        'comment' => '수정일'
                    ),
                    'UPD_IP' => array(
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'default' => null,
                        'comment' => '수정IP'
                    )
            ));
            $this->dbforge->add_key('GROUPLIST_ID', TRUE);
            $this->dbforge->create_table('PLM_GROUP_LIST');
        }

        public function down()
        {
            $this->dbforge->drop_table('PLM_GROUP_LIST');
        }
}
?>