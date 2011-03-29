<?php

class ProjectService {

    public function trackProjectProgress($date, $status, $storyId) {

        $storyDao = new StoryDao();
        $story = $storyDao->getStory($storyId);
        $previousStatus = $story->getStatus();
        $projectId = $story->getProjectId();

        $projectProgressDao = new ProjectProgressDao();

        if (($status == 'ACCEPTED') && ($previousStatus != 'ACCEPTED')) {

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $date);
            $workCompleted = $projectProgress[0]->getWorkCompleted();
            $workCompleted += $story->getEstimation();
            $projectProgressDao->addProjectProgress($projectId, $date, $workCompleted, 2);
        } elseif (($status != 'ACCEPTED') && ($previousStatus == 'ACCEPTED')) {

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $date);
            $workCompleted = $projectProgress[0]->getWorkCompleted();

            $oldDate = $story->getAcceptedDate();
            $workCompleted -= $story->getEstimation();
            $projectProgressDao->updateProjectProgress($projectId, $oldDate, $workCompleted);
        } elseif (($status == 'ACCEPTED') && ($previousStatus== 'ACCEPTED')) {

            $oldDate = $story->getAcceptedDate();
            $newDate = $date;

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $oldDate);
            $workCompleted = $projectProgress[0]->getWorkCompleted();
            $workCompleted -= $story->getEstimation();
            $projectProgressDao->updateProjectProgress($projectId, $oldDate, $workCompleted);

            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $newDate);
            $workCompleted = $projectProgress[0]->getWorkCompleted();
            $workCompleted += $story->getEstimation();
            $projectProgressDao->addProjectProgress($projectId, $newDate, $workCompleted, 2);
        }
    }

}

//if (task = Mark Story ACCEPTED) {
//   $date =  DATE ACCEPTED
//   work completed on $date += story size
//   update database (work completed on $date)
//} else if (task = Mark ACCEPTED Story to other state) {
//   $date = DATE ACCEPTED
//   work completed on $date -= story size
//   update database (work completed on $date)
//} else if (task = Change ACCEPTED DATE to NEW ACCEPTED DATE) {
//   $date = DATE ACCEPTED
//   $new_date = NEW ACCEPTED DATE
//
//   work completed on $date -= story size
//   work completed on $new_date += story size
//
//   update database (work completed on $date)
//   update database (work completed on $new date)
//}
