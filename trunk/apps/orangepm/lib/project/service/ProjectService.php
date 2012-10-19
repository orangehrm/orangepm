<?php
/**
 * Service class for Project Progress
 */
class ProjectService {

    public $isEdited = false;
    public $editedProjectId = Project::PROJECT_STATUS_DEFAULT_ID;
    private $projectDao = null;
    private $storyDao = null;
    /**
	 * Calculate the Project progress
	 * @param $acceptedDate, $status, $storyId
	 * @return none
	 */
    
    function __construct() {
        $this->projectDao = new ProjectDao();
        $this->storyDao=new StoryDao();
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
     * Set story dao
     * @param StoryDao $storyDao
     * @return null
     */
    public function setStoryDao(StoryDao $storyDao) {
        $this->storyDao = $storyDao;
    }
    
    /**
     * Get project dao
     * @return ProjectDao
     */
    public function getProjectDao() {
        return $this->projectDao;
    }
    
    /**
     * Get story dao
     * @return StoryDao
     */
    public function getStoryDao() {
        return $this->storyDao;
    }
    
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
            $weeklyVelocity = array();
            $workCompletedArray = array();

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
	 * @return week starting date
	 */
    public function CalculateWeekStartDate($date) {
        if(date("N", strtotime($date)) != 1) {
            return date('Y-m-d', strtotime('Last Monday', strtotime($date." 00:00")));
        } else {
            return date('Y-m-d', strtotime($date." 00:00"));
        }
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
        
        $this->projectDao->saveProject($project);
        
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
    
    /**
     * Update an existing project
     * @author Eranga
     * @param $projectId
     */
    public function updateProject($project,$projectUsersColl=null,$deleteProjUsers=true) { 
    
        $this->projectDao->updateProject($project,$projectUsersColl,$deleteProjUsers);        
    }
    
    /**
     * Getting projects for a particular user
     * @author Eranga
     * @param $userID
     */
    public function getProjectUsersByUser($userId) {        
        return $this->projectDao->getProjectUsersByUser($userId);        
    }
    
    /**
     * 
     * @param type $userId
     * @param type $statusId
     * @return array 
     */
    public function getProjectsByUserIdAndStatusId($userId, $statusId) {
        $projectUser = $this->projectDao->getProjectUsersByUser($userId, $statusId, true);
        if($projectUser!=NULL){
            $projects=array();
            foreach($projectUser as $value) {
                array_push($projects,  $value->getProject());
            }
            return $projects; 
        }        
        return NULL;
    }


    /**
     * Get the percentage
     * @param $value
     * @param $total
     * @return Integer
     */
    public function getPecentage($value, $total) {
        if ($value != 0) {
            return round(($value/$total)*100, 2);
        } else {
            return 0;
        }
    }

    
    /**
     *
     * Return the user type
     * @author Samith
     * @param type $userId
     * @param type $projectId
     * @return type - null if parameters are invalid , otherwise 
     */
    public function getProjectUserType($userId, $projectId){
        
        $userType = null;
        $result = $this->projectDao->getProjectUsersByProjectAndUser($userId, $projectId);
        if($result){
            $userType = $result->getUserType();
            
        }
        
        return $userType;
    }
    
    /*
     * Get all project members for a project
     * @param $projectId
     */
    public function getProjectUsersByProjectId($projectId) {
        $result=$this->projectDao->getProjectUsersByProjectId($projectId);
        return $result;
    }
    
    /*
     * get all users in project
     * @param $projectId
     * @return included Users name array
     */
    
    public function getUsersForProjectAsArrayOnlyName($projectId) {
        $projectUser = $this->projectDao->getProjectUsersByProjectId($projectId);
        $userArray=array();
        if($projectUser==null){
            return $userArray;
        }
        
        foreach($projectUser as $value) {
            $user=$value->getUser();
            //array_push($includedUsers,  $value->getUser());
            $userArray[$user->getId()] = $user->getFirstName().' '.$user->getLastName();
        }
        return $userArray; 
    }
    
    /*
     * Get project stories related to the project
     * @author eranga
     * @param $active - weather prject is deleted or not
     * @param $projectId - id of the related project for the stories
     * @param $pageNo - Number of the page of the data set after paging
     */
    public function getRelatedProjectStories($active, $projectId, $pageNo){
        return $this->getStoryDao()->getRelatedProjectStories($active, $projectId, $pageNo);
    }
    
     public function getUsersByProjectId($projectId)  {
        
        $list = array();
        $this->userDao = new UserDao();
        $this->projectDao = new ProjectDao();
        $users = $this->projectDao->getUsersByProjectId($projectId);
         
        foreach ($users as $user) {
            
            $userName = $this->userDao->getUserById($user->getUserId());
            $list[$user->getUserId()] = $userName->getFirstName().' '.$userName->getLastName();
                
         } 
        
        return $list;
        
    }
    
    /**
     * 
     * Get Project Details relevent to the project Id
     * @param Interger $projectId
     * @return Project Status array
     * @author Senura
     * 
     */
    public function getProjectStatus($projectId) {
        
        $startDate1 = $this->getProjectById($projectId)->getStartDate();
        $endDate1 = $this->getProjectById($projectId)->getEndDate();       
        $this->storyList = $this->storyDao->getStoriesForProjectProgress(true, $projectId, "date_added");     
        $allArray = $this->viewWeeklyProgress($this->storyList, $projectId);
        $weekStartingDate = $allArray[0];
        $totalEstimation = $allArray[1];
        $weeklyVelocity = $allArray[2];
        $workCompleted = $allArray[3];
        $this->burnDownArray = $allArray[4];
        
        $endDate = strtotime($endDate1);
        $startDate = strtotime($startDate1);
        
        if ($endDate != 0 && $startDate != 0) {
            $timeBetween = $endDate - $startDate;
        } else {
            $timeBetween = 0;
        }
        
        $dayCount = ($timeBetween / 24 / 60 / 60);
        $totalNumberOfWeeks = ($dayCount+1) / 7;
        reset($weekStartingDate);
        
        if (strtotime($weekStartingDate[count($weekStartingDate)]." +7 days") <=  strtotime(date('Y-m-d'))) {
             $lastWeekVelocity = $weeklyVelocity[$weekStartingDate[count($weekStartingDate)]];
             $totalAcceptedWork = $workCompleted[$weekStartingDate[count($weekStartingDate)]];
             $totalEstimatedValue = $totalEstimation[$weekStartingDate[count($weekStartingDate)]];
        } elseif (key($weekStartingDate) == 0) {
            $totalEstimatedValue = $totalEstimation[$weekStartingDate[count($weekStartingDate)-1]];
            $totalAcceptedWork = $workCompleted[$weekStartingDate[count($weekStartingDate)-1]];
            $lastWeekVelocity = $weeklyVelocity[$weekStartingDate[count($weekStartingDate)-2]];
        } else {
            $totalEstimatedValue = $totalEstimation[$weekStartingDate[count($weekStartingDate)]];
            $totalAcceptedWork = $workCompleted[$weekStartingDate[count($weekStartingDate)]];
            $lastWeekVelocity = $weeklyVelocity[$weekStartingDate[count($weekStartingDate)-1]];
        }
        
        $currentDate = strtotime(date('Y-m-d')." -1 days");
        $TimeCompleted = ($currentDate - $startDate);           
        $weeksCompleted = ($TimeCompleted / 24 / 60 / 60);       
        $actualNumOfWeeksCompleted = ($weeksCompleted) / 7;
        
        if($actualNumOfWeeksCompleted > $totalNumberOfWeeks || $weeksCompleted <= 0) {
            $actualNumOfWeeksCompleted = null;
        }
        $numOfWeeksCompleted = $actualNumOfWeeksCompleted;
        
        
        if ($numOfWeeksCompleted != 0 && $totalAcceptedWork != 0 && $numOfWeeksCompleted < 1 || $numOfWeeksCompleted == 0) {
            $avgWeeklyVelocity = round($totalAcceptedWork, 2).' hours per week';
        } else 
        if ($numOfWeeksCompleted != 0 && $totalAcceptedWork != 0 && $numOfWeeksCompleted >= 1) { 
            $avgWeeklyVelocity = round($totalAcceptedWork / $numOfWeeksCompleted, 2).' hours per week';
        } else {
            $avgWeeklyVelocity = 0;
        }
        
 
       
        $remainingTime = $endDate - $currentDate;
        $remainingDays = ($remainingTime / 24 / 60 / 60);
        $remainingWeeks = ($remainingDays) / 7;
        $remainingWeeks = round($remainingWeeks, 2);
        if($remainingWeeks < 0) {
            $remainingWeeks = 0;
        } 
        $remainingWork = $totalEstimatedValue - $totalAcceptedWork;
        if ($remainingWork !=0 && $remainingWeeks != 0) {
            $reqWeeklyVelocity = round($remainingWork / $remainingWeeks , 2).' hours per week';
        } else {
            $reqWeeklyVelocity = 0;
        }
        
        
        if ($numOfWeeksCompleted < 3 || $remainingWeeks == 0) {
            $varianceBasedonLKV = null;
        } else {
            
            foreach (array_slice($weeklyVelocity, -4, 3,true) as $k => $v) { 
                if($v != 0 ) {
                    $lastKnwnVelocity = $lastKnwnVelocity+$v;

                }     
            }
                $avgLastThreeWeeks = $lastKnwnVelocity / 3;
            if ($avgLastThreeWeeks != 0 && $remainingWork != 0) {
                $varianceOfWeeks = $remainingWork / $avgLastThreeWeeks;
            } 
            if (($varianceOfWeeks < $remainingWeeks) && ($varianceOfWeeks != 0)) {  
                $varianceBasedonLKV = round($remainingWeeks - $varianceOfWeeks, 2).' (weeks ahead)';
            } else if(($varianceOfWeeks == $remainingWeeks) || ($varianceOfWeeks == 0)) {
                $varianceBasedonLKV = 0;
            } else {
                $varianceBasedonLKV = round($varianceOfWeeks - $remainingWeeks, 2).' (weeks Delayed)';
            }
        
        }
       
        
        if ($remainingWeeks == 0) {
            $varianceBasedonAWV = null;
        } else {
            
            if ($avgWeeklyVelocity != 0 && $remainingWork != 0 ) {
                $avgVarianceOfWeeks = $remainingWork / $avgWeeklyVelocity;
            } 
            
            if (($avgVarianceOfWeeks < $remainingWeeks) && ($avgVarianceOfWeeks != 0)) {  
                $varianceBasedonAWV = round($remainingWeeks - $avgVarianceOfWeeks, 2).' (weeks ahead)';
            } else if (($avgVarianceOfWeeks == $remainingWeeks) || ($avgVarianceOfWeeks == 0)) {
                $varianceBasedonAWV = 0;
            } else {
                $varianceBasedonAWV = round($avgVarianceOfWeeks - $remainingWeeks, 2).' (weeks Delayed)';
            }
            
        }
          
        $estimatedTotalEffort = $dayCount.' days';
        
        if ($endDate1 == null) {
            $reqWeeklyVelocity = null;
            $varianceBasedonLKV = null;
            $varianceBasedonAWV = null;
            $estimatedTotalEffort = null;
        } else {      
        $projectStatus = array(round($totalNumberOfWeeks, 2), round($actualNumOfWeeksCompleted, 2), round($remainingWeeks, 2), $avgWeeklyVelocity, $reqWeeklyVelocity, $lastWeekVelocity, $varianceBasedonLKV, $varianceBasedonAWV);
        }
        
        return $projectStatus;
      
   }
   
   public function getProjectsByUserId($loggedUser,$projectId) {
        
       $StoryService = new StoryService(); 
       $list = array();
        
        if($loggedUser == User::USER_TYPE_SUPER_ADMIN){
            
            $projects = $StoryService->getProjectList();
            
        } else {
            
        $projects = $StoryService->getProjectByUserType($loggedUser);
        
        }
        
        foreach ($projects as $project) {
            
            if($project->getDeleted() != 0 && $project->getId() != $projectId)    {
                
                $list[$project->getId()] = $project->getName();
                
            }
            
        } 

        return $list;
    }

}