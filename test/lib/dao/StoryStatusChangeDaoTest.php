<?php

require_once 'PHPUnit/Framework.php';
require_once  sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class StoryStatusChangeDaoTest extends PHPUnit_Framework_TestCase {
    private $storyStatusChengeDao = null;
    
    protected function setUp() {
        $this->storyStatusChengeDao = new StoryStatusChangeDao();
        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/StoryStatusChange.yml');
    }
    
    public function testSaveStoryStatusChange() {
       $storyStatusChange = new StoryStatusChange();
       $storyStatusChange->setStoryId(1);
       $storyStatusChange->setStatus('Accepted');
       $storyStatusChange->setActionDate('2011-12-29');
       $this->storyStatusChengeDao->saveStoryStatusChange($storyStatusChange);
       $result = TestDataService::fetchLastInsertedRecord("StoryStatusChange","id");
       $this->assertEquals($storyStatusChange->getId(), $result->getId());
    }
    
    public function testGetStoryStatusChangeByStoryIdTestCount() {
        $results = $this->storyStatusChengeDao->getStoryStatusChangeByStoryId(1);
        $this->assertEquals(3, count($results));
    }
    
    public function testGetStoryStatusChangeByStoryIdTestStoryId() {
        $results = $this->storyStatusChengeDao->getStoryStatusChangeByStoryId(1);
        foreach ($results as $result) {
            $this->assertEquals(1,$result->getStoryId());
        }
    }
    
    public function testUpdateStoryStatusChange() {
       $storyStatusChange = new StoryStatusChange();
       $storyStatusChange->setId(3);
       $storyStatusChange->setStoryId(1);
       $storyStatusChange->setStatus('Accepted');
       $storyStatusChange->setActionDate('2011-12-29');
       $this->storyStatusChengeDao->updateStoryStatusChange($storyStatusChange);
       $result = TestDataService::fetchObject('StoryStatusChange', 3);
       $this->assertEquals('Accepted', $result->getStatus());
    }
    
    public function testDeleteStoryStatusChengeByStoryId() {
        $count = $this->storyStatusChengeDao->deleteStoryStatusChangeByStoryId(1);
        $this->assertEquals(3,$count);
        $results = $this->storyStatusChengeDao->getStoryStatusChangeByStoryId(1);
        $this->assertEquals(0,count($results));
    }
    
    public function testDeleteStoryStatusChengeById() {
        $count = $this->storyStatusChengeDao->deleteStoryStatusChangeById(1);
        $this->assertEquals(1,$count);
        $results = TestDataService::fetchObject("StoryStatusChange",1);
        $this->assertFalse($results);
    }
}