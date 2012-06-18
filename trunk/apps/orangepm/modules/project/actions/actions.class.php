<?php

/**
 * project actions.
 *
 * @package    OrangePM
 * @subpackage project
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $test
 */

/**
 * Actions class for orangepm
 */
class projectActions extends sfActions {

    /**
     * Pre execute
     */
    public function preExecute() {

        if ((!$this->getUser()->isAuthenticated()) && ($this->getRequestParameter('action') != 'login' )) {
            $this->redirect('project/login');
        }
        $this->authenticationService = new AuthenticationService();$this->authenticationService = new AuthenticationService();
    }

    /**
     * Set index action
     */
    public function executeIndex() {
        $response = $this->getResponse();
        $response->setTitle(__('Orange Project Management'));
        $this->redirect('project/viewProjects');
    }

    /**
     * Login
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeLogin($request) {

        $this->getResponse()->setTitle(__('Login'));
        $loggedUserObject = null;
        $this->loginForm = new LoginForm();

        if ($request->isMethod('post')) {

            $this->loginForm->bind($request->getParameter('login'));

            if ($this->loginForm->isValid()) {

                $user = $this->loginForm->getValue('username');
                $pass = $this->loginForm->getValue('password');

                $loginService = new LoginService();
                $loggedUser = $loginService->getUserByUsernameAndPassword($user, $pass);
                
                
                if ($loggedUser instanceof User) {

                    $this->getUser()->setAttribute($loggedUserObject, $loggedUser);

                    $this->getUser()->setAuthenticated(true);



                    $userRole = $loginService->getUserRole($loggedUser->getUserType());

                    
                    if (!empty($userRole)) {
                        $this->getUser()->addCredential($userRole);
                    }

                    $this->redirect('project/index');
                } else {
                    $this->errorMessage = __('Username or Password is incorrect');
                }
            }
        }
    }

    /**
     * Logout
     * @return unknown_type
     */
    public function executeLogout() {

        $this->getUser()->setAuthenticated(false);
        $this->redirect('project/login');
    }

