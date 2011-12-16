<?php

require_once 'PHPUnit/Framework.php';
require_once  sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class ProjectLogDaoTest extends PHPUnit_Framework_TestCase {
    
    protected $projectLogDao;

    protected function setUp() {
        $this->projectLogDao = new ProjectLogDao();
        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/ProjectLog.yml');
    }
    
    public function testGetLogItemByIdTestType() {
        $result = $this->projectLogDao->getLogItemById(1);
        $this->assertTrue($result instanceof ProjectLog);
    }
    
    public function testGetLogItemByIdTestProjectId() {
        $result = $this->projectLogDao->getLogItemById(7);
        $this->assertEquals(4, $result->projectId);
    }
    
    public function testGetLogItemByIdTestDescription() {
        $result = $this->projectLogDao->getLogItemById(7);
        $this->assertNull($result->description);
    }
    
    public function testGetLogItemListByProjectIdTestType() {
        $result = $this->projectLogDao->getLogItemListByProjectId(4);
        $this->assertTrue($result instanceof Doctrine_Collection);
    }
    
    public function testGetLogItemListByProjectIdTestCount() {
        $result = $this->projectLogDao->getLogItemListByProjectId(4);
        $this->assertEquals(4, count($result));
        
        $result = $this->projectLogDao->getLogItemListByProjectId(20);
        $this->assertEquals(0, count($result));
    }
    
    public function testGetLogItemListByProjectIdTestOrder() {
        $result = $this->projectLogDao->getLogItemListByProjectId(4);
        $logs = $result->toArray();
        $this->assertEquals(2, $logs[3]['id']);
    }
    
    public function testSaveLogDaoTestSavedDB() {
        $projectLog = new ProjectLog();
        $projectLog->setProjectId(1);
        $projectLog->setAddedBy(1);
        $projectLog->setLoggedDate('2011-02-05');
        $projectLog->setDescription('This is to test save');
        
        $this->projectLogDao->saveLogItem($projectLog);
        $result = TestDataService::fetchLastInsertedRecord('ProjectLog','loggedDate');
        $this->assertEquals($projectLog->description, $result->description);
    }
    
    public function testDeleteLogDaoTestCount() {
        $result = $this->projectLogDao->deleteLogItem(10);
        $this->assertEquals(1, $result);
    }
    
    public function testDeleteLogDaoTestDeleteDB() {
        $this->projectLogDao->deleteLogItem(9);
        $result2 = TestDataService::fetchObject('ProjectLog', 9);
        $this->assertNull($result2->id);
    }
    
    public function testUpdateLogDaoTestCount() {
        $projectLog = new ProjectLog();
        $projectLog->setId(8);
        $projectLog->setDescription('This is to test update');
        
        $result = $this->projectLogDao->updateLogItem($projectLog);
        $this->assertEquals(1, $result);
    }
    
    public function testUpdateLogDaoTestUpdateDB() {
        $projectLog = new ProjectLog();
        $projectLog->setId(7);
        $projectLog->setDescription('This is to test update');
        
        $this->projectLogDao->updateLogItem($projectLog);
        $result = TestDataService::fetchObject('ProjectLog', 7);
        $this->assertEquals('This is to test update', $result->description);
    }
}
