<?php

/**
 * Servise class for User
 * Inplement logics for user
 */

class UserService {

    /**
	 * Update the user details
	 * @param $userParameters(array), $id
	 * @return none
	 */
    public function updateUser($userParameters, $id) {
        
        $dao = new UserDao();
        
        if($userParameters['userType'] == "Super Admin") {
            $userParameters['userType'] = User::USER_TYPE_SUPER_ADMIN;
        }
        elseif($userParameters['userType'] == "Project Admin"){
            $userParameters['userType'] = User::USER_TYPE_PROJECT_ADMIN;
        }
        
        $dao->updateUser($userParameters, $id);
        
    }   
    
}
