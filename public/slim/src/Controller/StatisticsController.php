<?php
namespace App\Controller;

use App\Library\ApiSuccessResponse;

class StatisticsController extends Controller {

    public function index($request, $response, $args) {
        $repo = $this->getRepository('Statistics');

        $date_start = new \DateTime('first day of this month');
        $date_end = new \DateTime();

        $data = $request->getParsedBody();
        if ($data)
        {
            if (isset($data['date_start']) && isset($data['date_end']))
            {
                $date_start_ = \DateTime::createFromFormat('Y-m-d', $data['date_start']);
                $date_end_ = \DateTime::createFromFormat('Y-m-d', $data['date_end']);

                if ($date_end_ >= $date_start_)
                {
                    $date_start = $date_start_;
                    $date_end = $date_end_;
                }
            }
        }

        $last_month_start = new \DateTime('first day of last month');
        $last_month_end = new \DateTime('last day of last month');

        return $this->ci->view->render($response, 'Statistics/index.html.twig', [
            'toiletCount' => $repo->toiletCount(),
            'newToiletCount' => $repo->newToiletCount(),
            'descriptionCount' => $repo->descriptionCount(),
            'pictures' => $repo->pictureCount(),
            'rates' => $repo->rateCount(),
            'comments' => $repo->commentCount(),

            'newToiletCountLastMonth' => $repo->newToiletCountFromTo($last_month_start, $last_month_end),
            'descriptionCountLastMonth' => $repo->descriptionCountFromTo($last_month_start, $last_month_end),
            'picturesLastMonth' => $repo->pictureCountFromTo($last_month_start, $last_month_end),
            'ratesLastMonth' => $repo->rateCountFromTo($last_month_start, $last_month_end),
            'commentsLastMonth' => $repo->commentCountFromTo($last_month_start, $last_month_end),

            'newToiletCountCustomPeriod' => $repo->newToiletCountFromTo($date_start, $date_end),
            'descriptionCountCustomPeriod' => $repo->descriptionCountFromTo($date_start, $date_end),
            'picturesCustomPeriod' => $repo->pictureCountFromTo($date_start, $date_end),
            'ratesCustomPeriod' => $repo->rateCountFromTo($date_start, $date_end),
            'commentsCustomPeriod' => $repo->commentCountFromTo($date_start, $date_end),

            'dateStart' => $date_start->format('Y-m-d'),
            'dateEnd' => $date_end->format('Y-m-d'),
        ]);
    }

};
