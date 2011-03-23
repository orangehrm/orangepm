<?php

class StoryDao {

    public function saveStory($name, $date_added, $estimation, $projectId) {

        $story = new Story();
        $story->setName($name);
        $story->setDateAdded($date_added);
        $story->setEstimation($estimation);
        $story->setProjectId($projectId);
        $story->save();
        
    }

    public function deleteStory($id) {

        $story = Doctrine_Core::getTable('Story')->find($id);
        $story->setDeleted(Story::FLAG_DELETED);
        $story->save();
        
    }

   public function getRelatedProjectStories($active, $projectId, $pageNo) {

        if ($active) {
            $pager = new sfDoctrinePager('Story', 10);
            $pager->getQuery()->from('Story a')->where('a.deleted = ?', Project::FLAG_ACTIVE)->andWhere('a.project_id = ?', $projectId);
            $pager->setPage($pageNo);
            $pager->init();
            return $pager;
        } elseif (!$active) {
            return Doctrine_Core::getTable('Story')->findAll();
        }
        
    }

    public function updateStory($id, $updatedName, $updatedEstimation, $updatedDate) {

        $story = Doctrine_Core::getTable('Story')->find($id);
        if ($story instanceof Story) {
            $story->setName($updatedName);
            $story->setEstimation($updatedEstimation);
            $story->setDateAdded($updatedDate);
            $story->save();
        }
        
    }

}