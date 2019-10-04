<?php declare(strict_types = 1);

namespace Business\Adapters\DBTransaction\MySQL;

use Business\Domain\Boundary\Adapters\DBTransactionAdapterInterface;

class MySQLTransactionAdapter implements DBTransactionAdapterInterface
{
    protected $handler = null;
    private $transactionCounter = 0;


    /**
     * MySQLTransactionAdapter constructor.
     */
    public function __construct()
    {
        $this->handler = app('db')->connection()->getPdo();
    }


    /**
     * Begin the transaction.
     */
    public function begin() : void
    {
        // check already in a transaction and if not start the transaction
        if(!$this->handler->inTransaction())
        {
            $this->handler->beginTransaction();
        }

        // increment transaction counter
        $this->transactionCounter++;

        return;
    }


    /**
     * Commit the transaction.
     */
    public function commit() : void
    {
        // decrement the transaction counter
        $this->transactionCounter--;

        if(0 === $this->transactionCounter)
        {
            $this->handler->commit();
        }

        return;
    }



    /**
     * Rollback the transaction.
     */
    public function rollback() : void
    {
        // TODO: The rollback works fine even without checking for inTransaction. But if this check was not done
        //       before rolling back, a 'no active transaction' error is thrown. Need to find out why.

        if($this->handler->inTransaction())
        {
            $this->handler->rollBack();
        }

        // reset transaction counter
        $this->transactionCounter = 0;

        return;
    }


    /**
     * Wrap a segment of code in a transaction.
     *
     * @param \Closure $function
     * @throws \Exception
     */
    public function wrapInTransaction(\Closure $function) : void
    {
        $this->begin();

        try
        {
            $result = $function();

            $this->commit();
        }
        catch(\Exception $e)
        {
            $this->rollback();

            throw $e;
        }

        return;
    }
}