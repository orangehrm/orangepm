<?php

class ProjectProgressDao {

    public function addProjectProgress($projectID, $acceptedDate, $workCompleted, $unitOfWork) {

        $projectProgress = new ProjectProgress();

        $projectProgress->setProjectId($projectID);
        $projectProgress->setAcceptedDate($acceptedDate);
        $projectProgress->setWorkCompleted($workCompleted);
        $projectProgress->setUnitOfWork($unitOfWork);

        $projectProgress->save();
    }

    public function getProjectProgress($projectId, $acceptedDate) {

        $query = Doctrine_Core::getTable('ProjectProgress')
                        ->createQuery('c')
                        ->where('c.project_id = ?', $projectId)
                        ->andWhere('c.accepted_date= ?', $acceptedDate);
        return $query->execute();
    }

    public function updateProjectProgress($projectId, $acceptedDate, $workCompleted) {


        $query = Doctrine_Query::create()
                        ->update('ProjectProgress p')
                        ->where('p.project_id = ?', $projectId)
                        ->andWhere('p.accepted_date= ?', $acceptedDate)
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

