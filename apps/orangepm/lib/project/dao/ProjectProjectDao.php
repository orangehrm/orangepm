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

        $query = Doctrine_Core::getTable('ProjectPrgress')
                        ->createQuery('c')
                        ->where('c.project_id = ?', $projectId)
                        ->andWhere('c.date= ?', $date);
        return $query->execute();
        
    }

    public function updateProjectProgress($projectId, $date, $workCompleted) {

        $query = Doctrine_Core::getTable('ProjectPrgress')
                        ->createQuery('c')
                        ->where('c.project_id = ?', $projectId)
                        ->andWhere('c.date= ?', $date);
        $projectProgress = $query->execute();

        if ($projectProgress instanceof ProjectProgress) {
            $projectProgress->setWorkCompleted($workCompleted);
            $projectProgress->save();
        }
    }

}

