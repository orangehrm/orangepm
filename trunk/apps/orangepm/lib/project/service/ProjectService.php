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


        $startingDate = $storyList[0]->getDateAdded();

        $endDate = 0;
        $indexvalue = 0;

        foreach ($storyList as $story) {

            if (!$story->getStatus() == 'Accepted') {
                $endDate = date('Y-m-d');
                break;
            } else {

            }
        }

        if ($endDate == 0) {
            $storyDao = new StoryDao();
            $newList = $storyDao->getStoriesForProjectProgress(true, $projectId, "accepted_date");
            $indexvalue = count($newList) - 1;

            $endDate = $newList[$indexvalue]->getAcceptedDate();
        }


        $weekStartings = $this->calculateStartingDatesOfWeeks($startingDate, $endDate);

        $j = 0;

        $storeWorkCompleted = 0;
        $totalEstimatedEffort = 0;

        $storeWeeklyEstimation = 0;

        foreach ($storyList as $story) {

            if (!isset($weeklyTotalEstimation[$this->CalculateWeekStartDate($story->getDateAdded())])) {
                $weeklyTotalEstimation[$this->CalculateWeekStartDate($story->getDateAdded())] = 0;
            }
            $storeWeeklyEstimation+=$story->getEstimation();
            $weeklyTotalEstimation[$this->CalculateWeekStartDate($story->getDateAdded())] = $storeWeeklyEstimation;
            $totalEstimatedEffort+=$story->getEstimation();
        }

        $t = new ProjectProgressDao();
        $progressValues = $t->getRecords($projectId);

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
        }


        return $this->buildingTable($weekStartings, $weeklyTotalEstimation, $weeklyVelocity, $workCompletedArray);
    }

    public function CalculateWeekStartDate($date) {

        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
        return date('Y-m-d', $start);
    }

    public function calculateStartingDatesOfWeeks($startDate1, $endDate1) {
        $weekStartingDates = null;

        $j = 1;

        $endDate = strtotime($endDate1);

        $startDate = strtotime($startDate1);

        $timeBetween = $endDate - $startDate;

//find the days
        $dayCount = ceil($timeBetween / 24 / 60 / 60);
//find the names/dates of the days
        for ($i = 0; $i <= $dayCount; $i++) {
            if ($i == 0 && date("l", $startDate) != "Monday") {
//we're starting in the middle of a week.... show 1 earlier week than the code that follows
                for ($s = 1; $s <= 6; $s++) {
                    $newtime = $startDate - ($s * 60 * 60 * 24);
                    if (date("l", $newtime) == "Monday") {
                        $end_of_week = $newtime + (6 * 60 * 60 * 24);
                        $weekStartingDates[0] = date('Y-m-d', $newtime);
                    }
                }
            } else {
                $newtime = $startDate + ($i * 60 * 60 * 24);
                if (date("l", $newtime) == "Monday") {
//Beginning of a week... show it
                    $end_of_week = $newtime + (6 * 60 * 60 * 24);
                    $weekStartingDates[$j] = date('Y-m-d', $newtime);
                    $j++;
                }
            }
        }
        return $weekStartingDates;
    }

    public function buildingTable($weekStartings, $weeklyTotalEstimation, $weeklyVelocity, $workCompletedArray) {

        $reversedWeeklyVelocity = array_reverse($weeklyVelocity);
        $reversedWorkCompleted = array_reverse($workCompletedArray);

        $reversedWeeklyTotalEstimation = array_reverse($weeklyTotalEstimation);

        $weeklyTotalEstimationStoreValue = 0;
        $workCompletedStoreValue = 0;

        foreach ($weekStartings as $weekStarting) {
            $temp = end($reversedWeeklyTotalEstimation);

            if ($weekStarting == key($reversedWeeklyTotalEstimation)) {
                $weeklyTotalEstimationStoreValue = array_pop($reversedWeeklyTotalEstimation);
                $weeklyTotalEstimationArray[$weekStarting] = $weeklyTotalEstimationStoreValue;
                continue;
            }

            $weeklyTotalEstimationArray[$weekStarting] = $weeklyTotalEstimationStoreValue;

        }

        foreach ($weekStartings as $weekStarting) {

            $temp = end($reversedWeeklyVelocity);
            if ($weekStarting == key($reversedWeeklyVelocity)) {

                $weeklyVelocityArray[$weekStarting] = array_pop($reversedWeeklyVelocity);

                continue;
            }

            $weeklyVelocityArray[$weekStarting] = 0;
        }

        foreach ($weekStartings as $weekStarting) {
            $temp = end($reversedWorkCompleted);
            if ($weekStarting == key($reversedWorkCompleted)) {
                $workCompletedStoreValue = array_pop($reversedWorkCompleted);
                $workCompleted[$weekStarting] = $workCompletedStoreValue;
                continue;
            }

            $workCompleted[$weekStarting] = $workCompletedStoreValue;
        }

        foreach ($weekStartings as $weekStarting) {
            $burnDownArray[$weekStarting] = $weeklyTotalEstimationArray[$weekStarting] - $workCompleted[$weekStarting];
        }

        return array($weekStartings, $weeklyTotalEstimationArray, $weeklyVelocityArray, $workCompleted, $burnDownArray);

    }
}