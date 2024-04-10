<?php

function returnActive($label)
{
    return isset($_GET['page']) && $_GET['page'] == $label ? 'active' : '';
}

function getPourcentageForAllTask($status = 'completed')
{
    global $pdo;
    $taskService = new Services\TaskService($pdo);
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

function getPourcentageTaskUser()
{
    global $pdo;
    $taskService = new Services\TaskService($pdo);
    $tasks = $taskService->getTasksForUser($_SESSION['user']->getUserID());

    if (count($tasks) == 0)
        return 0;

    $count = 0;
    foreach ($tasks as $task) {
        if ($task->getStatus() == 'completed')
            $count++;
    }

    return  ceil(($count / count($tasks)) * 100);
}

if (!isset($_SESSION['user'])) {
    header('Location:?page=landing');
    exit();
}


?>

<div class="iq-sidebar sidebar-default">
    <div class="iq-sidebar-logo d-flex align-items-center">
        <a href="?page=home" class="header-logo">
            <img src="assets/images/logo.svg" alt="logo" />
            <h3 class="logo-title light-logo">sidimalo</h3>
        </a>
        <div class="iq-menu-bt-sidebar ml-0">
            <i class="las la-bars wrapper-menu"></i>
        </div>
    </div>

    <?php if ($_SESSION['user']->getRole() == 'admin') { ?>
        <div class="data-scrollbar" data-scroll="1">
            <nav class="iq-sidebar-menu">
                <ul id="iq-sidebar-toggle" class="iq-menu">
                    <li class="<?= returnActive('home'); ?>">
                        <a href="?page=home" class="svg-icon">
                            <svg class="svg-icon" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span class="ml-4">Dashboards</span>
                        </a>
                    </li>
                    <li class="<?= returnActive('projects'); ?>">
                        <a href="?page=projects" class="svg-icon">
                            <svg class="svg-icon" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                            <span class="ml-4">Projets</span>
                        </a>
                    </li>
                    <li class="<?= returnActive('tasks'); ?>">
                        <a href="?page=tasks" class="svg-icon">
                            <svg class="svg-icon" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            </svg>
                            <span class="ml-4">Tâches</span>
                        </a>
                    </li>
                    <li class="<?= returnActive('employees'); ?>">
                        <a href="?page=employees" class="svg-icon">
                            <svg class="svg-icon" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="ml-4">Employées</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div id="sidebar-bottom" class="position-relative sidebar-bottom">
                <div class="card border-none mb-0 shadow-none">
                    <div class="card-body p-0">
                        <div class="sidebarbottom-content">
                            <h5 class="mb-3">Tâches terminées</h5>
                            <div id="circle-progress-6" class="sidebar-circle circle-progress circle-progress-primary mb-4" data-min-value="0" data-max-value="100" data-value="<?= getPourcentageForAllTask() ?>" data-type="percent"></div>
                            <!-- <div class="custom-control custom-radio mb-1">
                            <input type="radio" id="customRadio6" name="customRadio-1" class="custom-control-input" checked="" />
                            <label class="custom-control-label" for="customRadio6">Performed task</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="customRadio7" name="customRadio-1" class="custom-control-input" />
                            <label class="custom-control-label" for="customRadio7">Incomplete Task</label>
                        </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="pt-5 pb-2"></div>
        </div>
    <?php } else { ?>
        <div id="sidebar-bottom" class="position-relative sidebar-bottom">
            <div class="card border-none mb-0 shadow-none">
                <div class="card-body p-0">
                    <div class="sidebarbottom-content">
                        <h5 class="mb-3">Terminées</h5>
                        <div id="circle-progress-6" class="sidebar-circle circle-progress circle-progress-primary mb-4" data-min-value="0" data-max-value="100" data-value="<?= getPourcentageTaskUser() ?>" data-type="percent"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>