<?php

/*
 * Dao class for project status table
 */

class ProjectStatusDao {    
    
   /**
    * Get all project status for show in dropdown
    * @return relevent Doctrine objects
    */ 
    public function getAllProjectStatus() {
        
        $query = Doctrine_Core::getTable('ProjectStatus')
                            ->createQuery('c');
        
        return $query->execute();
        
    }

    public function getProjectStatusByProjectStatusId($id){

        return  Doctrine_Core::getTable('ProjectStatus')->find($id);
    }


}

