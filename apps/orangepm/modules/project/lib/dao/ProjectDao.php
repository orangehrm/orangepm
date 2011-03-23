<?php

class ProjectDao {

    public function saveProject($name) {

        $project = new Project();
        $project->setName($name);
        $project->save();
    }

    public function deleteProject($id) {

        $project = Doctrine_Core::getTable('Project')->find($id);

        if ($project instanceof Project) {
            $project->setDeleted(Project::FLAG_DELETED);
            $project->save();
        }
    }

    public function getAllProjects($isDeleted) {

        if ($isDeleted) {
            return Doctrine_Core::getTable('Project')->findBy('deleted', Project::FLAG_ACTIVE);
        } else {
            return $allProjects = Doctrine_Core::getTable('Project')->findAll();
        }
    }

    public function getProjects($isDeleted,$exam) {

        if ($isDeleted) {
            $pager = new sfDoctrinePager('Project', 2);
            
            $pager->getQuery()->from('Project a')->where('a.deleted = ?', Project::FLAG_ACTIVE);
            $pager->setPage($exam->getRequestParameter('page', 1));
            $pager->init();
            return $pager;
        } else {
            return $allProjects = Doctrine_Core::getTable('Project')->findAll();
        }
    }

    public function updateProject($id, $name) {

        $project = Doctrine_Core::getTable('Project')->find($id);

        if ($project instanceof Project) {
            $project->setName($name);
            $project->save();
        }
    }

}

