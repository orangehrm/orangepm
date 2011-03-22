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

    }

    public function executeViewProjects() {

        $this->projectForm = new sfForm();
        $dao = new ProjectDao();
//        $this->projectList = $dao->getAllProjects(true);

        $this->pager = $dao->getProjects(true,$this);

        
    }

    public function executeSaveProject($request) {

        $dao = new ProjectDao();
        $dao->saveProject($request->getParameter('Name'));
        $this->redirect('project/viewProjects');
    }

    public function executeAddProject($request) {

        $this->projectForm = new ProjectForm();

        if ($request->isMethod('post')) {
            $this->projectForm->bind($request->getParameter('project'));
            if ($this->projectForm->isValid()) {

                $this->redirect('project/saveProject?' . http_build_query($this->projectForm->getValues()));
            }
        }

        $dao = new ProjectDao();
//        $this->projectList = $dao->getAllProjects(true);
        $this->pager = $dao->getProjects(true,$this);
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
        $this->storyForm = new StoryForm();
        $this->storyForm->setDefault('projectId', $this->projectId);

        if ($request->isMethod('post')) {
            $this->storyForm->bind($request->getParameter('project'));
            if ($this->storyForm->isValid()) {
                $dao = new StoryDao();
                $dao->saveStory($this->storyForm->getValue('Story_Name'), $this->storyForm->getValue('Date_Added'), $this->storyForm->getValue('Estimated_Effort'), $this->storyForm->getValue('projectId'));
                $this->redirect("project/viewStories?" . http_build_query(array('id' => $this->storyForm->getValue('projectId'))));
            }
        }
        $viewStoryDao = new StoryDao();
        $this->storyList = $viewStoryDao->getRelatedProjectStoriesPaged(true, $this->projectId,$this);
    }

    public function executeDeleteStory($request) {

        $dao = new StoryDao();
        $dao->deleteStory($request->getParameter('id'));
        $this->redirect("project/viewStories?" . http_build_query(array('id' => $request->getParameter('projectId'))));
    }

    public function executeViewStories($request) {
        
        $this->projectId = $request->getParameter('id');
        $viewStoriesDao = new StoryDao();
        $this->storyList = $viewStoriesDao->getRelatedProjectStoriesPaged(true, $this->projectId,$this);
    }

}
