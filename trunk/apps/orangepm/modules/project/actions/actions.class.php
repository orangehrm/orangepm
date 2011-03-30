<?php

/**
 * project actions.
 *
 * @package    OrangePM
 * @subpackage project
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projectActions extends sfActions {

    public function executeIndex() {

        $response = $this->getResponse();
        $response->setTitle(__('Orange Project Management'));
    }

    public function executeViewProjects($request) {

        $this->message = $request->getParameter('msg');
        $this->projectForm = new sfForm();

        $dao = new ProjectDao();

        $pageNo = $this->getRequestParameter('page', 1);
        $this->pager = $dao->getProjects(true, $pageNo);

        $response = $this->getResponse();
        $response->setTitle(__('Projects'));
    }

    public function executeSaveProject($request) {

        $dao = new ProjectDao();
        $dao->saveProject($request->getParameter('name'));
        $this->redirect('project/viewProjects?msg=added');
    }

    public function executeAddProject($request) {

        $this->projectForm = new ProjectForm();
        $response = $this->getResponse();
        $response->setTitle(__('Add Project'));

        if ($request->isMethod('post')) {
            $this->projectForm->bind($request->getParameter('project'));
            if ($this->projectForm->isValid()) {

                $this->redirect('project/saveProject?' . http_build_query($this->projectForm->getValues()));
            }
        }

        $dao = new ProjectDao();
        $pageNo = $this->getRequestParameter('page', 1);
        $this->pager = $dao->getProjects(true, $pageNo);
    }

    public function executeDeleteProject($request) {

        $dao = new projectDao();
        $dao->deleteProject($request->getParameter('id'));
        $this->redirect('project/viewProjects');
    }

    public function executeEditProject($request) {

        $dao = new ProjectDao();
        $dao->updateProject($request->getParameter('id'), $request->getParameter('name'));
        die;
        sfView::NONE;
    }

    public function executeEditStory($request) {

        $dao = new StoryDao();
        $inputParameters = array(
            'name' => $request->getParameter('name'),
            'added date' => $request->getParameter('date'),
            //'estimated effort' => $estimation,
            'id' => $request->getParameter('id'),
            'status' => $request->getParameter('status')
                //'accepted date' => $accepttedDate
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
                
                $story = $dao->saveStory($inputParameters);
                
                $projectService = new ProjectService();
                $projectService->trackProjectProgress($inputParameters['accepted date'], $inputParameters['status'], $story->getId());
                //$dao->saveStory($this->storyForm->getValue('storyName'), $this->storyForm->getValue('dateAdded'), $this->storyForm->getValue('estimatedEffort'), $this->storyForm->getValue('projectId'));
                $this->redirect("project/viewStories?" . http_build_query(array('id' => $this->storyForm->getValue('projectId'), 'msg' => 'added', 'projectName' => $this->projectName)));
            }
        }

        $pageNo = $this->getRequestParameter('page', 1);
        $viewStoryDao = new StoryDao();
        $this->storyList = $viewStoryDao->getRelatedProjectStories(true, $this->projectId, $pageNo);
    }

    public function executeDeleteStory($request) {

        $dao = new StoryDao();
        $dao->deleteStory($request->getParameter('id'));
        $this->redirect("project/viewStories?" . http_build_query(array('id' => $request->getParameter('projectId'), 'projectName' => $request->getParameter('projectName'))));
    }

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

}
