<?php declare(strict_types = 1);

namespace Business\Domain\Boundary\Repositories;
use Business\Domain\Entities\FlowGraphCreator;

interface FlowRepositoryInterface
{
    public function showFlowGraph() : array;
}
