<?php
/**
 * Class NestedPDO для поддержки вложенных транзакций
 */
class NestedPDO extends PDO
{
    // The current transaction level.
    protected $transLevel = 0;

    public function beginTransaction()
    {
        if($this->transLevel == 0) {
            parent::beginTransaction();
        } else {
            $this->exec("SAVEPOINT LEVEL{$this->transLevel}");
        }

        $this->transLevel++;
    }

    public function commit()
    {
        $this->transLevel--;

        if($this->transLevel == 0) {
            parent::commit();
        } else {
            $this->exec("RELEASE SAVEPOINT LEVEL{$this->transLevel}");
        }

    }

    public function rollBack()
    {
        $this->transLevel--;

        if($this->transLevel == 0) {
            parent::rollBack();
        } else {
            $this->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->transLevel}");
        }

    }
}