<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Business\Domain\UseCases\FlowViewer\GraphCreate;
use Illuminate\Http\Response;

class OnboardFlowController extends Controller
{
    /**
     * @var GraphCreate
     */
    private $graphCreate;

    /**
     * FlowController constructor.
     * @param GraphCreate $graphCreate
     */
    public function __construct(GraphCreate $graphCreate)
    {
        $this->graphCreate = $graphCreate;
    }

    public function createGraph(Request $request)
    {
        $result =  $this->graphCreate->showGraph();

        return json_encode($result);
    }

}
