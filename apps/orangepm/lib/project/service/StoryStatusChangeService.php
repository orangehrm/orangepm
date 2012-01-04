<?php
class StoryStatusChangeService {

    private $storyStatusChangeDao = null;
    private $projectService = null;
    private $storyDao = null;
    function __construct() {
        $this->storyStatusChangeDao = new StoryStatusChangeDao();
        $this->projectService = new ProjectService();
        $this->storyDao = new StoryDao();
    }
    
    public function trackStoryStatusChange($storyId, $newStoryStatus, $newStatusChangedDate, $estimation) {
        $story = $this->storyDao->getStoryById($storyId);
        if (($newStatusChangedDate != $story->getStatusChangedDate()) || ($newStoryStatus != $story->getStatus())) {
            $storyStatusChanges = $this->storyStatusChangeDao->getStoryStatusChangeByStoryId($storyId);
            $storyStatusChangedID = $this->getStoryStatusChangeInSameWeek($storyStatusChanges, $newStatusChangedDate);
            if($storyStatusChangedID != null) {
                $storyStatusChange = new StoryStatusChange();
                $storyStatusChange->setId($storyStatusChangedID);
                $storyStatusChange->setStatus($newStoryStatus);
                $storyStatusChange->setActionDate($newStatusChangedDate);
                $this->storyStatusChangeDao->updateStoryStatusChange($storyStatusChange);
            } else {
                $this->removDuplicatingStatusChanges($storyStatusChanges, $newStoryStatus );
                $storyStatusChange = new StoryStatusChange();
                $storyStatusChange->setStoryId($storyId);
                $storyStatusChange->setStatus($newStoryStatus);
                $storyStatusChange->setActionDate($newStatusChangedDate);
                $this->storyStatusChangeDao->saveStoryStatusChange($storyStatusChange);
            }
        }
    }
    
    public function statusChangeInSameWeek($oldDate, $newDate) {
        $oldWeek = $this->projectService->CalculateWeekStartDate($oldDate);
        $newWeek = $this->projectService->CalculateWeekStartDate($newDate);
        if ($oldWeek == $newWeek) {
            return true;
        } else {
            return false;
        }
    }
    
     public function getStoryStatusChangeInSameWeek($storyStatusChanges, $newStatusChangedDate) {
        foreach ($storyStatusChanges as $storyStatusChange) {
            $isSameWeek = $this->statusChangeInSameWeek($storyStatusChange->getActionDate(), $newStatusChangedDate);
            if ($isSameWeek) {
                return $storyStatusChange->getId();
            }
        }
     }
     
     public function saveStoryStatusChange(Story $story) {
        $storyStatusChange = new StoryStatusChange();
        $storyStatusChange->setStatus($story->getStatus());
        $storyStatusChange->setStoryId($story->getId());
        $storyStatusChange->setActionDate($story->getStatusChangedDate());
        $this->storyStatusChangeDao->saveStoryStatusChange($storyStatusChange);
     }
     
     public function removDuplicatingStatusChanges($storyStatusChanges, $newStoryStatus ) {
        foreach ($storyStatusChanges as $storyStatusChange) {
            if($storyStatusChange->getStatus() == $newStoryStatus) {
                $this->storyStatusChangeDao->deleteStoryStatusChangeById($storyStatusChange->getId());
            }
        }
     }
     
}