<?php

/**
 * project actions.
 *
 * @package    OrangePM
 * @subpackage project
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
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
    }

    /**
     * Set index action
     */
    public function executeIndex() {

        $response = $this->getResponse();
        $response->setTitle(__('Orange Project Management'));
    }

    /**
     * Login
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeLogin($request) {

        $this->getResponse()->setTitle(__('Login'));

        $this->loginForm = new LoginForm();

        if ($request->isMethod('post')) {

            $this->loginForm->bind($request->getParameter('login'));

            if ($this->loginForm->isValid()) {

                $user = $this->loginForm->getValue('username');
                $pass = $this->loginForm->getValue('password');

                $loginService = new LoginService();
                $loggedUser = $loginService->getUserByUsernameAndPassword($user, $pass);

                if ($loggedUser instanceof User) {

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

        $response = $this->getResponse();
        $response->setTitle(__('Users'));

        $this->message = $request->getParameter('msg');
        $this->userForm = new sfForm();

        $dao = new UserDao();
        $pageNo = $this->getRequestParameter('page', 1);
        $this->pager = $dao->getUsers(true, $pageNo);
    }

    /**
     * Add users
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddUser($request) {

        $this->userForm = new UserForm();
        $response = $this->getResponse();
        $response->setTitle(__('Add User'));

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

                $this->redirect('project/viewUsers?msg=added');
            }
        }

        $dao = new UserDao();
        $pageNo = $this->getRequestParameter('page', 1);
        $this->pager = $dao->getUsers(true, $pageNo);
    }

    /**
     * Delete users
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteUser($request) {

        $dao = new UserDao();
        $dao->deleteUser($request->getParameter('id'));
        $this->redirect('project/viewUsers?');
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
     * View projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewProjects($request) {

        $this->message = $request->getParameter('msg');
        $this->projectForm = new sfForm();
        $this->statusForm = new StatusForm();

        $dao = new ProjectDao();
        $statusDao = new ProjectStatusDao();

        $pageNo = $this->getRequestParameter('page', 1);

        $this->pager = $dao->getProjectsByStatus(true, $pageNo,1);
        $this->status = $statusDao->getProjectStatusByProjectStatusId(1)->getProjectStatus();
        
        if ($request->isMethod('post')) {
            $this->statusForm->bind($request->getParameter('projectSearch'));
            if ($this->statusForm->isValid()) {

                if($this->statusForm->getValue('searchByStatus') == 0) {
                    $this->pager = $dao->getProjects(true, $pageNo);
                    $this->status = 'All';
                }
                else{
                $this->pager = $dao->getProjectsByStatus(true, $pageNo,$this->statusForm->getValue('searchByStatus'));
                $this->status = $statusDao->getProjectStatusByProjectStatusId($this->statusForm->getValue('searchByStatus'))->getProjectStatus();
                }
            }
        }

        $response = $this->getResponse();
        $response->setTitle(__('Projects'));
    }

    /**
     * Save projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveProject($request) {

        $dao = new ProjectDao();
        $dao->saveProject($request->getParameter('name'), $request->getParameter('statusId'));
        $this->redirect('project/viewProjects?msg=added');
    }

    /**
     * Add projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddProject($request) {

        $this->projectForm = new ProjectForm();
        $response = $this->getResponse();
        $response->setTitle(__('Add Project'));

        if ($request->isMethod('post')) {
            $this->projectForm->bind($request->getParameter('project'));
            if ($this->projectForm->isValid()) {
                $this->redirect('project/saveProject?' . http_build_query(array('name' => $this->projectForm->getValue('name'), 'statusId' => $this->projectForm->getValue('status'))));
            }
        }

        $dao = new ProjectDao();
        $pageNo = $this->getRequestParameter('page', 1);
        $this->pager = $dao->getProjects(true, $pageNo);
    }

    /**
     * Delete projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteProject($request) {

        $dao = new projectDao();
        $dao->deleteProject($request->getParameter('id'));
        $this->redirect('project/viewProjects');
    }

    /**
     * Edit projects
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeEditProject($request) {

        $dao = new ProjectDao();
        $dao->updateProject($request->getParameter('id'), $request->getParameter('name'), $request->getParameter('projectStatus'));
        die;
        sfView::NONE;
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
        if ($request->getParameter('estimation') == '')
            $inputParameters['estimated effort'] = null;
        else
            $inputParameters['estimated effort'] = $request->getParameter('estimation');


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
                $projectService = new ProjectService();
                $projectService->trackProjectProgressAddStory($inputParameters['accepted date'], $inputParameters['status'], $inputParameters['project id'], $inputParameters['estimated effort']);
                $dao->saveStory($inputParameters);
                $this->redirect("project/viewStories?" . http_build_query(array('id' => $this->storyForm->getValue('projectId'), 'msg' => 'added', 'projectName' => $this->projectName)));
            }
        }

        $pageNo = $this->getRequestParameter('page', 1);
        $viewStoryDao = new StoryDao();
        $this->storyList = $viewStoryDao->getRelatedProjectStories(true, $this->projectId, $pageNo);
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
        $this->redirect("project/viewStories?" . http_build_query(array('id' => $request->getParameter('projectId'), 'projectName' => $request->getParameter('projectName'))));
    }

    /**
     * View stories
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewStories($request) {

        $this->message = $request->getParameter('msg');

        $response = $this->getResponse();
        $response->setTitle(__('Stories'));

        $this->projectId = $request->getParameter('id');
        $this->projectName = $request->getParameter('projectName');
        $viewStoriesDao = new StoryDao();

        $pageNo = $this->getRequestParameter('page', 1);
        $this->storyList = $viewStoriesDao->getRelatedProjectStories(true, $this->projectId, $pageNo);
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
    }

}