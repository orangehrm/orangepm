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
	 * Get projects
	 * @param $active, $pageNo
	 * @return $pager or $allProjects
	 */
    public function getProjects($active, $pageNo) {

        if ($active) {
            $pager = new sfDoctrinePager('Project', 50);

            $pager->getQuery()->from('Project a')->where('a.deleted = ?', Project::FLAG_ACTIVE);
            $pager->setPage($pageNo);
            $pager->init();
            return $pager;
        } else {
            return $allProjects = Doctrine_Core::getTable('Project')->findAll();
        }
        
    }

    /**
	 * Get projects by status
	 * @param $active, $pageNo, $statusId
	 * @return $pager or $allProjects
	 */
    public function getProjectsByStatus($active, $pageNo, $statusId) {

        if ($active) {
            $pager = new sfDoctrinePager('Project', 10);

            $pager->getQuery()->from('Project a')->where('a.deleted = ?', Project::FLAG_ACTIVE)->andWhere('a.projectStatusId = ?', $statusId);
            $pager->setPage($pageNo);
            $pager->init();
            return $pager;
        } else {
            return $allProjects = Doctrine_Core::getTable('Project')->findAll();
        }

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
    

}

