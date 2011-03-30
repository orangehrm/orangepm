<?php

class ProjectService {

    public function trackProjectProgressAddStory($date, $status, $projectId, $estimation) {
        
        $projectProgressDao = new ProjectProgressDao();
        if ($status == 'ACCEPTED') {

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

        if (($status == 'ACCEPTED') && ($previousStatus != 'ACCEPTED')) {

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $date);
            if ($projectProgress[0]->getProjectId() == null) {
                $projectProgressDao->addProjectProgress($projectId, $date, $story->getEstimation(), 2);
            } else {
                $workCompleted = $projectProgress[0]->getWorkCompleted();
                $workCompleted += $story->getEstimation();
                $projectProgressDao->updateProjectProgress($projectId, $date, $workCompleted);
            }
        } elseif (($status != 'ACCEPTED') && ($previousStatus == 'ACCEPTED')) {

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $date);
            $workCompleted = $projectProgress[0]->getWorkCompleted();

            $oldDate = $story->getAcceptedDate();
            $workCompleted -= $story->getEstimation();
            $projectProgressDao->updateProjectProgress($projectId, $oldDate, $workCompleted);
        } elseif (($status == 'ACCEPTED') && ($previousStatus == 'ACCEPTED')) {

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


        $t = new ProjectProgressDao();
        $progressValues = $t->getRecords($projectId);


        $i = 0;
        $j = 0;


        foreach ($progressValues as $Values) {


            if (!isset($weekStartingDays[$this->CalculateWeekStartDate($Values->getDate())])) {
                $weekStartingDays[$this->CalculateWeekStartDate($Values->getDate())] = 0;
            }
            $weekStartingDays[$this->CalculateWeekStartDate($Values->getDate())] += $Values->getWorkCompleted();
            $weekStartings[$j] = $this->CalculateWeekStartDate($Values->getDate());
            $j++;
        }

        $totalEstimatedEffort = 0;
        $weeklyVelocity = 0;
        foreach ($storyList as $story) {


            $totalEstimatedEffort+=$story->getEstimation();

            if ($story->getStatus() == 'Accepted') {

                $weeklyVelocity+=$story->getEstimation();
            }

            $story->getDateAdded();
        }

         return array(array_unique($weekStartings),$totalEstimatedEffort,$weekStartingDays);
    }

    public function CalculateWeekStartDate($date) {

        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        return date('Y-m-d', $start);
    }

}

//if (task = Mark Story ACCEPTED) {
//   $date =  DATE ACCEPTED
//   work completed on $date += story size
//   update database (work completed on $date)
//} else if (task = Mark ACCEPTED Story to other state) {
//   $date = DATE ACCEPTED
//   work completed on $date -= story size
//   update database (work completed on $date)
//} else if (task = Change ACCEPTED DATE to NEW ACCEPTED DATE) {
//   $date = DATE ACCEPTED
//   $new_date = NEW ACCEPTED DATE
//
//   work completed on $date -= story size
//   work completed on $new_date += story size
//
//   update database (work completed on $date)
//   update database (work completed on $new date)
//}