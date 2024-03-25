<?php

use Services\ProjectService;
use Services\TaskService;

$projectService = new ProjectService($pdo);
$taskService = new TaskService($pdo);

$projects = $projectService->getProjects();
$tasks = $taskService->getTasks();

if (count($projects) > 10) {
  $projects = array_slice($projects, 0, 10);
}

if (count($tasks) > 10) {
  $tasks = array_slice($tasks, 0, 5);
}

function getPourcentageProject($project)
{
  global $taskService;
  $tasks = $taskService->getTaskProject($project);

  if (count($tasks) == 0)
    return 0;

  $count = 0;
  foreach ($tasks as $task) {
    if ($task->getStatus() == 'completed')
      $count++;
  }

  return  ceil(($count / count($tasks)) * 100);
}

function getPourcentageAllTask($status = 'unfinished')
{
  global $taskService;
  $tasks = $taskService->getTasks();

  if (count($tasks) == 0)
    return 0;

  $count = 0;
  foreach ($tasks as $task) {
    if ($task->getStatus() == $status)
      $count++;
  }

  return  ceil(($count / count($tasks)) * 100);
}

function getPourcentageAllProject($status = 'unfinished')
{
  global $projectService;
  $projects = $projectService->getProjects();

  $result = 0;
  if (count($projects) == 0)
    return 0;

  foreach ($projects as $project) {
    $count = getPourcentageProject($project->getProjectID());
    if ($status == 'completed') {
      if ($count == 100) {
        $result++;
      }
    } else {
      if ($count < 100) {
        $result++;
      }
    }
  }

  return ceil(($result / count($projects)) * 100);
}

