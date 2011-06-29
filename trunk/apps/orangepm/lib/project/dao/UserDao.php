<?php

class UserDao {

    public function getUsers($active, $pageNo) {

        if ($active) {
            $pager = new sfDoctrinePager('Project', 10);

            $pager->getQuery()->from('User a')->where('a.isActive = ?', User::FLAG_ACTIVE);
            $pager->setPage($pageNo);
            $pager->init();
            return $pager;
        } 
        else {
            return $allUsers = Doctrine_Core::getTable('User')->findAll();
        }
    }

    public function saveUser($userParameters) {

        $user = new User();

        $user->setFirstName($userParameters['firstName']);
        $user->setLastName($userParameters['lastName']);
        $user->setUserType($userParameters['userType']);
        $user->setEmail($userParameters['email']);
        $user->setUsername($userParameters['username']);
        $user->setPassword(sha1($userParameters['password']));

        $user->save();
        return $user;
    }

    public function deleteUser($id) {

        $user = Doctrine_Core::getTable('User')->find($id);
        $user->setIsActive(User::FLAG_DELETED);
        $user->save();
    }

    public function updateUser($userParameters, $id) {

        $user = Doctrine_Core::getTable('User')->find($id);
        
        if ($user instanceof User) {
            
            $user->setFirstName($userParameters['firstName']);
            $user->setLastName($userParameters['lastName']);
            $user->setEmail($userParameters['email']);
            $user->setUserType($userParameters['userType']);
            $user->setUsername($userParameters['username']);
            
            
            if($userParameters['password'] != "double click to reset") {
                $user->setPassword(sha1($userParameters['password']));
            }
            
            $user->save();
        }
    }
    
}