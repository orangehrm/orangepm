<?php

require_once 'PHPUnit/Framework.php';




class ProjectProgressTest extends PHPUnit_Framework_TestCase {

    public function testAddProjectProgress() {

        $dao = new ProjectProgressDao();
        $dao->addProjectProgress(1,2011-4-24,10,2);
        $record = $dao->getProjectProgress(1,2011-4-24);

        $this->assertEquals(10,$record->getWorkCompleted());
        $this->assertEquals(2,$record->getUnitOfWork());

    }

}

