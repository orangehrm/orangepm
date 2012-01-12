<?php

/**
 * Dao class to retrieve data from StoryTask table
 */
class TaskDao {

    /**
     * Save Task
     * @param Task $task
     */
    public function saveTask(Task $task) {
        try {
            $task->save();
        } catch (Exception $e){
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Get Task by id
     * @param integer $id
     * @return Doctrine collection
     */
    public function getTaskById($id) {
        try {
            return Doctrine_Core::getTable('Task')->find($id);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Get alls tasks of a story
     * @param $storyId
     * @return Doctrine collection
     */
    public function getTaskByStoryId($storyId) {
        try {
            $query = Doctrine_Query::create()
                        ->select('t.*')
                        ->from('Task t')
                        ->where('t.storyId = ?', $storyId)
                        ->orderBy("t.name");
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    } 
    
    /**
     * Update Task
     * @param Task $task
     * @return update record count
     */
    public function updateTask(Task $task) {
        try {
            $query = Doctrine_Query::create()
                        ->update('Task t');
            if($task->getName()) {
                $query->set('t.name', '?', $task->getName());
            }
                $query->set('t.effort', $task->getEffort());
                $query->set('t.description', '?', $task->getDescription());
                $query->set('t.ownedBy', '?', $task->getOwnedBy());
                $query->set('t.status', '?', $task->getStatus());
                $query->where('t.id = ?', $task->getId());
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Delete the Task
     * @param integer $id
     * @return deleted record count
     */
    public function deleteTask($id) {
        try {
            $query = Doctrine_Query::create()
                        ->delete('t.*')
                        ->from('Task t')
                        ->where('t.id = ?', $id);
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    /**
     * Get all task status list
     * @return taskStatus array
     */
    public function getAllTaskStatusArray() {
        try {
            $query = Doctrine_Query::create()
                        ->select('t.*')
                        ->from('TaskStatus t')
                        ->orderBy("t.id");
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
}