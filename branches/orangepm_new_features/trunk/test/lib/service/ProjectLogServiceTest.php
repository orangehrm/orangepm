<?php

require_once 'PHPUnit/Framework.php';
require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class ProjectLogServiceTest extends PHPUnit_Framework_TestCase {

    protected $fixture;
    protected $projectLogService = null;
    
    protected function setUp() {
        $this->fixture = sfConfig::get('sf_test_dir') . '/fixtures/ProjectLog.yml';
        $this->projectLogService = new ProjectLogService();
        TestDataService::populate($this->fixture);
    }
    
    public function testGetLogItemById() {
        $projectLogList = TestDataService::loadObjectList('ProjectLog', $this->fixture, 'ProjectLog');

        $projectLogDao = $this->getMock('ProjectLogDao');
        $projectLogDao->expects($this->once())
            ->method('getLogItemById')
            ->with(1)
            ->will($this->returnValue($projectLogList[3]));
        $this->projectLogService->setProjectLogDao($projectLogDao);
        $result = $this->projectLogService->getLogItemById(1);
        $this->assertEquals($projectLogList[3]['description'],$result['description']);
    }
    
    public function testGetLogItemListByProjectId() {
        $projectLogList = TestDataService::loadObjectList('ProjectLog', $this->fixture, 'ProjectLog');
        $fileterdProjectLogList = array($projectLogList[1], $projectLogList[2], $projectLogList[3], $projectLogList[4]);
        $projectLogDao = $this->getMock('ProjectLogDao');
        $projectLogDao->expects($this->once())
            ->method('getLogItemListByProjectId')
            ->with(1)
            ->will($this->returnValue($fileterdProjectLogList));
        $this->projectLogService->setProjectLogDao($projectLogDao);
        $result = $this->projectLogService->getLogItemListByProjectId(1);
        $this->assertEquals($projectLogList[1]['description'],$result[0]['description']);
    }
    
    public function testSaveLogItem() {
        $projectLog = new ProjectLog();
        $projectLog->setProjectId(1);
        $projectLog->setAddedBy(3);
        $projectLog->setDescription("This is to save test");
        $projectLog->setLoggedDate('2011-12-01');
        $projectLogDao = $this->getMock('ProjectLogDao');
        $projectLogDao->expects($this->once())
            ->method('saveLogItem')
            ->with($projectLog);
        $this->projectLogService->setProjectLogDao($projectLogDao);
        $this->projectLogService->saveLogItem($projectLog);
    }
    
    public function testDeleteLogItem() {
        $projectLogDao = $this->getMock('ProjectLogDao');
        $projectLogDao->expects($this->once())
            ->method('deleteLogItem')
            ->with(1)
            ->will($this->returnValue(1));
        $this->projectLogService->setProjectLogDao($projectLogDao);
        $result = $this->projectLogService->deleteLogItem(1);
        $this->assertEquals(1, $result);
    }
    
    public function testUpdateLogItem() {
        $projectLog = new ProjectLog();
        $projectLog->setId(1);
        $projectLog->setDescription("This is to save test");
        $projectLogDao = $this->getMock('ProjectLogDao');
        $projectLogDao->expects($this->once())
            ->method('updateLogItem')
            ->with($projectLog)
            ->will($this->returnValue(1));
        $this->projectLogService->setProjectLogDao($projectLogDao);
        $result = $this->projectLogService->updateLogItem($projectLog);
        $this->assertEquals(1, $result);
    }
}