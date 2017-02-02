<?php namespace Modules\Ganalytic\Widgets;

use Modules\Villamanager\Repositories\BookingRepository;
use Modules\Dashboard\Foundation\Widgets\BaseWidget;
use Spatie\Analytics\Period;

class GoogleAnalyticWidget extends BaseWidget
{
    /**
     * @var \Modules\Blog\Repositories\PostRepository
     */
    public function __construct()
    {
        
    }
    /**
     * Get the widget name
     * @return string
     */
    protected function name()
    {
        return 'GanalyticWidget';
    }
    /**
     * Get the widget view
     * @return string
     */
    protected function view()
    {
        return 'ganalytic::widgets.analytic';
    }
    /**
     * Get the widget data to send to the view
     * @return string
     */
    protected function data()
    {

        $startDate = new \Carbon\Carbon('first day of january 2010');
        $endDate = new \Carbon\Carbon('last day of this month');

        $period = Period::create($startDate,$endDate);

        try {
            $response = \Analytics::performQuery(
                $period,
                'ga:users,ga:newUsers,ga:pageviews,ga:bounceRate'
            );

            $data = collect($response['rows'] ?? [])->map(function (array $items) {
                return [
                    'visitors' => (int) $items[0],
                    'newVisitors' => (int) $items[1],
                    'pageViews' => (int) $items[2],
                    'bounceRate' => (int) $items[3],
                ];
            });

            return ['data' => $data[0]];
        }
        catch (\Exception $e){
            return ['data' => [
                'visitors' => (int) 0,
                'newVisitors' => (int) 0,
                'pageViews' => (int) 0,
                'bounceRate' => (int) 0,
            ]];
        }


    }
     /**
     * Get the widget type
     * @return string
     */
    protected function options()
    {
        return [
            'width' => '12',
            'height' => '3',
            'y' => '0',
        ];
    }
}