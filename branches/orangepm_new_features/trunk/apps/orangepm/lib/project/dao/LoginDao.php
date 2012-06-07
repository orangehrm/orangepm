<?php

/**
 * Dao class for retrive the data of user table
 */
class LoginDao {
    
    /**
	 * Get the user type by using username adnd password
	 * @param $username, $password
	 * @return user
	 */
    public function getUserByUsernameAndPassword($username, $password) {
        
        $query = Doctrine_Core::getTable('User')
                            ->createQuery('a')
                            ->where('a.username = ?', $username)
                            ->andWhere('a.password = ?', $password)
                            ->andWhere('a.isActive = ?', User::FLAG_ACTIVE);
        
        return $query->fetchOne();
        
    }
    
}