?>
<div class="content-page">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 col-lg-3">
        <div class="card card-block card-stretch card-height">
          <div class="card-body">
            <div class="top-block d-flex align-items-center justify-content-between">
              <h5>Tache non terminées</h5>
              <span class="badge badge-secondary">1</span>
            </div>
            <h3><span class="counter"><?= getPourcentageAllTask() ?>%</span></h3>
            <div class="d-flex align-items-center justify-content-between mt-1">
              <p class="mb-0">jauge</p>
              <span class="text-secondary"><?= getPourcentageAllTask() ?>%</span>
            </div>
            <div class="iq-progress-bar bg-secondary-light mt-2">
              <span class="bg-secondary iq-progress progress-1" data-percent="<?= getPourcentageAllTask() ?>"></span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card card-block card-stretch card-height">
          <div class="card-body">
            <div class="top-block d-flex align-items-center justify-content-between">
              <h5>Tâches non terminées</h5>
              <span class="badge badge-success">2</span>
            </div>
            <h3><span class="counter"><?= getPourcentageAllTask('completed') ?>%</span></h3>
            <div class="d-flex align-items-center justify-content-between mt-1">
              <p class="mb-0">jauge</p>
              <span class="text-success"><?= getPourcentageAllTask('completed') ?>%</span>
            </div>
            <div class="iq-progress-bar bg-success-light mt-2">
              <span class="bg-success iq-progress progress-1" data-percent="<?= getPourcentageAllTask('completed') ?>"></span>
            </div>
          </div>
        </div>
      </div>
      <?php
      $projectCompleted = getPourcentageAllProject('completed');
      $projectUnfinished = getPourcentageAllProject();
      ?>
      <div class="col-md-6 col-lg-3">
        <div class="card card-block card-stretch card-height">
          <div class="card-body">
            <div class="top-block d-flex align-items-center justify-content-between">
              <h5>Projets non terminés</h5>
              <span class="badge badge-warning">3</span>
            </div>
            <h3><span class="counter"><?= $projectUnfinished ?></span>%</h3>
            <div class="d-flex align-items-center justify-content-between mt-1">
              <p class="mb-0">jauge</p>
              <span class="text-warning"><?= $projectUnfinished ?>%</span>
            </div>
            <div class="iq-progress-bar bg-warning-light mt-2">
              <span class="bg-warning iq-progress progress-1" data-percent="<?= $projectUnfinished ?>"></span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card card-block card-stretch card-height">
          <div class="card-body">
            <div class="top-block d-flex align-items-center justify-content-between">
              <h5>Projets terminés</h5>
              <span class="badge badge-info">4</span>
            </div>
            <h3><span class="counter"><?= $projectCompleted ?></span>%</h3>
            <div class="d-flex align-items-center justify-content-between mt-1">
              <p class="mb-0">jauge</p>
              <span class="text-info"><?= $projectCompleted ?>%</span>
            </div>
            <div class="iq-progress-bar bg-info-light mt-2">
              <span class="bg-info iq-progress progress-1" data-percent="<?= $projectCompleted ?>"></span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-8">
        <div class="card-transparent card-block card-stretch card-height">
          <div class="card-body p-0">
            <div class="card">
              <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                  <h4 class="card-title">Dernières tâches</h4>
                </div>
              </div>
              <!-- <div class="card-body">
                <ul class="list-inline p-0 mb-0">
                  <li class="mb-1">
                    <div class="row">
                      <div class="col-sm-3">
                        <p class="mb-0">UX / UI Design</p>
                      </div>
                      <div class="col-sm-6">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="iq-progress-bar bg-secondary-light">
                            <span class="bg-secondary iq-progress progress-1" data-percent="65"></span>
                          </div>
                          <span class="ml-3">65%</span>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="iq-media-group text-sm-right">
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/05.jpg" alt="" />
                          </a>
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/06.jpg" alt="" />
                          </a>
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/07.jpg" alt="" />
                          </a>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="mb-1">
                    <div class="d-flex align-items-center justify-content-between row">
                      <div class="col-sm-3">
                        <p class="mb-0">Development</p>
                      </div>
                      <div class="col-sm-6">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="iq-progress-bar bg-primary-light">
                            <span class="bg-primary iq-progress progress-1" data-percent="59"></span>
                          </div>
                          <span class="ml-3">59%</span>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="iq-media-group text-sm-right">
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/08.jpg" alt="" />
                          </a>
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/09.jpg" alt="" />
                          </a>
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/04.jpg" alt="" />
                          </a>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li>
                    <div class="d-flex align-items-center justify-content-between row">
                      <div class="col-sm-3">
                        <p class="mb-0">Testing</p>
                      </div>
                      <div class="col-sm-6">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="iq-progress-bar bg-warning-light">
                            <span class="bg-warning iq-progress progress-1" data-percent="78"></span>
                          </div>
                          <span class="ml-3">78%</span>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="iq-media-group text-sm-right">
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/01.jpg" alt="" />
                          </a>
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/02.jpg" alt="" />
                          </a>
                          <a href="#" class="iq-media">
                            <img class="img-fluid avatar-40 rounded-circle" src="../assets/images/user/03.jpg" alt="" />
                          </a>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div> -->
            </div>
            <div class="row">
              <?php
              foreach ($tasks as $task) {
              ?>

                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-sm-8">
                          <div class="row align-items-center">
                            <div class="col-md-3">
                              <img src="assets/images/task.png" class="img-fluid rounded-circle avatar-90 m-auto" alt="image">
                            </div>
                            <div class="col-md-9">
                              <div class="mt-3 mt-md-0">
                                <h5 class="mb-1"><?= $task->getName() ?></h5>
                                <p class="mb-0">
                                  <?= substr($task->getDescription(), 0, 50) ?>...
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                          <div class="iq-media-group">
                            <?= date('d/m/Y', strtotime($task->getDueDate())) ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card card-block card-stretch card-height">
          <div class="card-body">
            <div class="card border-bottom pb-2 shadow-none">
              <div class="card-body text-center inln-date flet-datepickr">
                <input type="text" id="inline-date" class="date-input basicFlatpickr d-none" readonly="readonly" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <div class="card-transparent mb-0">
          <div class="card-header d-flex align-items-center justify-content-between p-0 pb-3">
            <div class="header-title">
              <h4 class="card-title">Projets courants</h4>
            </div>
            <div class="card-header-toolbar d-flex align-items-center">
              <div id="top-project-slick-arrow" class="slick-aerrow-block"></div>
            </div>
          </div>
          <div class="card-body p-0">
            <ul class="list-unstyled  top-projects mb-0">
              <?php
              $i = 1;
              foreach ($projects as $project) {
                $evolution = getPourcentageProject($project->getProjectID());
              ?>
                <li class="col-lg-4">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="mb-3"><?= $project->getName() ?></h5>
                      <p class="mb-3">
                        <i class="las la-calendar-check mr-2"></i><?= date('d/m/Y', strtotime($project->getDueDate())) ?>
                      </p>
                      <div class="iq-progress-bar bg-success-light mb-4">
                        <span class="bg-success iq-progress progress-1" data-percent="<?= $evolution ?>" style="transition: width 2s ease 0s; width: <?= $evolution ?>%"></span>
                      </div>
                    </div>
                  </div>
                </li>
              <?php
                $i++;
              }
              ?>
              <?php if (count($projects)) {
              ?>
                <li class="col-lg-4"></li>
              <?php
              }
              ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Page end  -->
  </div>
</div>