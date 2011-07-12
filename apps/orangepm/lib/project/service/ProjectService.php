<?php
/**
 * Service class for Project Progress
 */
class ProjectService {

    public $isEdited = false;
    public $editedProjectId = Project::PROJECT_STATUS_DEFAULT_ID;
    /**
	 * Calculate the Project progress
	 * @param $acceptedDate, $status, $storyId
	 * @return none
	 */
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

    /**
	 * Calculate the Project progress add story
	 * @param $acceptedDate, $status, $projectId, $estimation
	 * @return none
	 */
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

    /**
	 * Calculate the Project progress delete story
	 * @param $storyId
	 * @return none
	 */
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

    /**
	 * View weekly progress
	 * @param $storyList, $projectId
	 * @return weeklyProgress table
	 */
    public function viewWeeklyProgress($storyList, $projectId) {
        $ProjectProgressDaoObject = new ProjectProgressDao();
        $progressValues = $ProjectProgressDaoObject->getRecords($projectId);
        if (!($storyList[0]->getProjectId() == null)) {


            $startingDate = $storyList[0]->getDateAdded();



            $endDate = 0;
            $indexvalue = 0;
            
            foreach ($storyList as $story) {

                if (!($story->getStatus() == 'Accepted')) {
                    $endDate = date('Y-m-d');
                    break;
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
            }


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
    }

    /**
	 * Calculate week start day
	 * @param $date
	 * @return $from
	 */
    public function CalculateWeekStartDate($date) {


        $week = date('W', strtotime($date));
        $year = date('Y', strtotime($date));
        $from = date("Y-m-d", strtotime("{$year}-W{$week}-1"));

        return $from;
    }

    /**
	 * Calculate starting date of weeks
	 * @param $startDate1, $endDate1
	 * @return $weekStartingDates
	 */
    public function calculateStartingDatesOfWeeks($startDate1, $endDate1) {
        $weekStartingDates = null;

        $j = 1;

        $endDate = strtotime($endDate1);

        $startDate = strtotime($startDate1);

        $timeBetween = $endDate - $startDate;

        $dayCount = ceil($timeBetween / 24 / 60 / 60); //find the days
        /*find the names/dates of the days*/
        for ($i = 0; $i <= $dayCount; $i++) {
            if ($i == 0 && date("l", $startDate) != "Monday") {
        /*we're starting in the middle of a week.... show 1 earlier week than the code that follows*/
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
                /*Beginning of a week... show it*/
                    $end_of_week = $newtime + (6 * 60 * 60 * 24);
                    $weekStartingDates[$j] = date('Y-m-d', $newtime);
                    $j++;
                }
            }
        }
        return $weekStartingDates;
    }

    /**
	 * Building the table
	 * @param $weekStartings, $weeklyTotalEstimation, $weeklyVelocity, $workCompletedArray
	 * @return array($weekStartings, $weeklyTotalEstimationArray, $weeklyVelocityArray, $workCompleted, $burnDownArray)
	 */
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
    
   /**
	 * Get the all status to an array 
	 * @return array
	 */ 
    public function getAllProjectStatusesAsArray() {
        
        $dao = new ProjectDao();
        
        $allProjectStatus = $dao->getAllProjectStatuses();
        
        foreach($allProjectStatus as $projectStatus) {
            $projectStatusArray[$projectStatus->getId()] = $projectStatus->getName();
        }
                
        return $projectStatusArray;
        
    }

    /**
     * Get the all Projects according to status
     * @param $isActive, $statusId
	 * @return $allProjects
	 */
    public function getAllProjects($isActive, $statusId){

        $projectDao = new ProjectDao();

        if($statusId == Project::PROJECT_STATUS_ALL_ID) {
            $allProjects = $projectDao->getAllProjects($isActive);
            
        }else {
            $allProjects = $projectDao->getProjectsByStatus($isActive, $statusId);
        }
        
        return $allProjects;
        
    }

    /**
     * Get the relevant Projects status according to id
     * @param $id
	 * @return $status
	 */
    public function getAllProjectStatus($id){

        $projectDao = new ProjectDao();

        if($id == Project::PROJECT_STATUS_ALL_ID) {
            $status = Project::PROJECT_STATUS_ALL;

        }else {
            $status = $projectDao->getProjectStatusById($id)->getName();

        }
        return $status;
    }
    
   /**
    * Get project status by id
    * @param $statusId
    * @return relevent Doctrine ProjectStatus objects
    */
    public function getProjectStatusById($statusId=Project::PROJECT_STATUS_DEFAULT_ID){
        
        $dao = new ProjectDao();        
        return $dao->getProjectStatusById($statusId);

    }
    
   /**
    * save a project
    * @param $name, $statusId
    * @return none
    */ 
    public function saveProject($name, $statusId, $userId) {
        
        $dao = new ProjectDao();
        $dao->saveProject($name, $statusId, $userId);
        
    }
       
    /**
     * Get all projects considering user type and status 
     * @param $userId, $statusId, $isActive
     * @return relevent Doctrine Project objects
     */
    public function getProjectsByUser($userId, $statusId=Project::PROJECT_STATUS_ALL_ID, $isActive=true) {
        
        $dao = new ProjectDao();
        
        if($statusId == Project::PROJECT_STATUS_ALL_ID) {
            
            return $dao->getProjectsByUser($userId);
            
        } else {
            
            return $dao->getProjectsByUser($userId, $statusId, $isActive);
            
        }
    }
    
    /**
     * Check whether the user has authentication to do the action
     * @param $userId, $projectId
     * @return boolean
     */
    public function isActionAllowedForUser($userId, $projectId) {
        
        $dao = new ProjectDao();
        
        return $dao->isActionAllowedForUser($userId, $projectId);
        
    }
    
}