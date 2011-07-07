<?php

/**
 * Dao class for retrive the data of Project table
 */
class ProjectDao {

    /**
     * Save projects
     * @param $name
     */
    public function saveProject($name, $statusId) {

        $project = new Project();
        $project->setName($name);
        $project->setProjectStatusId($statusId);
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
     * Update projects
     * @param $id, $name
     * @return none
     */
    public function updateProject($id, $name, $statusId) {

        $project = Doctrine_Core::getTable('Project')->find($id);

        if ($project instanceof Project) {
            $project->setName($name);
            $project->setProjectStatusId($statusId);
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
    public function getProjectStatusById($id){

        return  Doctrine_Core::getTable('ProjectStatus')->find($id);
    }
    
}

