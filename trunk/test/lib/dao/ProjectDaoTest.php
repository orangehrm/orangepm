<?php

require_once 'PHPUnit/Framework.php';
require_once  sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class ProjectDaoTest extends PHPUnit_Framework_TestCase {

    protected $projectDao;

    public function setup() {

        TestDataService::truncateTables(array('User','Project','Story','ProjectLog','ProjectUser'));
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

        $this->assertEquals(8, $result[0]->getId());

    }

    public function testGetProjectsByUserTestWrongInputs() {

        $result = $this->projectDao->getProjectsByUser(2, 4);
        $this->assertNull($result);

        $result = $this->projectDao->getProjectsByUser(1, 6);
        $this->assertNull($result);
 
    }

    
      
    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByUserTestObjectType(){
        
        $result = $this->projectDao->getProjectUsersByUser(1, 1,true , 3);
        foreach ($result as $value) {
            $this->assertTrue($value instanceof ProjectUser);
        }
        
        $result = $this->projectDao->getProjectUsersByUser(1, 1,true );
        foreach ($result as $value) {
            $this->assertTrue($value instanceof ProjectUser);
        }
    }
    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByUserTestObjectCount(){
        
        $result = $this->projectDao->getProjectUsersByUser(1, 1,true , 3);
        $this->assertEquals(1, count($result));
        
        $result = $this->projectDao->getProjectUsersByUser(1, 1,true );
        $this->assertEquals(2, count($result));
        
        $result = $this->projectDao->getProjectUsersByUser(1, 2,false );
        $this->assertEquals(2, count($result));
        
        $result = $this->projectDao->getProjectUsersByUser(1, null,false );
        $this->assertEquals(4, count($result));
        
        $result = $this->projectDao->getProjectUsersByUser(2, 2,false );
        $this->assertEquals(2, count($result));
    }
    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByUserTestResultValues(){
        
        $result = $this->projectDao->getProjectUsersByUser(1, 1,true );
        $this->assertEquals(3, $result[0]->getUserType());
        
        $result = $this->projectDao->getProjectUsersByUser(1, null,false );
        $this->assertEquals(3, $result[0]->getUserType());
        $this->assertEquals(2, $result[1]->getUserType());
        $this->assertEquals(1, $result[2]->getUserType());
        $this->assertEquals(3, $result[3]->getUserType());
    }
    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByUserTestWrongInputs(){
         $result = $this->projectDao->getProjectUsersByUser(5, 5,true );
         $this->assertNull($result);
         
         $result = $this->projectDao->getProjectUsersByUser(1, 3,true );
         $this->assertNull($result);
        
    }    
    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByProjectIdTestObjectType(){
        
        $result = $this->projectDao->getProjectUsersByProjectId(1);
        foreach ($result as $value) {
            $this->assertTrue($value instanceof ProjectUser);
        }
    }
    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByProjectIdTestObjectCount(){
        $result = $this->projectDao->getProjectUsersByProjectId(1);
        $this->assertEquals(2, count($result));
         
        
        $result = $this->projectDao->getProjectUsersByProjectId(4);
        $this->assertEquals(4, count($result));
    }
    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByProjectIdTestWrongInputs(){
        $result = $this->projectDao->getProjectUsersByProjectId(111);
         $this->assertNull($result);
    }
    
    /**
     * @author Samith
     * @group samith
     */ 
    public function testGetProjectUsersByProjectIdTestResultValues(){
          $result = $this->projectDao->getProjectUsersByProjectId(1);
           $this->assertEquals(1, $result[0]->getUserId());
           $this->assertEquals(1, $result[1]->getUserId());
           $this->assertEquals(2, $result[1]->getUserType());
           
           $result = $this->projectDao->getProjectUsersByProjectId(4);
           $this->assertEquals(1, $result[0]->getUserId());
           $this->assertEquals(4, $result[3]->getUserId());
           $this->assertEquals(1, $result[2]->getUserType());
     }
    
    /* Tests for isActionAllowedForStory() */
    public function testIsActionAllowedForStoryTestCorrectInputs() {

        $result = $this->projectDao->isActionAllowedForUser(3, 9);
        $this->assertEquals(false, $result);

        $result = $this->projectDao->isActionAllowedForUser(3, 3);
        $this->assertEquals(false, $result);

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

    /*
     * function Testing for delete project 
     * @author Guru
     */
    public function testDeleteProject() {
        $this->projectDao->deleteProject(1);
        $result = $this->projectDao->getProjectById(1);
        $this->assertEquals($result->getDeleted(), 0);
    }
    
    public function testGetAllProjectsWithActiveOnly() {
        $projects=$this->projectDao->getAllProjects(1);
        $this->assertEquals(count($projects), 7);
        $this->assertTrue($projects[0] instanceof Project);
    }
    
    public function testGetProjectsByStatus() {
        $projects=$this->projectDao->getProjectsByStatus(true, 3);
        $this->assertEquals(count($projects), 1);
        $this->assertEquals("Duke-NUS People Manager SAP Synchronization", $projects[0]->getName());
        $this->assertTrue($projects[0] instanceof Project);
    }

    /*
     * function Testing saving for ProjectUser
     * @author Eranga
     */
    public function testSaveProjectUsers(){
        
        $project = new Project();
        $project->setName('aaa');
        $project->setUserId(3);
        $project->setStartDate('2011-01-01');
        $this->projectDao->saveProject($project);
        
        $collection=new Doctrine_Collection('ProjectUser');
        $projectUser1=new ProjectUser();
        $projectUser1->setProjectId($project->getId());
        $projectUser1->setUserId(3);        
        $projectUser1->setUserType(1);
        $projectUser1->save();
        $collection->add($projectUser1);
        
        $collection=new Doctrine_Collection('ProjectUser');
        $projectUser2=new ProjectUser();
        $projectUser2->setProjectId($project->getId());
        $projectUser2->setUserId(4);        
        $projectUser2->setUserType(1);
        $projectUser2->save();
        $collection->add($projectUser2);
        
        //$project->setProjectUser($collection);
        $this->projectDao->saveProjectUsers($collection);
        $expected=$this->projectDao->getProjectById($project->getId())->getProjectUser();
        $this->assertEquals(2,$expected->count());
    }
    
    /*
     * function Testing saving for ProjectUser when there are no users defined
     * @author Eranga
     */
    public function testSaveProjectUsersWhenNoUsers(){
        
        $project = new Project();
        $project->setName('aaa');
        $project->setUserId(3);
        $project->setStartDate('2011-01-01');
        $this->projectDao->saveProject($project);
        
        $collection=new Doctrine_Collection('ProjectUser');
        
        $project->setProjectUser($collection);
        $this->projectDao->saveProject($project);
        $expected=$this->projectDao->getProjectById($project->getId())->getProjectUser();
        $this->assertEquals(0,$expected->count());
    }
    
    /*
     * function Testing updating for ProjectUser
     * @author Eranga
     */
    public function testUpdateProjectUsers(){
               
        $projectUser1=new ProjectUser();
        $projectUser1->setProjectId(3);
        $projectUser1->setUserId(5);
        $projectUser1->setUserType(1);
        
        $projectUser2=new ProjectUser();
        $projectUser2->setProjectId(3);
        $projectUser2->setUserId(2);
        $projectUser2->setUserType(2);
        
        $collection=new Doctrine_Collection('ProjectUser');
        $collection->add($projectUser1);
        $collection->add($projectUser2);
        
        $project=$this->projectDao->getProjectById(3);
        $project->setProjectUser($collection);
        $this->projectDao->saveProject($project);
        
        $projectUsers=$project->getProjectUser();
        $this->assertEquals(2, $projectUsers->count());
    }
    
    /*
     * function Testing updating for ProjectUser when no users are defined
     * @author Eranga
     */
    public function testUpdateProjectUsersWhenNoProjectUsers(){                   
        $collection=new Doctrine_Collection('ProjectUser');
        
        $project=$this->projectDao->getProjectById(3);
        $project->setProjectUser($collection);
        $this->projectDao->saveProject($project);
        
        $projectUsers=$project->getProjectUser();
        $this->assertEquals(0, $projectUsers->count());
    }
    

    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByProjectAndUserTestResultValues()
    {
        
        $result = $this->projectDao->getProjectUsersByProjectAndUser(1,2);
        $this->assertEquals(1,$result->getUserType() );
        
        
        
         $result =$this->projectDao->getProjectUsersByProjectAndUser(2,2);
        $this->assertEquals(1, $result->getUserType() );
        
        $result =$this->projectDao->getProjectUsersByProjectAndUser(3,8);
        $this->assertEquals(2, $result->getUserType()  );
                
        
        
    }
    

    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByProjectAndUserTestObjectType(){
        $result = $this->projectDao->getProjectUsersByProjectAndUser(1,2);
        $this->assertTrue($result instanceof ProjectUser);
        
         $result =$this->projectDao->getProjectUsersByProjectAndUser(2,2);
         $this->assertTrue($result instanceof ProjectUser);
    }
    
    
    /**
     * @author Samith
     * @group samith
     */
    public function testGetProjectUsersByProjectAndUserTestWrongInputs(){
        $result =$this->projectDao->getProjectUsersByProjectAndUser(11,11);
        $this->assertTrue(!$result);
        
        $result =$this->projectDao->getProjectUsersByProjectAndUser(55,6);
        $this->assertTrue(!$result);
        
        $result =$this->projectDao->getProjectUsersByProjectAndUser(11,1);
        $this->assertTrue(!$result);
    }
    
}
