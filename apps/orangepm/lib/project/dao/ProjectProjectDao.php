<?php

class ProjectProgressDao {

    public function addProjectProgress($projectID, $date, $workCompleted, $unitOfWork) {

        $projectProgress = new ProjectProgress();

        $projectProgress->setProjectId($projectID);
        $projectProgress->setDate($date);
        $projectProgress->setWorkCompleted($workCompleted);
        $projectProgress->setUnitOfWork($unitOfWork);

        $projectProgress->save();
    }

    public function getProjectProgress($projectId, $date) {

        $query = Doctrine_Core::getTable('ProjectProgress')
                        ->createQuery('c')
                        ->where('c.project_id = ?', $projectId)
                        ->andWhere('c.date= ?', $date);
        return $query->execute();
    }

    public function updateProjectProgress($projectId, $date, $workCompleted) {


        $query = Doctrine_Query::create()
                        ->update('ProjectProgress p')
                        ->where('p.project_id = ?', $projectId)
                        ->andWhere('p.date= ?', $date)
                        ->set('p.work_completed', $workCompleted);

        $query->execute();
    }

    public function getRecords($projectId) {

        $query = Doctrine_Core::getTable('ProjectProgress')
                        ->createQuery('c')
                        ->where('c.project_id = ?', $projectId);
         return $query->execute();

    }

}

