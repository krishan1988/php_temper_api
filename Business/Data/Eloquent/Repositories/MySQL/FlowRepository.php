<?php declare(strict_types = 1);

namespace Business\Data\Eloquent\Repositories\MySQL;

use Illuminate\Support\Facades\DB;
use Business\Data\Eloquent\Models\Flow;
use Business\Domain\Boundary\Repositories\FlowRepositoryInterface;
use Business\Domain\Entities\FlowGraphCreator;
use Mockery\Exception;

class FlowRepository implements FlowRepositoryInterface
{
    /**
     * @var Flow
     */
    private $flow;

    /**
     * PeopleRepository constructor.
     *
     * @param Flow $flow
     */
    public function __construct(Flow $flow)
    {
        $this->flow = $flow;
    }

    public function showFlowGraph(): array
    {



$weekly_retention =
    Flow::select([
        DB::raw('DATE_ADD(created_at, INTERVAL(2-DAYOFWEEK(created_at)) DAY) AS week_start'),
        DB::raw('CONCAT(YEAR(created_at), "/", WEEK(created_at)) AS week_name'),
        DB::raw('SUM(CASE WHEN onboarding_perentage <= 100 THEN 1 ELSE 0 END) AS Step1'),
        DB::raw('SUM(CASE WHEN onboarding_perentage > 0 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) Step2'),
        DB::raw('SUM(CASE WHEN onboarding_perentage > 20 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) Step3'),
        DB::raw('SUM(CASE WHEN onboarding_perentage > 40 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) Step4'),
        DB::raw('SUM(CASE WHEN onboarding_perentage > 50 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) Step5'),
        DB::raw('SUM(CASE WHEN onboarding_perentage > 70 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) Step6'),
        DB::raw('SUM(CASE WHEN onboarding_perentage > 90 AND onboarding_perentage <= 100 THEN 1 ELSE 0 END) Step7'),
        DB::raw('SUM(CASE WHEN onboarding_perentage = 100 THEN 1 ELSE 0 END) Step8')
    ])
    ->groupBy('week_name')
    ->orderBy(DB::raw('YEAR(created_at)'),'ASC')
    ->orderBy(DB::raw('WEEK(created_at)'),'ASC')
    ->get();

    

        return $this->mapToFlowCreator($weekly_retention);
    }

    private function mapToFlowCreator($request) : array
    {


        $chartArray ["chart"] = array (
        "type" => "line"
    );
    $chartArray ["title"] = array (
        "text" => "Weekly Retention Curve"
    );
    $chartArray ["credits"] = array (
        "enabled" => false
    );
    $chartArray ["xAxis"] = array (
        "categories" => array ()
    );
    $chartArray ["tooltip"] = array (
        "valueSuffix" => "%"
    );

    $categoryArray = array (
        '0',
        '20',
        '40',
        '50',
        '70',
        '90',
        '99',
        '100'
    );

    $chartArray ["xAxis"] = array (
        "categories" => $categoryArray
    );
    $chartArray ["yAxis"] = array (
        "title" => array (
            "text" => "Total Onboarded"
        ),
        'labels' => array(
            'format' => '{value}%'
        ),
        'min' => '0',
        'max' => '100'
    );


    foreach ($request as $week){
        $dataArray = array();

        for($i = 1; $i <= 8; $i++){

            if($i == 1){
                $dataArray[] = 100;
            }else{
                $dataArray[] = round(($week->{"Step".$i}/$week->Step1) * 100);
            }

        }


        $chartArray ["series"] [] = array (
            "name" => $week->week_start,
            "data" => $dataArray
        );
    }

        return $chartArray;
    }


}
