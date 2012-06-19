<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StoryDaoTest
 *
 * @author orangehrm
 */
require_once 'PHPUnit/Framework.php';
require_once  sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

class StoryDaoTest extends PHPUnit_Framework_TestCase {
    //put your code here
    
    protected $storyDao;
    
    public function setup() {
        TestDataService::truncateTables(array('User','Project','Story','ProjectLog','ProjectUser','Task'));
        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/ProjectDao.yml');
        $this->storyDao = new StoryDao();
    }
    public function insertData() {
        $story = new Story();
        $story->setName("Test Story");
        $story->setId(5);
        $story->setDateAdded("2011-03-30");
        $story->setEstimation("34");
        $story->setProjectId(1);
        $story->setStatus("Accepted");
        $story->setAcceptedDate("2011-03-31");
        $story->save();

        $storyNew = new Story();
        $story->setId(6);
        $storyNew->setName("Test Story Again");
        $storyNew->setDateAdded("2011-03-31");
        $storyNew->setEstimation("35");
        $storyNew->setProjectId(1);
        $storyNew->setStatus("Pending");
        $storyNew->setAcceptedDate("2011-04-01");
        $storyNew->save();
    }

    public function deleteData() {

        $query = Doctrine_Query::create()->delete()->from('Story')->execute();
    }


   public function testUpdateStory(){

      $inputParameters = array(
                    'name' => "Test Story Update",
                    'id'=> 5,
                    'added date' => '2011-04-30',
                    'estimated effort' => '44',
                    'project id' => 1,
                    'status' => "Pending",
                    'accepted date' =>'2011-03-31'
                );

      $dao=new StoryDao();
      $dao->updateStory($inputParameters);

      $query = Doctrine_Core::getTable('Story')
                        ->createQuery('c')
                        ->where('c.id = ?', 5);
      $storyArray = $query->execute();

        $this->assertEquals("Test Story Update", $storyArray[0]->getName());
        $this->assertEquals('44', $storyArray[0]->getEstimation());
        



   }
   /**
    * @author guru
    */
   public function testGetProjectIdByStoryId() {
       $this->assertEquals(6 ,  $this->storyDao->getProjectIdByStoryId(12));
   }
   /**
    *@author guru 
    */
   public function testGetProjectIdByStoryIdNonId() {
       $this->assertEquals(NULL ,  $this->storyDao->getProjectIdByStoryId(15));
   }
   /**
    * @author guru 
    */
   public function testGetEstimationEffortByStoryId() {
       $this->assertEquals(5 ,  $this->storyDao->getEstimationEffortByStoryId(12));
   }
   /**
    * @author guru 
    */
   public function testGetEstimationEffortByStoryIdNonId() {
       $this->assertEquals(NULL ,  $this->storyDao->getEstimationEffortByStoryId(15));
   }
   
   /*
    * @author Eranga
    * Testing updating estimeated end data for story
    */
   public function testUpdateEstimatedEndDate(){
       $date='2011-01-15';
       $storyId=4;
       $this->storyDao->updateEstimatedEndDate($storyId,$date);
       $story=$this->storyDao->getStory($storyId);
       $this->assertEquals($date,$story->getEstimatedEndDate());
   }
   
   /*
    * @author Eranga
    * Testing updating estimeated end data for story when date is null
    */
   public function testUpdateEstimatedEndDateWhenDateIsNull(){
       $date=null;
       $storyId=4;
       $this->storyDao->updateEstimatedEndDate($storyId,$date);
       $story=$this->storyDao->getStory($storyId);
       $this->assertEquals($date,$story->getEstimatedEndDate());
   }
   
   /*
    * @author Eranga
    * Testing updating estimeated end data for story when user id is not in range
    */
   public function testUpdateEstimatedEndDateForInvalidUserId(){
       $date='2011-01-15';
       $storyId=15;
       $this->storyDao->updateEstimatedEndDate($storyId,$date);
       $story=$this->storyDao->getStory($storyId);       
       $this->assertFalse($story);
   }
   
   /*
    * @author Eranga
    * Testing geting tasks for a particular story
    */
    public function testGetTasks(){
       $storyId=1;
       $taskSet=$this->storyDao->getTasks($storyId);
       $this->assertTrue($taskSet[0] instanceof Task);
       $this->assertEquals(2,$taskSet->count());   
   }
   
   /*
    * @author Eranga
    * Testing geting tasks for a particular story when story Id is not defined
    */
   public function testGetTasksForUndefinedStoryId(){
       $storyId=50;
       $taskSet=$this->storyDao->getTasks($storyId);
       $this->assertNull($taskSet);   
   }
   
   /*
    * @author Eranga
    * Testing geting tasks for a particular story when tasks are not defined
    */
   public function testGetTasksForUndefinedTasksForStory(){
       $storyId=2;
       $taskSet=$this->storyDao->getTasks($storyId);
       $this->assertNull($taskSet);   
   }
}
?>
