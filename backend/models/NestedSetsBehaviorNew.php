<?php
namespace backend\models;


use creocoder\nestedsets\NestedSetsBehavior;

class NestedSetsBehaviorNew extends NestedSetsBehavior
{


    public function beforeDelete()
    {
        if ($this->owner->getIsNewRecord()) {
            throw new Exception('Can not delete a node when it is new record.');
        }

        if ($this->owner->isRoot() && $this->operation !== self::OPERATION_DELETE_WITH_CHILDREN) {

        }

        $this->owner->refresh();
    }

}

?>