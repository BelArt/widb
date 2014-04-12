<?php
/**
 *
 */
class MyWebUser extends CWebUser
{
    private $_model;

    public function getRole()
    {
        if ($user = $this->getModel()) {
            return $user->role;
        }
    }

    private function getModel()
    {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = Users::model()->findByPk($this->id, array('select' => 'role'));
        }
        return $this->_model;
    }
} 