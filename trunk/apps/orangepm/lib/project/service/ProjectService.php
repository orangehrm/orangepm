<?php

class ProjectService {

    public function trackProjectProgressAddStory($date, $status, $projectId, $estimation) {

        $projectProgressDao = new ProjectProgressDao();
        if ($status == 'Accepted') {

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $date);

            if ($projectProgress[0]->getProjectId() == null) {
                $projectProgressDao->addProjectProgress($projectId, $date, $estimation, 2);
            } else {
                $workCompleted = $projectProgress[0]->getWorkCompleted();
                $workCompleted += $estimation;
                $projectProgressDao->updateProjectProgress($projectId, $date, $workCompleted);
            }
        }
    }

    public function trackProjectProgress($date, $status, $storyId) {
//        $conn = Doctrine_Manager::getInstance()->getCurrentConnection();
//        $conn->lastInsertId('continent_id');
//        print_r(Doctrine::getTable('Story')->count());
        $storyDao = new StoryDao();
        $story = $storyDao->getStory($storyId);
        $previousStatus = $story->getStatus();
        $projectId = $story->getProjectId();

        $projectProgressDao = new ProjectProgressDao();

        if (($status == 'Accepted') && ($previousStatus != 'Accepted')) {

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $date);
            if ($projectProgress[0]->getProjectId() == null) {
                $projectProgressDao->addProjectProgress($projectId, $date, $story->getEstimation(), 2);
            } else {
                $workCompleted = $projectProgress[0]->getWorkCompleted();
                $workCompleted += $story->getEstimation();
                $projectProgressDao->updateProjectProgress($projectId, $date, $workCompleted);
            }
        } elseif (($status != 'Accepted') && ($previousStatus == 'Accepted')) {
            $oldDate = $story->getAcceptedDate();
            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $oldDate);
            $workCompleted = $projectProgress[0]->getWorkCompleted();


            $workCompleted -= $story->getEstimation();
            $projectProgressDao->updateProjectProgress($projectId, $oldDate, $workCompleted);
        } elseif (($status == 'Accepted') && ($previousStatus == 'Accepted')) {

            $oldDate = $story->getAcceptedDate();
            $newDate = $date;

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $oldDate);
            $workCompleted = $projectProgress[0]->getWorkCompleted();
            $workCompleted -= $story->getEstimation();
            $projectProgressDao->updateProjectProgress($projectId, $oldDate, $workCompleted);

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $newDate);


            if ($projectProgress[0]->getProjectId() == null) {

                $projectProgressDao->addProjectProgress($projectId, $newDate, $story->getEstimation(), 2);
            } else {
                $workCompleted = $projectProgress[0]->getWorkCompleted();
                $workCompleted += $story->getEstimation();
                $projectProgressDao->updateProjectProgress($projectId, $newDate, $workCompleted);
            }
        }
    }

    public function viewWeeklyProgress($storyList, $projectId) {

        $totalEstimatedEffort = 0;

        foreach ($storyList as $story) {

            $totalEstimatedEffort+=$story->getEstimation();
        }

        $t = new ProjectProgressDao();
        $progressValues = $t->getRecords($projectId);


        $j = 0;
        $burnDown = 0;
        $storeWorkCompleted = 0;
//         $burnDownArray=0;
//        $weekStartingDays=null;
//        $weekStartings=null;
//         $workCompleted=null;


        foreach ($progressValues as $values) {

            if (!isset($weeklyVelocity[$this->CalculateWeekStartDate($values->getDate())])) {

                $weeklyVelocity[$this->CalculateWeekStartDate($values->getDate())] = 0;
            }

            if (!isset($burnDownArray[$this->CalculateWeekStartDate($values->getDate())])) {

                $burnDownArray[$this->CalculateWeekStartDate($values->getDate())] = 0;
            }

            $weeklyVelocity[$this->CalculateWeekStartDate($values->getDate())] += $values->getWorkCompleted();

            $storeWorkCompleted += $values->getWorkCompleted();
            $workCompletedArray[$this->CalculateWeekStartDate($values->getDate())] = $storeWorkCompleted;

            $burnDown = $totalEstimatedEffort - $storeWorkCompleted;
            $burnDownArray[$this->CalculateWeekStartDate($values->getDate())] = $burnDown;

            $weekStartings[$j] = $this->CalculateWeekStartDate($values->getDate());
            $j++;
        }

        return array(array_unique($weekStartings), $totalEstimatedEffort, $weeklyVelocity, $workCompletedArray, $burnDownArray);
    }

    public function CalculateWeekStartDate($date) {

        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
        return date('Y-m-d', $start);
    }

}