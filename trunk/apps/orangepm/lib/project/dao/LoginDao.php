<?php

/*
 * Dao class for retrive the data of admin table
 */

class LoginDao {
    
    // function for get the Admin Type of the user
    public function getUserByUsernameAndPassword($username, $password) {
        
        $query = Doctrine_Core::getTable('User')
                            ->createQuery('a')
                            ->where('a.username = ?', $username)
                            ->andWhere('a.password = ?', $password);        
        
        return $query->fetchOne();
        
    }
    
}
