<?php

require_once 'PHPUnit/Framework.php';
require_once  sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class ProjectDaoTest extends PHPUnit_Framework_TestCase {
    
    protected $projectDao;

    public function setup() {
        
        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/ProjectDao.yml');
        $this->projectDao = new ProjectDao();
        
    }

    public function testGetProjectsByUserTestCount() {
        
        $result = $this->projectDao->getProjectsByUser(2, 2);
        $this->assertEquals(2, count($result));
        
    }
    
    public function testGetProjectsByUserTestObjectType() {
        
        $result = $this->projectDao->getProjectsByUser(2, 2);

        foreach ($result as $value) {
            $this->assertTrue($value instanceof Project);
        }
        
    }
    
    public function testGetProjectsByUserTestResultValues() {
        
        $result = $this->projectDao->getProjectsByUser(2, 2);
        
        $this->assertEquals(3, $result[0]->getId());
        $this->assertEquals(7, $result[1]->getId());
        
    }    
    
    
}
