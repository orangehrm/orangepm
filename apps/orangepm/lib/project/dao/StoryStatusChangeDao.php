<?php

/**
 * Dao class for retrieve data form storyStatusChange class
 */
class StoryStatusChangeDao {
    
    /**
     * Save poject status change
     * @param ProjectStatusChange $projectStatusChange
     */
    public function saveStoryStatusChange(StoryStatusChange $storyStatusChange) {
        try {
            $storyStatusChange->save();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Get story status change by story id 
     * @param $storyId
     * @return Doctrine Collection
     */
    public function getStoryStatusChangeByStoryId($storyId) {
    try {
            $query = Doctrine_Query::create()
                    ->from('StoryStatusChange s')
                    ->where('s.storyId = ?', $storyId)
                    ->orderBy('s.actionDate ASC');
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Update storyStatusChange
     * @param StoryStatusChange $storyStatusChange
     * @return update row count
     */
    public function updateStoryStatusChange(StoryStatusChange $storyStatusChange) {
        try {
            $query = Doctrine_Query::create()
                ->update('StoryStatusChange s')
                ->set('s.status', '?', $storyStatusChange->getStatus())
                ->set('s.actionDate', '?', $storyStatusChange->getActionDate())
                ->where('s.id = ?', $storyStatusChange->getId());
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    
    /**
     * Delete storyStatusChange by storyId
     * @param integer $storyId
     * @return deleted row count
     */
    public function deleteStoryStatusChangeByStoryId($storyId) {
        try {
            $query = Doctrine_Query::create()
                         ->delete('s.*')
                         ->from('StoryStatusChange s')
                        ->where("s.storyId = ?", $storyId);
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Delete storyStatusChage by id
     * @param integer $id
     * @return delete row count
     */
    public function deleteStoryStatusChangeById($id) {
        try {
            $query = Doctrine_Query::create()
                         ->delete('s.*')
                         ->from('StoryStatusChange s')
                        ->where("s.id = ?", $id);
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
}