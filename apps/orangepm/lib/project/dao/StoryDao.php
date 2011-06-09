<?php

class StoryDao {


    public function saveStory($storyParameters) {


        $story = new Story();

        $story->setName($storyParameters['name']);
        $story->setDateAdded($storyParameters['added date']);
        $story->setEstimation($storyParameters['estimated effort']);
        $story->setProjectId($storyParameters['project id']);
        $story->setStatus($storyParameters['status']);
        $story->setAcceptedDate($storyParameters['accepted date']);

        $story->save();
        return $story;
        
    }

    public function deleteStory($id, $deletedDate) {

        $story = Doctrine_Core::getTable('Story')->find($id);
        $story->setDeleted(Story::FLAG_DELETED);
        $story->setDeletedDate($deletedDate);
        $story->save();
        
    }

   public function getRelatedProjectStories($active, $projectId, $pageNo) {

        if ($active) {
            $pager = new sfDoctrinePager('Story', 50);
            $pager->getQuery()->from('Story a')->where('a.deleted = ?', Project::FLAG_ACTIVE)->andWhere('a.project_id = ?', $projectId)->orderBy('date_added');
            $pager->setPage($pageNo);
            $pager->init();
            return $pager;
        } elseif (!$active) {
            return Doctrine_Core::getTable('Story')->findAll();
        }
        
    }

    public function updateStory($storyParameters) {

        $story = Doctrine_Core::getTable('Story')->find($storyParameters['id']);
        if ($story instanceof Story) {
            $story->setName($storyParameters['name']);
            $story->setEstimation($storyParameters['estimated effort']);
            $story->setDateAdded($storyParameters['added date']);
            $story->setStatus($storyParameters['status']);
            $story->setAcceptedDate($storyParameters['accepted date']);
            $story->save();
        }
        
    }

     public function getStory($storyId){

        return Doctrine_Core::getTable('Story')->find($storyId);

    }

     public function getStoriesForProjectProgress($active, $projectId,$sortBy) {

        if ($active) {
            $query = Doctrine_Core::getTable('Story')
                            ->createQuery('c')
                            ->where('c.project_id = ?', $projectId)
                            ->andWhere('c.deleted = ?', Project::FLAG_ACTIVE)
                            ->orderBy("c.{$sortBy}");
            return $query->execute();
        } elseif (!$active) {
            $query = Doctrine_Core::getTable('Story')
                            ->createQuery('c')
                            ->where('c.project_id = ?', $projectId)
                            ->orderBy("c.{$sortBy}");
            return $query->execute();
        }
    }

}