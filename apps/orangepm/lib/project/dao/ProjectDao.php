<?php

/**
 * Dao class for retrive the data of Project table
 */
class ProjectDao {

    /**
     * Save project
     * @param $project
     */
    public function saveProject($project) {
        $project->save();
    }

    /**
     * Delete Projects
     * @param $id
     * @return none
     */
    public function deleteProject($id) {

        $project = Doctrine_Core::getTable('Project')->find($id);

        if ($project instanceof Project) {
            $project->setDeleted(Project::FLAG_DELETED);
            $project->save();
        }
    }

    /**
     * Get projects considering deleted or not
     * @param $isActive
     * @return doctrine Project objects
     */
    public function getAllProjects($isActive) {

        $query = Doctrine_Query::create()
                ->from('Project a');

        if ($isActive) {
            $query->addWhere('a.deleted = ?', Project::FLAG_ACTIVE);
        }

        return $query->execute();
    }

    /**
     * Get projects by status considering deleted or not
     * @param $isActive, $statusId
     * @return doctrine project objects
     */
    public function getProjectsByStatus($isActive, $statusId) {

        $query = Doctrine_Query::create()
                ->from('Project a')
                ->where('a.projectStatusId = ?', $statusId);

        if ($isActive) {
            $query->addWhere('a.deleted = ?', Project::FLAG_ACTIVE);
        }

        return $query->execute();
    }

        /**
     * Update projects function 
     * @param $project
     * @return none
     */
    public function updateProject(Project $tempProject) {

        $project = Doctrine_Core::getTable('Project')->find($tempProject->getId());

        if ($project instanceof Project) {
            $project->setName($tempProject->getName());
            $project->setProjectStatusId($tempProject->getProjectStatusId());
            $project->setUserId($tempProject->getUserId());
            $project->setDescription($tempProject->getDescription());
            $project->setStartDate($tempProject->getStartDate());
            $project->setEndDate($tempProject->getEndDate());
            $project->save();
        }
    }
    

    /**
     * Get project
     * @param $projectId
     * @return Doctrine object
     */
    public function getProjectById($projectId) {

        return Doctrine_Core::getTable('Project')->find($projectId);
    }

    /**
     * Get all project status for show in dropdown
     * @return relevent Doctrine objects
     */
    public function getAllProjectStatuses() {

        $query = Doctrine_Core::getTable('ProjectStatus')
                ->createQuery('c');

        return $query->execute();
    }

    /**
     * Get  project status by id
     * @return relevent Doctrine objects
     */
    public function getProjectStatusById($id) {

        return Doctrine_Core::getTable('ProjectStatus')->find($id);
    }

    /**
     * Get all projects considering user type and status 
     * @param $userId, $statusId, $isActive
     * @return relevent Doctrine Project objects
     */
    public function getProjectsByUser($userId, $statusId=Project::PROJECT_STATUS_ALL_ID, $isActive=true) {

        $dao = new UserDao();
        $userType = $dao->getUserById($userId)->getUserType();

        $query = Doctrine_Query::create()
                ->from('Project a');

        if ($isActive) {
            $query->addWhere('a.deleted = ?', Project::FLAG_ACTIVE);
        }

        if ($userType != User::USER_TYPE_SUPER_ADMIN) {
            $query->addWhere('a.userId = ?', $userId);
        }

        if ($statusId != null) {
            $query->addWhere('a.projectStatusId = ?', $statusId);
        }

        $result = $query->execute();

        return count($result) == 0 ? null : $result;
    }

    /**
     * Check whether the user has authentication to do the action
     * @param $userId, $projectId
     * @return boolean
     */
    public function isActionAllowedForUser($userId, $projectId) {

        $project = $this->getProjectById($projectId);
        
        $user = new UserDao();
        $actualUser = $user->getUserById($userId);
        
        if(((count($project)) && ($project instanceof Project)) &&  ((count($actualUser)) && ($actualUser instanceof User))) {  
            
            $actualProjectOwnerUserId = $project->getUserId();            
            $actualUserType = $actualUser->getUserType();
            
            if(($userId == $actualProjectOwnerUserId) || ($actualUserType == User::USER_TYPE_SUPER_ADMIN)) {

                return true;
            }
        }        
        
        return false;
        
    }

}

