<?php

/*
 * Implement logics for login
 */

class LoginService {
    
    public function getUserByUsernameAndPassword($username, $password) {
        
        $loginDao = new LoginDao();     
        return $loginDao->getUserByUsernameAndPassword($username, $password); 
        
    }
    
    public function getUserRole($type) {
        
        $userRole = null;
        
        if($type == User::USER_TYPE_SUPER_ADMIN) {
            $userRole = "superAdmin";
        }
        elseif($type == User::USER_TYPE_PROJECT_ADMIN) {
            $userRole = "projectAdmin";
        }
        
        return $userRole;
        
    }
    
}

