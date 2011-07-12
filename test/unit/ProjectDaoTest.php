<?php

require_once 'PHPUnit/Framework.php';
require_once  sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class ProjectDaoTest extends PHPUnit_Framework_TestCase {
    
    protected $projectDao;

    public function setup() {
        
        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/ProjectDao.yml');
        $this->projectDao = new ProjectDao();
        
    }

    /* Tests for getProjectsByUser() */
    
    public function testGetProjectsByUserTestCount() {
        
        $result = $this->projectDao->getProjectsByUser(2, 2);
        $this->assertEquals(2, count($result));
        
        $result = $this->projectDao->getProjectsByUser(4, 1);
        $this->assertEquals(1, count($result));
        
        $result = $this->projectDao->getProjectsByUser(1, 2);
        $this->assertEquals(4, count($result));       
        
    }
    
    public function testGetProjectsByUserTestObjectType() {
        
        $result = $this->projectDao->getProjectsByUser(2, 2);

        foreach ($result as $value) {
            $this->assertTrue($value instanceof Project);
        }
        
        $result = $this->projectDao->getProjectsByUser(4, 1);

        foreach ($result as $value) {
            $this->assertTrue($value instanceof Project);
        }
    }
    
    public function testGetProjectsByUserTestResultValues() {
        
        $result = $this->projectDao->getProjectsByUser(2, 2);
        
        $this->assertEquals(3, $result[0]->getId());
        $this->assertEquals(7, $result[1]->getId());
        
        $result = $this->projectDao->getProjectsByUser(4, 1);
        
        $this->assertEquals(10, $result[0]->getId());

    }    
    
    public function testGetProjectsByUserTestWrongInputs() {
        
        $result = $this->projectDao->getProjectsByUser(2, 4);
        $this->assertNull($result);

        $result = $this->projectDao->getProjectsByUser(1, 6);
        $this->assertNull($result);
 
    }


    /* Tests for isActionAllowedForStory() */
    public function testIsActionAllowedForStoryTestCorrectInputs() {
        
        $result = $this->projectDao->isActionAllowedForUser(4, 7);
        $this->assertEquals(false, $result);
        
        $result = $this->projectDao->isActionAllowedForUser(1, 6);
        //$this->assertEquals(true, $result);
        
        $result = $this->projectDao->isActionAllowedForUser(3, 6);
        $this->assertEquals(true, $result);
        
    }
    
    public function testIsActionAllowedForStoryTestWrongInputs() {
        
        $result = $this->projectDao->isActionAllowedForUser(4, 14);
        $this->assertEquals(false, $result);
        
        $result = $this->projectDao->isActionAllowedForUser(7, 3);
        $this->assertEquals(false, $result);
        
        $result = $this->projectDao->isActionAllowedForUser(7, 14);
        $this->assertEquals(false, $result);
        
    }
    
}
