<?php

/**
 * Service class for login
 */

class LoginService {

    /**
	 * Get the user type by using Username and Password
	 * @param $username, $password
	 * @return getUserByUsernameAndPassword
	 */
    public function getUserByUsernameAndPassword($username, $password) {
        
        $loginDao = new LoginDao();     
        return $loginDao->getUserByUsernameAndPassword($username, sha1($password)); 
        
    }

    /**
	 * Get the user role
	 * @param $type
	 * @return $userRole
	 */
    public function getUserRole($type) {
        
        $userRole = null;
        
        if($type == User::USER_TYPE_SUPER_ADMIN) {
            $userRole = "superAdmin";
        } elseif($type == User::USER_TYPE_PROJECT_ADMIN) {
            $userRole = "projectAdmin";
        } elseif($type == User::USER_TYPE_PROJECT_MEMBER) {
            $userRole = "projectMember";
        }
        
        return $userRole;
        
    }
    
}

