<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Adapters;

interface DBTransactionAdapterInterface
{
    /**
     * Begin the transaction.
     */
    public function begin() : void;

    /**
     * Commit the transaction.
     */
    public function commit() : void;

    /**
     * Rollback the transaction.
     */
    public function rollback() : void;

    /**
     * Wrap a segment of code in a transaction.
     *
     * @param \Closure $function
     */
    public function wrapInTransaction(\Closure $function) : void;
}