<?php

class ProjectService {

    public function trackProjectProgress($projectId, $date, $status, $storyId) {

        if ($status == 'ACCEPTED') {
            $projectProgressDao = new ProjectProgressDao();
            $projectProgress = $projectProgressDao->getProjectProgress($projectId, $date);
            $workCompleted = $projectProgress->getWorkCompleted();

            $storyDao = new StoryDao();
            $workCompleted += $storyDao->getStory($storyId)->getProject()->getEstimatedEffort();

            $projectProgressDao->updateProjectProgress($projectId, $date, $workCompleted);

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
