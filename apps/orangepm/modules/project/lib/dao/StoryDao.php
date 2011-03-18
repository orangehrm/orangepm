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

    public function getAllStories($isDeleted) {

        if ($isDeleted) {
            return Doctrine_Core::getTable('Story')->findBy('deleted', Story::FLAG_ACTIVE);
        } elseif (!$isDeleted) {
            return Doctrine_Core::getTable('Story')->findAll();
        }
    }

    public function getRelatedProjectStories($isDeleted, $projectId) {

        if ($isDeleted) {
            $q = Doctrine_Core::getTable('Story')
                            ->createQuery('c')
                            ->where('c.project_id = ?', $projectId)
                            ->andWhere('c.deleted = ?', Story::FLAG_ACTIVE);

            return $q->execute();
        } elseif (!$isDeleted) {
            return Doctrine_Core::getTable('Story')->findAll();
        }
    }

    public function getRelatedProjectStoriesPaged($isDeleted, $projectId, $exam) {

        if ($isDeleted) {

            $pager = new sfDoctrinePager('Story', 2);

            $pager->getQuery()->from('Story a')->where('a.deleted = ?', Project::FLAG_ACTIVE)->andWhere('a.project_id = ?', $projectId);
            $pager->setPage($exam->getRequestParameter('page', 1));
            $pager->init();
            return $pager;
        } elseif (!$isDeleted) {
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