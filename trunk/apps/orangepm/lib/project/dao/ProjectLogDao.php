<?php

/**
 * Dao class to retrieve data form Project Log table
 */
class ProjectLogDao {

    /**
     * Save ProjectLog item
     * @param ProjectLog $projectLog
     * @throws DaoException
     */
    public function saveLogItem(ProjectLog $projectLog) {
        try {
            $projectLog->save();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get ProjectLog item list by projectId
     * @param Integer $pojectId
     * @return Collection
     * @throws DaoException
     */
    public function getLogItemListByProjectId($pojectId) {
        try {
            $query = Doctrine_Query::create()
                    ->from('ProjectLog l')
                    ->where('l.projectId = ?', $pojectId)
                    ->orderBy('l.loggedDate ASC');
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get ProjectLog item by id
     * @param Integer $logId
     * @return Collection 
     * @throws DaoException
     */
    public function getLogItemById($logId) {
        try {
            return Doctrine_Core::getTable('ProjectLog')->find($logId);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Delete ProjectLog item
     * @param Integer $logId
     * @return Integer deleted row count
     * @throws DaoException
     */
    public function deleteLogItem($logId) {
        try {
            $query = Doctrine_Query::create()
                         ->delete('l.*')
                         ->from('projectLog l')
                        ->where("l.id = ?", $logId);
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Update ProjectLog item
     * @param ProjectLog $projectLog
     * @return Integer update row count
     * @throws DaoException
     */
    public function updateLogItem(ProjectLog $projectLog) {
        try {
            $query = Doctrine_Query::create()
                ->update('ProjectLog l')
                ->set('l.description', '?', $projectLog->description)
                ->where('l.id = ?', $projectLog->id );
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
}