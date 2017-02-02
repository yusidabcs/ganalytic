<?php
namespace Modules\Ganalytic\Http\Controllers\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Foundation\Asset\Manager\AssetManager;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Spatie\Analytics\Period;
/**
 * Created by PhpStorm.
 * User: yusida
 * Date: 2/2/17
 * Time: 10:58 AM
 */
class GanalyticController extends AdminBaseController
{

    public function __construct(AssetManager $assetManager)
    {
        parent::__construct();
    }

    public function index()
    {
        $this->assetPipeline->requireJs('chart.js');

        $startDate = new \Carbon\Carbon('first day of this month');
        $endDate = new \Carbon\Carbon('last day of this month');

        return view('ganalytic::admin.ganalytics.index',compact('startDate','endDate'));
    }

    public function store(Request $request)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            if (strpos(file_get_contents($path), 'ANALYTICS_VIEW_ID') === false) {

                file_put_contents($path, PHP_EOL . 'ANALYTICS_VIEW_ID=' . $request->get('view_id'), FILE_APPEND);
            } else {
                file_put_contents($path, str_replace(
                    'ANALYTICS_VIEW_ID=' . config('laravel-analytics.view_id'), 'ANALYTICS_VIEW_ID=' . $request->get('view_id'), file_get_contents($path)
                ));
            }
            if ($request->hasFile('service_account_credentials_json')) {
                $destinationPath = storage_path('app/laravel-google-analytics');
                $filename = $request->file('service_account_credentials_json')->getClientOriginalName();
                $request->file('service_account_credentials_json')->move($destinationPath,$filename);

                if (strpos(file_get_contents($path), 'ANALYTICS_JSON_FILE=') === false) {

                    file_put_contents($path, PHP_EOL . 'ANALYTICS_JSON_FILE=' . $destinationPath.'/'.$filename, FILE_APPEND);
                } else {
                    file_put_contents($path, str_replace(
                        'ANALYTICS_JSON_FILE=' . config('laravel-analytics.service_account_credentials_json'), 'ANALYTICS_JSON_FILE=' . $destinationPath.'/'.$filename, file_get_contents($path)
                    ));
                }
            }


        }
        return redirect()->back();
    }
    public function show($id)
    {
        $startDate = new \Carbon\Carbon('first day of this month');
        $endDate = new \Carbon\Carbon('last day of this month');

        $period = Period::create($startDate,$endDate);

        $rs = [];
        $start = $startDate->copy();
        while ($start->lte($endDate)) {
            $date = $start->copy();
            $start->addDay();
            $rs['labels'][] = $date->format('d M');
        }

        $response = \Analytics::performQuery(
            $period,
            'ga:users,ga:newUsers,ga:pageviews,ga:bounceRate',
            [
                'dimensions' => 'ga:date'
            ]
        );
        $rs['datasets'][0] = [
            'label' =>  "Visitors",
            'borderColor'   =>  "#00c0ef",
            'pointColor'    => "rgb(0, 192, 239,1)",
            'pointStrokeColor'  => "rgb(0, 192, 239,1)",
            'pointHighlightFill'    => "#fff",
            'pointHighlightStroke' =>   "#00c0ef",
        ];
        $rs['datasets'][1] = [
            'label' =>  "New Visitor",
            'strokeColor'   =>  "#00a65a",
            'pointColor'    => "#00a65a",
            'pointStrokeColor'  => "#00a65a",
            'pointHighlightFill'    => "#fff",
            'pointHighlightStroke' =>   "#00a65a",
        ];
        $rs['datasets'][2] = [
            'label' =>  "Pageviews",
            'strokeColor'   =>  "#f39c12",
            'pointColor'    => "#f39c12",
            'pointStrokeColor'  => "#f39c12",
            'pointHighlightFill'    => "#fff",
            'pointHighlightStroke' =>   "#f39c12",
        ];
        $rs['datasets'][3] = [
            'label' =>  "Bounce Rates",
            'strokeColor'   =>  "#dd4b39",
            'pointColor'    => "#dd4b39",
            'pointStrokeColor'  => "#dd4b39",
            'pointHighlightFill'    => "#fff",
            'pointHighlightStroke' =>   "#dd4b39",
        ];
        foreach($response['rows'] as $item){

            $rs['datasets'][0]['data'][] = $item[1];
            $rs['datasets'][1]['data'][] = $item[2];
            $rs['datasets'][2]['data'][] = $item[3];
            $rs['datasets'][3]['data'][] = $item[4];
        }

        $startDate = new \Carbon\Carbon('first day of January 2014');
        $endDate = Carbon::today();

        $period = Period::create($startDate,$endDate);
        $response = \Analytics::performQuery(
            $period,
            'ga:users,ga:newUsers,ga:pageviews,ga:bounceRate'
        );
        $rs['total'] = $response['rows'][0];

        return response()->json($rs);
    }
}