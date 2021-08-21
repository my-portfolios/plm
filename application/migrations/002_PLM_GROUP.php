<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Plm_group extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'PG_ID' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'comment' => '그룹ID'
                        ),
                        'PG_NM' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'comment' => '그룹이름'
                        ),
                        'PG_TEL' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'default' => null,
                                'comment' => '전화번호'
                        ),
                        'PG_DEL_YN' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 50,
                                'default' => null,
                                'comment' => '삭제여부'
                        ),
                        'INS_ID' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 255,
                                'comment' => '등록ID'
                        ),
                        'INS_DT' => array(
                                'type' => 'DATETIME',
                                'comment' => '등록일'
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
                        )
                ));
                $this->dbforge->add_key('PG_ID', TRUE);
                $this->dbforge->create_table('PLM_GROUP');
        }

        public function down()
        {
                $this->dbforge->drop_table('PLM_GROUP');
        }
}
?>