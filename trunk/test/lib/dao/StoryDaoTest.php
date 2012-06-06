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

class StoryDaoTest extends PHPUnit_Framework_TestCase {
    //put your code here
    public function setup() {

        $this->deleteData();
        $this->insertData();
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


}
?>
