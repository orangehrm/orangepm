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
        $weeklyVelocity = 0;
        foreach ($storyList as $story) {


            $totalEstimatedEffort+=$story->getEstimation();
        }

        $t = new ProjectProgressDao();
        $progressValues = $t->getRecords($projectId);


        $i = 0;
        $j = 0;;
        $burnDown = 0;
        $wc = 0;

        foreach ($progressValues as $Values) {


            if (!isset($weekStartingDays[$this->CalculateWeekStartDate($Values->getDate())])) {
                $weekStartingDays[$this->CalculateWeekStartDate($Values->getDate())] = 0;
                
            }
            $weekStartingDays[$this->CalculateWeekStartDate($Values->getDate())] += $Values->getWorkCompleted();
            $wc += $Values->getWorkCompleted();
            $workCompleted[$this->CalculateWeekStartDate($Values->getDate())] = $wc;
            $burnDown = $totalEstimatedEffort - $wc;
            $burnDownArray[$this->CalculateWeekStartDate($Values->getDate())] = $burnDown;
            $weekStartings[$j] = $this->CalculateWeekStartDate($Values->getDate());
            $j++;
        }

        return array(array_unique($weekStartings), $totalEstimatedEffort, $weekStartingDays, $workCompleted, $burnDownArray);
    }

    public function CalculateWeekStartDate($date) {

        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        return date('Y-m-d', $start);
    }

}