    /**
     * View users
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewUsers($request) {

        if ($this->getUser()->hasCredential('superAdmin')) {

            $response = $this->getResponse();
            $response->setTitle(__('Users'));
            $this->message = $request->getParameter('message');
            $this->userForm = new sfForm();
            $this->cannotDeleteMessage = $this->getUser()->getFlash('cannotDeleteMessage');

            $dao = new UserDao();
            $pageNo = $this->getRequestParameter('page', 1);
            $this->pager = $dao->getUsers(true, $pageNo);
        } else {
            $this->redirect("project/viewProjects");
        }
    }

    /**
     * Add users
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddUser($request) {

        if ($this->getUser()->hasCredential('superAdmin')) {

            $this->userForm = new UserForm();
            $response = $this->getResponse();
            $response->setTitle(__('Add User'));
            $userService = new UserService();
            $usernames = $userService->getAllUsernamesAsArray();

            if ($request->isMethod('post')) {
                $this->userForm->bind($request->getParameter('user'));
                if ($this->userForm->isValid()) {
                    $dao = new UserDao();

                    $inputParameters = array(
                        'firstName' => $this->userForm->getValue('firstName'),
                        'lastName' => $this->userForm->getValue('lastName'),
                        'email' => $this->userForm->getValue('email'),
                        'username' => $this->userForm->getValue('username'),
                        'userType' => $this->userForm->getValue('userType'),
                        'password' => $this->userForm->getValue('password')
                    );
                    $dao->saveUser($inputParameters);
                    $request->setParameter('message', __('The User is added successfully'));
                    $this->forward('project', 'viewUsers');
                }
            }

            $dao = new UserDao();
            $pageNo = $this->getRequestParameter('page', 1);
            $this->pager = $dao->getUsers(true, $pageNo);
        } else {
            $this->redirect("project/viewProjects");
        }
    }

    /**
     * Delete users
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteUser($request) {

        if ($this->getUser()->hasCredential('superAdmin')) {
            $dao = new UserDao();
            $this->id = $request->getParameter('id');
            if ($this->getUser()->getAttribute($loggedUserObject)->getId() != $this->id) {
                $dao->deleteUser($this->id);
            } else {
                $this->getUser()->setFlash('cannotDeleteMessage', __('You cannot delete your own account !'));
            }
            $this->redirect('project/viewUsers');
        } else {
            $this->redirect('project/viewProjects');
        }
    }

    /**
     * Edit users
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeEditUser($request) {

        $userService = new UserService();

        $userParameters = array(
            'firstName' => $request->getParameter('firstName'),
            'lastName' => $request->getParameter('lastName'),
            'email' => $request->getParameter('email'),
            'userType' => $request->getParameter('userType'),
            'username' => $request->getParameter('username'),
            'password' => $request->getParameter('password')
        );

        $userService->updateUser($userParameters, $request->getParameter('id'));
        die;
    }

    /**
     * View profile of a project admin
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewProfile($request) {
        $loggedUserObject = null;
        $response = $this->getResponse();
        $response->setTitle(__('Profile'));
        $userDao = new UserDao();
        $this->loggedUser = $this->getUser()->getAttribute($loggedUserObject);

        $isSuperAdmin = false;
        if ($this->getUser()->hasCredential('superAdmin')) {
            $isSuperAdmin = true;
        }
        
        $userProfileDetails = array('userFormType' => 'editUserForm', 'firstName' => $this->loggedUser->getFirstName(),
            'lastName' => $this->loggedUser->getLastName(), 'email' => $this->loggedUser->getEmail(),
            'password' => $this->loggedUser->getPassword(), 'isSuperAdmin' => $isSuperAdmin);

        $this->userForm = new UserForm(array(), $userProfileDetails);
        if ($request->isMethod('post')) {   
            $this->userForm->bind($request->getParameter('user'));
            if ($this->userForm->isValid()) {
                $userParameters = array(
                    'firstName' => $this->loggedUser->getFirstName(),
                    'lastName' =>$this->loggedUser->getLastName(),
                    'email' => $this->userForm->getValue('email'),
                    'userType' => $this->loggedUser->getUserType(),
                    'username' => $this->loggedUser->getUsername(),
                    'password' => $this->userForm->getValue('password')
                );
                $userDao->updateUser($userParameters, $this->loggedUser->getId());
                $this->getUser()->setFlash('editProfile', __('The profile edit successfully'));
                $this->forward('project', 'index');
            }
        }
         
       
    }

    /**
     * View projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewProjects($request) {

        $response = $this->getResponse();
        $response->setTitle(__('Projects'));
        $this->loggedUserObject = null;
        $this->projectForm = new sfForm();
        
        $this->selectedStatusId = $request->getParameter('selectedStatusId');
        
        $selectedStatusDetails = array('selectedProjectStatusId' => $this->selectedStatusId);
        
        $this->projectSearchForm = new ProjectSearchForm(array(), $selectedStatusDetails);

        $this->statusId = $this->getUser()->getFlash('statusId');

        $userService = new UserService();
        $this->projectAdmins = $userService->getAllUsersAsArray();

        $dao = new ProjectDao();
        $projectSevice = new ProjectService();
        $loginService = new LoginService();

        $loggedUserId = $this->getUser()->getAttribute($this->loggedUserObject)->getId();

        if ($this->statusId == null) {
            $this->statusId = Project::PROJECT_STATUS_DEFAULT_ID;
        }

        if($this->getUser()->hasCredential('superAdmin')){
            if($this->selectedStatusId != "") {
                $this->projects =  $projectSevice->getAllProjects(true, $this->selectedStatusId);
            }
            else{
                $this->projects = $projectSevice->getAllProjects(true, $this->statusId);
            }
        }
        else{
            if($this->selectedStatusId != "") {
                $this->projects = $projectSevice->getProjectsByUserIdAndStatusId($loggedUserId, $this->selectedStatusId);
                $this->projects = $projectSevice->getProjectsByUserIdAndStatusId($loggedUserId, $this->selectedStatusId);
                
            }
            else{
                $this->projects = $projectSevice->getProjectsByUserIdAndStatusId($loggedUserId, $this->statusId);
            }            
        }
        
        
        if (count($this->projects) == 0) {
            $this->noRecordMessage = __("No Matching Projects Found");
        }

        /* $pageNo = $this->getRequestParameter('page', 1);
          $this->recordsLimit = 5;
          $this->recordsCount = 5;
          $this->offset = 0;

          $this->pager = new SimplePager('action', $this->recordsLimit);
          $this->pager->setPage($pageNo);
          $this->pager->setNbResults($this->recordsCount);
          $this->pager->init();
          $offset = $this->pager->getOffset();
          $offset = empty($offset)?0:$offset;
          $this->offset = $offset; */
    }

    /**
     * Add projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddProject($request) {

        $isSuperAdmin = false;
        if ($this->getUser()->hasCredential('superAdmin')) {
            $isSuperAdmin = true;
        }
        $removeUserId=null;
        $loggedUserObject = null;
        if(($this->getUser()->hasCredential('projectAdmin'))||($this->getUser()->hasCredential('superAdmin'))){
            $removeUserId=$this->getUser()->getAttribute($loggedUserObject)->getId();
        }
        $this->projectForm = new ProjectForm(array(), array('user' => $isSuperAdmin,'newproject'=>true,'projectid'=>null,'removeUserId'=>$removeUserId));        
        $response = $this->getResponse();
        $response->setTitle(__('Add Project'));
        $loggedUserObject = null;
        $projectSevice = new ProjectService();

        $loggedUserId = $this->getUser()->getAttribute($loggedUserObject)->getId();
        $this->projectForm->setDefault('projectAdmin', $loggedUserId);

        if ($request->isMethod('post')) {
            $this->projectForm->bind($request->getParameter('project'));            

            if ($this->projectForm->isValid()) {
                $project = new Project();
                $project->setName($this->projectForm->getValue('name'));
                $project->setProjectStatusId($this->projectForm->getValue('status'));
                $project->setStartDate($this->projectForm->getValue('startDate'));
                if($this->projectForm->getValue('endDate') != '') {
                    $project->setEndDate($this->projectForm->getValue('endDate'));
                }
                $project->setDescription($this->projectForm->getValue('description'));
                if ($isSuperAdmin) {
                    if($this->projectForm->getValue('projectAdmin') != 0) {
                        $project->setUserId($this->projectForm->getValue('projectAdmin'));
                    }else
                    {
                      $project->setUserId($loggedUserId);
                    }
                } else {
                    $project->setUserId($loggedUserId);
                }
                $projectSevice->saveProject($project);               
                $projectId = $project->getId();
                //$projectUsers=array();
                $projectUsersColl=new Doctrine_Collection('ProjectUser');                
                //$projectUsers=$this->projectForm->getWidget('projectUserAll');
                //$mine=array();
                //$mine=$projectUsers->get;
                //$projectUsers[0]='dsds';
                $projectUserString=$request->getParameter('aaa');
                //die($projectUserString);
                
                //die($request->getParameter('aaa'));
                if($projectUserString!=''){
                    $projectUserValues=explode(',', $projectUserString);
                    foreach($projectUserValues as $single){
                        $projectUser=new ProjectUser();
                        $projectUser->setUserId($single);
                        $projectUser->setProjectId($projectId);
                        $projectUser->setUserType(User::USER_TYPE_PROJECT_MEMBER);
                        $projectUsersColl->add($projectUser);
                    }
                    
                }
                
               $projectUser=new ProjectUser();
               $projectUser->setUserId($project->getUserId());
               $projectUser->setProjectId($projectId);
               $projectUser->setUserType(User::USER_TYPE_PROJECT_ADMIN);
               $projectUsersColl->add($projectUser);
                //$project->setProjectUser($projectUsersColl);
                $projectSevice->updateProject($project,$projectUsersColl);
                $this->getUser()->setFlash('addProject', __('The Project is added successfully'));
                $this->getUser()->setFlash('statusId', $this->projectForm->getValue('status'));
                $this->redirect("project/viewProjectDetails?projectId=$projectId");
            }
        }
        if ($isSuperAdmin) {
            $this->projects = $projectSevice->getAllProjects(true, Project::PROJECT_STATUS_ALL_ID);
        } else {
            $this->projects = $projectSevice->getProjectsByUser($loggedUserId);
        }
    }

    /**
     * Delete projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteProject($request) {
        $this->projectId = $request->getParameter('id');
        $loggedUserId = $this->getUser()->getAttribute($loggedUserObject)->getId();
        $auth = new AuthenticationService();
        $accessLevel = $auth->projectAccessLevel($loggedUserId, $this->projectId);
        if(($accessLevel == User::USER_TYPE_PROJECT_ADMIN) || ($accessLevel == User::USER_TYPE_SUPER_ADMIN) ){
            $dao = new projectDao();
            $dao->deleteProject($this->projectId);
            $this->getUser()->setFlash('statusId', $dao->getProjectById($this->projectId)->getProjectStatusId());
            $this->redirect('project/viewProjects');
        } else {
            $this->redirect("project/viewProjects");
        }
    }

    /**
     * Edit projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeEditProject($request) {
        $this->projectId = $request->getParameter('id');
        $loggedUserId = $this->getUser()->getAttribute($loggedUserObject)->getId();
        $auth = new AuthenticationService();
        $accessLevel = $auth->projectAccessLevel($loggedUserId, $this->projectId);
        if(($accessLevel == User::USER_TYPE_PROJECT_ADMIN) || ($accessLevel == User::USER_TYPE_SUPER_ADMIN) ){
            $dao = new ProjectDao();
            $project = new Project();
            $loggedUserId = $this->getUser()->getAttribute($loggedUserObject)->getId();
            $this->projectAdminId = $request->getParameter('projectAdminId');
            $project->setId($request->getParameter('id'));
            $project->setName($request->getParameter('name'));
            $project->setProjectStatusId($request->getParameter('projectStatus'));
            $project->setStartDate($request->getParameter('startDate'));
            if ($request->getParameter('endDate') != '') {
                $project->setEndDate($request->getParameter('endDate'));
            }
    /*        if($this->getUser()->hasCredential('projectAdmin')) {
                $project->setUserId($this->getUser()->getAttribute($loggedUserObject)->getId());
            } else {
                if ($request->getParameter('endDate') == )
            }*/
            if ($this->getUser()->hasCredential('superAdmin')) {
                if($request->getParameter('projectAdminId') != 0) {
                    $project->setUserId($request->getParameter('projectAdminId'));
                }
                else {
                    $project->setUserId($loggedUserId);
                }
            } else {
                $project->setUserId($loggedUserId);
            }
            //$project->setUserId($this->projectAdminId);
            $dao->updateProject($project,null,false);
        }
        die;
    }

    /**
     * Edit stories
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeEditStory($request) {

        $dao = new StoryDao();
        $inputParameters = array(
            'name' => $request->getParameter('name'),
            'added date' => $request->getParameter('date'),
            'id' => $request->getParameter('id'),
            'status' => $request->getParameter('status')
        );
        $loggedUserId = $this->getUser()->getAttribute($loggedUserObject)->getId();
        $projectId = $dao->getProjectIdByStoryId($request->getParameter('id'));
        $auth = new AuthenticationService();
        $accessLevel = $auth->projectAccessLevel($loggedUserId, $projectId);
        if(($accessLevel == User::USER_TYPE_PROJECT_ADMIN) || ($accessLevel == User::USER_TYPE_SUPER_ADMIN) ){
            if ($request->getParameter('estimation') == '')
                $inputParameters['estimated effort'] = null;
            else
                $inputParameters['estimated effort'] = $request->getParameter('estimation');
        }
        if ($request->getParameter('acceptedDate') == '')
            $inputParameters['accepted date'] = null;
        else
            $inputParameters['accepted date'] = $request->getParameter('acceptedDate');


        $projectService = new ProjectService();
        $projectService->trackProjectProgress($request->getParameter('acceptedDate'), $request->getParameter('status'), $request->getParameter('id'));
        $dao->updateStory($inputParameters);
        die;
    }

    /**
     * Add stories
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddStory($request) {

        $this->projectId = $request->getParameter('id');
        $projectService = new ProjectService();
        $loggedUserObject = null;
        $auth = new AuthenticationService();
        $projectAccessLevel = $auth->projectAccessLevel($this->getUser()->getAttribute($loggedUserObject)->getId(), $this->projectId);
        if ($projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN || $projectAccessLevel == User::USER_TYPE_SUPER_ADMIN || $projectAccessLevel == User::USER_TYPE_PROJECT_MEMBER) {
            $this->projectName = $request->getParameter('projectName');
            $this->storyForm = new StoryForm();
            $this->storyForm->setDefault('projectId', $this->projectId);

            $response = $this->getResponse();
            $response->setTitle(__('Add Story'));



            if ($request->isMethod('post')) {
                $this->storyForm->bind($request->getParameter('project'));
                if ($this->storyForm->isValid()) {
                    $dao = new StoryDao();
                    $storyStatus = array(0 => 'Pending', 1 => 'Design', 2 => 'Development', 3 => 'Development Completed', 4 => 'Testing', 5 => 'Rework', 6 => 'Accepted');
                    $inputParameters = array(
                        'name' => $this->storyForm->getValue('storyName'),
                        'added date' => $this->storyForm->getValue('dateAdded'),
                        'estimated effort' => $this->storyForm->getValue('estimatedEffort'),
                        'project id' => $this->storyForm->getValue('projectId'),
                        'status' => $storyStatus[$this->storyForm->getValue('status')],
                        'accepted date' => $this->storyForm->getValue('acceptedDate')
                    );
                    $projectService->trackProjectProgressAddStory($inputParameters['accepted date'], $inputParameters['status'], $inputParameters['project id'], $inputParameters['estimated effort']);
                    $dao->saveStory($inputParameters);
                    $this->getUser()->setFlash('addStory', __('The Story was added successfully'));
                    $this->redirect("project/viewStories?" . http_build_query(array('id' => $this->storyForm->getValue('projectId'), 'projectName' => $this->projectName)));
                }
            }


            $pageNo = $this->getRequestParameter('page', 1);
            $viewStoryDao = new StoryDao();
            $this->storyList = $viewStoryDao->getRelatedProjectStories(true, $this->projectId, $pageNo);
        } else {
            $this->redirect("project/viewProjects");
        }
    }

    /**
     * Delete stories
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteStory($request) {


        $projectService = new ProjectService();
        $projectService->trackProjectProgressDeleteStory($request->getParameter('id'));

        $dao = new StoryDao();
        $dao->deleteStory($request->getParameter('id'), $request->getParameter('deletedDate'));
        if($request->getParameter('fromViewProjectDetails')) {
            $this->redirect("project/viewProjectDetails?" . http_build_query(array('projectId' => $request->getParameter('projectId')))."#stories");
        } else {
            $this->redirect("project/viewStories?" . http_build_query(array('id' => $request->getParameter('projectId'), 'projectName' => $request->getParameter('projectName'))));
        }
    }

    /**
     * View stories
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewStories($request) {

        $response = $this->getResponse();
        $response->setTitle(__('Stories'));

        $this->projectId = $request->getParameter('id');

        $projectService = new ProjectService();
        $this->taskService = new TaskService();
        $loggedUserObject = null;
        $auth = new AuthenticationService();
        $projectAccessLevel = $auth->projectAccessLevel($this->getUser()->getAttribute($loggedUserObject)->getId(), $this->projectId);
        if ($projectAccessLevel == User::USER_TYPE_PROJECT_ADMIN || $projectAccessLevel == User::USER_TYPE_SUPER_ADMIN || $projectAccessLevel == User::USER_TYPE_PROJECT_MEMBER) {
       
            $this->id = $request->getParameter('id');
            $projectDao = new ProjectDao();
            $this->projectName = $projectDao->getProjectById($this->id)->getName();
            $viewStoriesDao = new StoryDao();

            $pageNo = $this->getRequestParameter('page', 1);
            $this->storyList = $viewStoriesDao->getRelatedProjectStories(true, $this->projectId, $pageNo);

            if (count($this->storyList) == 0) {
                $this->noRecordMessage = __("No Matching Stories Found");
            }
        } else {
            $this->redirect("project/viewProjects");
        }
    }

    /**
     * View weekly progress
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewWeeklyProgress($request) {
        $this->projectName = $request->getParameter('projectName');
        $this->projectId = $request->getParameter('projectId');
        // $pageNo = $this->getRequestParameter('page', 1);

        $viewStoriesDao = new StoryDao();


        $this->storyList = $viewStoriesDao->getStoriesForProjectProgress(true, $this->projectId, "date_added");

        $progressServiceObject = new ProjectService();
        $allArray = $progressServiceObject->viewWeeklyProgress($this->storyList, $this->projectId);
        $this->weekStartingDate = $allArray[0];
        $this->totalEstimation = $allArray[1];
        $this->weeklyVelocity = $allArray[2];
        $this->workCompleted = $allArray[3];
        $this->burnDownArray = $allArray[4];

        if (count($allArray) == 0) {
            $this->noRecordMessage = __("No Records to Show");
        }
    }

}