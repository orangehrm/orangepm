<?php

/**
 * Service class for Story
 * @author Senura
 */
class StoryService {

	private $storyDao;	
	
           
    /**
     * Get StoryDao
     * @return StroyDao $storyDao
     */
    public function getStoryDao() {

        if(!$this->storyDao) {
            $this->storyDao = new StoryDao();
        }

        return $this->storyDao;
        
    }
    
    /**
     * Set StroyDao
     * @param StoryDao $storyDao
     */
    public function setStoryDao(StoryDao $storyDao) {
        $this->storyDao = $storyDao;
    }
    
    /**
     * Get all the projects
     * 
     * @return Doctrine_objects projects
     */
    public function getProjectList() {     
        return $this->getStoryDao()->getProjectList();
   }
   
   /**
    * Move Story to another project
    * 
    * @param Integer $storyId
    * @param Integer $projectId
    * @return Doctrine_object story
    */
    public function moveStory($storyId, $projectId) {   
        return $this->getStoryDao()->moveStory($storyId, $projectId);     
   }
   
    /**
     * Get projects according to the user type
     * 
     * @param Integer $userId
     * @return Doctrine_objects projects
     */
    public function getProjectByUserType($userId) {
        return $this->getStoryDao()->getProjectByUserType($userId);
      }
  }