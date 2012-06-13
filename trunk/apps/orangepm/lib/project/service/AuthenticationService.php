<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthenticationService
 *
 * @author samith
 */
class AuthenticationService {
    //put your code here
    private $projectDao = null;
    
    
    public function __construct() {
        $this->projectDao = new ProjectDao();
    }
    
    /**
     *Set project Dao
     * @param ProjectDao $projectDao 
     */
    public function setProjectDao(ProjectDao $projectDao) {
        $this->projectDao =  $projectDao;
    }
    
    
    public function isProjectEditbleByUser($userId ,$projectId){
        
        $result = $this->projectDao->getProjectUsersByProjectAndUser($userId, $projectId);
        if($result){
            $userType = $result->getUserType();
            
            
            if($userType == User::USER_TYPE_SUPER_ADMIN || $userType == User::USER_TYPE_PROJECT_ADMIN){
                return true;
            }
            
        }
        
        
        return false;
        
    }
    
   
}

?>
