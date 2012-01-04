<?php
/**
 * Service class for Project Progress
 */
class ProjectService {

    public $isEdited = false;
    public $editedProjectId = Project::PROJECT_STATUS_DEFAULT_ID;
    private $projectDao = null;
    /**
	 * Calculate the Project progress
	 * @param $acceptedDate, $status, $storyId
	 * @return none
	 */
    
    function __construct() {
        $this->projectDao = new ProjectDao();
    }
    
    /**
     * Set project dao
     * @param ProjectDao $projectDao
     * @return null
     */
    public function setProjectDao(ProjectDao $projectDao) {
        $this->projectDao = $projectDao;
    }
    
    /**
     * Get project dao
     * @return ProjectDao
     */
    public function getProjectDao() {
        return $this->projectDao;
    }
    

    /**
	 * View weekly progress
	 * @param $storyList, $projectId
	 * @return weeklyProgress table
	 */
    public function viewWeeklyProgress($storyList, $projectId) {
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
                $newList = $storyDao->getStoriesForProjectProgress(true, $projectId, "statusChangedDate");
                $indexvalue = count($newList) - 1;

                $endDate = $newList[$indexvalue]->getStatusChangedDate();
            }

            $weekStartings = $this->calculateStartingDatesOfWeeks($startingDate, $endDate);
            $j = 0;

            $storeWeeklyEstimation = 0;

            $storyStatusChangeDao = new StoryStatusChangeDao();
            $weeklyStatusChange = null;
            $storeWorkCompleted2 = 0;
            foreach ($storyList as $story) {
                $storyStatusChangeList = $storyStatusChangeDao->getStoryStatusChangeByStoryId($story->getId());
                foreach ($storyStatusChangeList as $storyStatusChange) {
                    if ($weeklyStatusChange[$this->CalculateWeekStartDate($storyStatusChange->getActionDate())][$storyStatusChange->getStatus()] == null) {
                        $weeklyStatusChange[$this->CalculateWeekStartDate($storyStatusChange->getActionDate())][$storyStatusChange->getStatus()] = 0;
                    }
                    $weeklyStatusChange[$this->CalculateWeekStartDate($storyStatusChange->getActionDate())][$storyStatusChange->getStatus()] += $story->getEstimation();
                    
                }
            }

            foreach ($storyList as $story) {
                if (!isset($weeklyTotalEstimation[$this->CalculateWeekStartDate($story->getDateAdded())])) {
                    $weeklyTotalEstimation[$this->CalculateWeekStartDate($story->getDateAdded())] = 0;
                }
                $storeWeeklyEstimation+=$story->getEstimation();
                $weeklyTotalEstimation[$this->CalculateWeekStartDate($story->getDateAdded())] = $storeWeeklyEstimation;
            }
            
           return $this->buildingTable($weekStartings, $weeklyTotalEstimation, $weeklyStatusChange);
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
    public function buildingTable($weekStartings, $weeklyTotalEstimation, $weeklyStatuChange) {

        $reversedWeeklyTotalEstimation = array_reverse($weeklyTotalEstimation);
        $weeklyTotalEstimationStoreValue = 0;

        foreach ($weekStartings as $weekStarting) {
            $temp = end($reversedWeeklyTotalEstimation);
            
            if ($weekStarting == key($reversedWeeklyTotalEstimation)) {
                $weeklyTotalEstimationStoreValue = array_pop($reversedWeeklyTotalEstimation);
                $weeklyTotalEstimationArray[$weekStarting] = $weeklyTotalEstimationStoreValue;
                continue;
            }

            $weeklyTotalEstimationArray[$weekStarting] = $weeklyTotalEstimationStoreValue;
        }

        $statusArray = array("Pending", 'Design', 'Development', 'Development Completed', 'Testing', 'Rework', 'Accepted');
        $weeklyStatuChangeArray = null;
        foreach ($weekStartings as $weekStarting) {
            foreach ($statusArray as $status) {
                if($weeklyStatuChange[$weekStarting][$status] != null) {
                    $weeklyStatuChangeArray[$weekStarting][$status] = $weeklyStatuChange[$weekStarting][$status];
                } else {
                    $weeklyStatuChangeArray[$weekStarting][$status] = 0;
                }
            }
        }
        
        $workCompleted = null;
        $totalAccepedWork = 0;
        foreach ($weekStartings as $weekStarting) {
            $totalAccepedWork += $weeklyStatuChangeArray[$weekStarting]["Accepted"];
            $workCompleted[$weekStarting] = $totalAccepedWork;
            if ($workCompleted[$weekStarting] == null) {
                $workCompleted[$weekStarting] = 0;
            }
        }
        
        foreach ($weekStartings as $weekStarting) {
            $burnDownArray[$weekStarting] = $weeklyTotalEstimationArray[$weekStarting] - $workCompleted[$weekStarting];
        }

        return array($weekStartings, $weeklyTotalEstimationArray, $weeklyStatuChangeArray ,$workCompleted, $burnDownArray);
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
     * Get Project by id
     * @param $projectId
     * @return Collection
     */
    public function getProjectById($projectId){
        return $this->projectDao->getProjectById($projectId);
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
    * save project
    * @param $project
    */ 
    public function saveProject($project) {
        
        $dao = new ProjectDao();
        $dao->saveProject($project);
        
    }
       
    /**
     * Get all projects considering user type and status 
     * @param $userId, $statusId, $isActive
     * @return relevent Doctrine Project objects
     */
    public function getProjectsByUser($userId, $statusId=Project::PROJECT_STATUS_ALL_ID, $isActive=true) {
        
        $dao = $this->projectDao;
        
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