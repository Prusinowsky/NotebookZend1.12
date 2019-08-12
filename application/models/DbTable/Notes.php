<?php

class Application_Model_DbTable_Notes extends Zend_Db_Table_Abstract
{

    protected $_name = 'notes';

    public function getNote($id){
        $id = (int)$id;
        $row = $this->fetchRow('id = '.$id);
        return $row->toArray();
    }

    public function addNote($array){
        $this->insert($array);
    }

    public function updateNote($id, $array){
        $this->update($array, 'id = '.(int)$id);
    }

    public function deleteNote($id){
        $this->delete('id = '.(int)$id);
    }
}

