<?php declare(strict_types = 1);

namespace Business\Domain\UseCases\FlowViewer;

use Business\Domain\Entities\FlowGraphCreator;
use Business\Domain\Boundary\Repositories\FlowRepositoryInterface as FlowRepository;
use Illuminate\Support\Collection;
use Business\Domain\UseCases\FlowViewer\GraphCreateException;

class GraphCreate
{
    /**
     * @var FlowRepository
     */
    private $flowRepository;

    /**
     * GraphCreate constructor.
     * @param FlowRepository $flowRepository
     */
    public function __construct(FlowRepository $flowRepository)
    {
        $this->flowRepository = $flowRepository;
    }


    public function showGraph()
    {
        $result = $this->flowRepository->showFlowGraph();

        if(empty($result)){
            GraphCreateException::noRecords();
        }

        return $result;
    }

}
