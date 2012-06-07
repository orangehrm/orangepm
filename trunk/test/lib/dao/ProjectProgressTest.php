<?php

require_once 'PHPUnit/Framework.php';

class ProjectProgressTest extends PHPUnit_Framework_TestCase {

    public function setup() {
        
        $this->deleteData();
        $this->insertData();
    }

    public function deleteData() {

        $query = Doctrine_Query::create()->delete()->from('ProjectProgress')->execute();
    }

    public function insertData() {

        $projectprogress = new ProjectProgress();
        $projectprogress->setProjectId(2);
        $projectprogress->setAcceptedDate('2011-03-29');
        $projectprogress->setWorkCompleted('20');
        $projectprogress->setUnitOfWork('15');
        $projectprogress->save();

        $projectprogress = new ProjectProgress();
        $projectprogress->setProjectId(3);
        $projectprogress->setAcceptedDate('2011-04-01');
        $projectprogress->setWorkCompleted('18');
        $projectprogress->setUnitOfWork('14');
        $projectprogress->save();
    }

    public function getData($projectId, $acceptedDate) {

        $query = Doctrine_Core::getTable('ProjectProgress')
                        ->createQuery('c')
                        ->where('c.project_id = ?', $projectId)
                        ->andWhere('c.accepted_date= ?', $acceptedDate);
        return $query->execute();
    }

    public function testGetProjectProgress() {

        $dao = new ProjectProgressDao();
        $record = $dao->getProjectProgress(2, '2011-03-29');

        $this->assertEquals(20, $record[0]->getWorkCompleted());
        $this->assertEquals(15, $record[0]->getUnitOfWork());
    }

    public function testAddProjectProgress() {

        $dao = new ProjectProgressDao();
        $dao->addProjectProgress(1, '2011-04-24', 10, 2);
        $projectProgress = $this->getData(1, '2011-04-24');
        $this->assertEquals(10, $projectProgress[0]->getWorkCompleted());
        $this->assertEquals(2, $projectProgress[0]->getUnitOfWork());
    }

    public function testUpdateProjectProgress() {

        $dao = new ProjectProgressDao();
        $dao->updateProjectProgress(3, '2011-04-01', 30);

        $projectProgress = $this->getData(3, '2011-04-01');
        $this->assertEquals(30, $projectProgress[0]->getWorkCompleted());
    }

}

