<?php

require_once 'PHPUnit/Framework.php';

class ProjectServiceTest extends PHPUnit_Framework_TestCase {

    public function testTrackProjectProgress() {


//        $projectService = new ProjectService();
//        $projectService->trackProjectProgress('2011-2-2', "ACCEPTED", 1);
//        $projectProgress = $this->_getProjectProgress(1, '2011-2-2');
//        $this->assertEquals(10,$projectProgress[0]->getWorkCompleted());
//
//        $projectService->trackProjectProgress('2011-1-25', "PENDING", 2);
//        $projectProgress = $this->_getProjectProgress(1, '2011-1-25');
//        $this->assertEquals(0,$projectProgress[0]->getWorkCompleted());
//
//        $projectService->trackProjectProgress('2011-2-15', "ACCEPTED", 3);
//        $projectProgress = $this->_getProjectProgress(1, '2011-2-15');
//        $this->assertEquals(15,$projectProgress[0]->getWorkCompleted());
//
//        $projectService->trackProjectProgress('2011-1-25', "ACCEPTED", 4);
//        $projectProgress = $this->_getProjectProgress(1, '2011-1-25');
//        $this->assertEquals(40,$projectProgress[0]->getWorkCompleted());
//
//        $projectService->trackProjectProgressAddStory('2010-6-12', "ACCEPTED", 1, "100");
//        $projectProgress = $this->_getProjectProgress(1, '2010-6-12');
//        $this->assertEquals(100,$projectProgress[0]->getWorkCompleted());
//
//        $projectService->trackProjectProgressAddStory('2010-6-22', "PENDING", 1, "100");
//        $projectProgress = $this->_getProjectProgress(1, '2010-6-22');
//        $this->assertEquals(null,$projectProgress[0]->getWorkCompleted());

    }

    public function setup() {

        $this->deleteTestData();
        $this->insertTestData();
        
    }

    public function deleteTestData() {
        
        $projectQuery = Doctrine_Query::create()->delete()->from('Project')->execute();
        $storyQuery = Doctrine_Query::create()->delete()->from('Story')->execute();
        $projectProgressQuery = Doctrine_Query::create()->delete()->from('ProjectProgress')->execute();
        
    }


     public function _getProjectProgress($projectId, $date) {

        $query = Doctrine_Core::getTable('ProjectProgress')
                        ->createQuery('c')
                        ->where('c.project_id = ?', $projectId)
                        ->andWhere('c.date= ?', $date);
        return $query->execute();

    }

    public function insertTestData() {

        $project = new Project();
        $project->setId(1);
        $project->setName("OrangePM");
        $project->save();

        $story = new Story();
        $story->setId(1);
        $story->setProjectId(1);
        $story->setEstimation(10);
        $story->setName('story1');
        $story->setDateAdded('2011-1-12');
        $story->setStatus('Pending');
        $story->setDeleted(Story::FLAG_ACTIVE);
        $story->save();

        $story = new Story();
        $story->setId(2);
        $story->setProjectId(1);
        $story->setEstimation(5);
        $story->setName('story2');
        $story->setDateAdded('2011-1-13');
        $story->setStatus('Accepted');
        $story->setAcceptedDate('2011-1-25');
        $story->setDeleted(Story::FLAG_ACTIVE);
        $story->save();

        $projectProgress = new ProjectProgress();
        $projectProgress->setProjectId(1);
        $projectProgress->setDate('2011-1-25');
        $projectProgress->setWorkCompleted(5);
        $projectProgress->setUnitOfWork(2);
        $projectProgress->save();

        $story = new Story();
        $story->setId(3);
        $story->setProjectId(1);
        $story->setEstimation(15);
        $story->setName('story3');
        $story->setDateAdded('2011-1-14');
        $story->setStatus('Accepted');
        $story->setAcceptedDate('2011-1-27');
        $story->setDeleted(Story::FLAG_ACTIVE);
        $story->save();

        $projectProgress = new ProjectProgress();
        $projectProgress->setProjectId(1);
        $projectProgress->setDate('2011-1-27');
        $projectProgress->setWorkCompleted(15);
        $projectProgress->setUnitOfWork(2);
        $projectProgress->save();
        $story = new Story();
        
        $story->setId(4);
        $story->setProjectId(1);
        $story->setEstimation(40);
        $story->setName('story4');
        $story->setDateAdded('2011-1-14');
        $story->setStatus('Accepted');
        $story->setAcceptedDate('2011-1-30');
        $story->setDeleted(Story::FLAG_ACTIVE);
        $story->save();

        $projectProgress = new ProjectProgress();
        $projectProgress->setProjectId(1);
        $projectProgress->setDate('2011-1-30');
        $projectProgress->setWorkCompleted(40);
        $projectProgress->setUnitOfWork(2);
        $projectProgress->save();
        
    }

    
}