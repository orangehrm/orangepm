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

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    /* public function executeIndex(sfWebRequest $request) {
      $this->forward('default', 'module');
      }
     */
    public function executeIndex() {

        $response = $this->getResponse();
        $response->setTitle(  __('Orange Project Management') );

    }

    public function executeViewProjects($request) {
        $this->message = $request->getParameter('msg');
        $this->projectForm = new sfForm();
        $dao = new ProjectDao();
//        $this->projectList = $dao->getAllProjects(true);

        $this->pager = $dao->getProjects(true, $this);

        $response = $this->getResponse();
        $response->setTitle( __('Projects'));
    }

    public function executeSaveProject($request) {

        $dao = new ProjectDao();
        $dao->saveProject($request->getParameter('name'));
        $this->redirect('project/viewProjects?msg=added');
    }

    public function executeAddProject($request) {

        $this->projectForm = new ProjectForm();
        $response = $this->getResponse();
        $response->setTitle( __('Add Project'));

        if ($request->isMethod('post')) {
            $this->projectForm->bind($request->getParameter('project'));
            if ($this->projectForm->isValid()) {

                $this->redirect('project/saveProject?' . http_build_query($this->projectForm->getValues()));
            }
        }

        $dao = new ProjectDao();
//        $this->projectList = $dao->getAllProjects(true);
        $this->pager = $dao->getProjects(true, $this);
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
        $dao->updateStory($request->getParameter('id'), $request->getParameter('name'), $request->getParameter('estimation'), $request->getParameter('date'));
        die;
    }

    public function executeAddStory($request) {

        $this->projectId = $request->getParameter('id');
        $this->projectName = $request->getParameter('projectName');
        $this->storyForm = new StoryForm();
        $this->storyForm->setDefault('projectId', $this->projectId);

        $response = $this->getResponse();
        $response->setTitle( __('Add Story'));

        if ($request->isMethod('post')) {
            $this->storyForm->bind($request->getParameter('project'));
            if ($this->storyForm->isValid()) {
                $dao = new StoryDao();
                $dao->saveStory($this->storyForm->getValue('storyName'), $this->storyForm->getValue('dateAdded'), $this->storyForm->getValue('estimatedEffort'), $this->storyForm->getValue('projectId'));
                $this->redirect("project/viewStories?" . http_build_query(array('id' => $this->storyForm->getValue('projectId'),'msg' => 'added','projectName' => $this->projectName)));
            }
        }
        $viewStoryDao = new StoryDao();
        $this->storyList = $viewStoryDao->getRelatedProjectStoriesPaged(true, $this->projectId, $this);
    }

    public function executeDeleteStory($request) {

        $dao = new StoryDao();
        $dao->deleteStory($request->getParameter('id'));
        $this->redirect("project/viewStories?" . http_build_query(array('id' => $request->getParameter('projectId'),'projectName' => $request->getParameter('projectName'))));
    }

    public function executeViewStories($request) {

        $this->message = $request->getParameter('msg');

        $response = $this->getResponse();
        $response->setTitle( __('Stories'));

        $this->projectId = $request->getParameter('id');
        $this->projectName = $request->getParameter('projectName');
        $viewStoriesDao = new StoryDao();
        $this->storyList = $viewStoriesDao->getRelatedProjectStoriesPaged(true, $this->projectId, $this);
    }

}
