<?php

class ProjectService {

    public function trackProjectProgress($acceptedDate, $status, $storyId) {

        $storyDao = new StoryDao();
        $story = $storyDao->getStory($storyId);
        $previousStatus = $story->getStatus();
        $projectId = $story->getProjectId();

        $projectProgressDao = new ProjectProgressDao();

        if (($status == 'Accepted') && ($previousStatus != 'Accepted')) {

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $acceptedDate);
            if ($projectProgress[0]->getProjectId() == null) {
                $projectProgressDao->addProjectProgress($projectId, $acceptedDate, $story->getEstimation(), 2);
            } else {
                $workCompleted = $projectProgress[0]->getWorkCompleted();
                $workCompleted += $story->getEstimation();
                $projectProgressDao->updateProjectProgress($projectId, $acceptedDate, $workCompleted);
            }
        } elseif (($status != 'Accepted') && ($previousStatus == 'Accepted')) {
            $oldDate = $story->getAcceptedDate();
            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $oldDate);
            $workCompleted = $projectProgress[0]->getWorkCompleted();


            $workCompleted -= $story->getEstimation();
            $projectProgressDao->updateProjectProgress($projectId, $oldDate, $workCompleted);
        } elseif (($status == 'Accepted') && ($previousStatus == 'Accepted')) {

            $oldDate = $story->getAcceptedDate();
            $newDate = $acceptedDate;

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

    public function trackProjectProgressAddStory($acceptedDate, $status, $projectId, $estimation) {

        $projectProgressDao = new ProjectProgressDao();
        if ($status == 'Accepted') {

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $acceptedDate);

            if ($projectProgress[0]->getProjectId() == null) {
                $projectProgressDao->addProjectProgress($projectId, $acceptedDate, $estimation, 2);
            } else {
                $workCompleted = $projectProgress[0]->getWorkCompleted();
                $workCompleted += $estimation;
                $projectProgressDao->updateProjectProgress($projectId, $acceptedDate, $workCompleted);
            }
        }
    }

    public function trackProjectProgressDeleteStory($storyId) {

        $storyDao = new StoryDao();
        $story = $storyDao->getStory($storyId);
        $currentStatus = $story->getStatus();


        if ($currentStatus == 'Accepted') {

            $projectId = $story->getProjectId();
            $acceptedDate = $story->getAcceptedDate();

            $projectProgressDao = new ProjectProgressDao();
            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $acceptedDate);
            $workCompleted = $projectProgress[0]->getWorkCompleted() - $story->getEstimation();
            $projectProgressDao->updateProjectProgress($projectId, $acceptedDate, $workCompleted);
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



        foreach ($progressValues as $values) {

            if (!isset($weeklyVelocity[$this->CalculateWeekStartDate($values->getAcceptedDate())])) {

                $weeklyVelocity[$this->CalculateWeekStartDate($values->getAcceptedDate())] = 0;
            }

            if (!isset($burnDownArray[$this->CalculateWeekStartDate($values->getAcceptedDate())])) {

                $burnDownArray[$this->CalculateWeekStartDate($values->getAcceptedDate())] = 0;
            }

            $weeklyVelocity[$this->CalculateWeekStartDate($values->getAcceptedDate())] += $values->getWorkCompleted();

            $storeWorkCompleted += $values->getWorkCompleted();
            $workCompletedArray[$this->CalculateWeekStartDate($values->getAcceptedDate())] = $storeWorkCompleted;

            $burnDown = $totalEstimatedEffort - $storeWorkCompleted;
            $burnDownArray[$this->CalculateWeekStartDate($values->getAcceptedDate())] = $burnDown;

            $weekStartings[$j] = $this->CalculateWeekStartDate($values->getAcceptedDate());
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