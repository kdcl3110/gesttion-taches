<?php

use Model\Task;
use Services\TaskService;

$user = $_SESSION['user'];

$taskService = new TaskService($pdo);

$allTasks = $taskService->getTasksForUser($user->getUserID());
$unfinishedTasks = $taskService->getTasksForUser($user->getUserID(), 'unfinished');
$completedTasks = $taskService->getTasksForUser($user->getUserID(), 'completed');

$success = null;
$error = null;

if (isset($_POST['method'])) {
    if ($_POST['method'] == 'updateTask') {
        extract($_POST);
        if (!empty($task_id) && !empty($name) && !empty($due_date) && !empty($project_id) && !empty($user_id) && !empty($status)) {
            $task = new Task($task_id, $name, $description, $status, $project_id, $user_id, $due_date, null, null);
            $response = $taskService->updateTask($task);
            if ($response) {
                $success = "Tâche modifiée avec succes";
                $allTasks = $taskService->getTasksForUser($user->getUserID());
                $unfinishedTasks = $taskService->getTasksForUser($user->getUserID(), 'unfinished');
                $completedTasks = $taskService->getTasksForUser($user->getUserID(), 'completed');
            } else {
                $error = "Echec de la mis à jour";
            }
        } else {
            $error = "ERREUR DE CREATION: Les champs 'Nom tache', 'Attribuer à', 'Dates d'échéance' et 'Projet' sont requis";
        }
    }
}


?>
<div class="content-page">
    <div class="container-fluid">
        <?php
        // var_dump($projects);
        if ($error != null) {
        ?>
            <div class="alert text-white bg-danger" role="alert">
                <div class="iq-alert-icon">
                    <i class="ri-information-line"></i>
                </div>
                <div class="iq-alert-text"><?= $error ?></div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        <?php } ?>

        <?php
        if ($success != null) {
        ?>
            <div class="alert text-white bg-success" role="alert">
                <div class="iq-alert-text"><?= $success ?></div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="iq-edit-list usr-edit">
                            <ul class="iq-edit-profile d-flex nav nav-pills">
                                <li class="col-md-3 p-0">
                                    <a class="nav-link active" data-toggle="pill" href="#all">
                                        Tout
                                    </a>
                                </li>
                                <li class="col-md-3 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#unfinished">
                                        Non terminé
                                    </a>
                                </li>
                                <li class="col-md-3 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#completed">
                                        Terminé
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="iq-edit-list-data">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="all" role="tabpanel">
                            <?php
                            $i = 1;
                            foreach ($allTasks as $task) {
                            ?>
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card card-widget task-card">
                                                        <div class="card-body">
                                                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h5 class="mb-2"><?= $task->getName() ?></h5>
                                                                        <div class="media align-items-center">
                                                                            <div class="btn bg-body mr-3"><i class="ri-survey-line mr-2"></i><?php echo date('d/m/Y', strtotime($task->getDueDate())) ?></div>
                                                                            <div class="btn bg-body"><?= substr($task->getDescription(), 0, 50) . '...' ?></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="media align-items-center mt-md-0 mt-3">
                                                                    <button type="button" onclick="selectTaskID(<?= $task->getTaskID() ?>)" class="btn bg-secondary-light mr-3" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="ri-delete-bin-6-line m-0"></i></button>
                                                                    <a class="btn bg-success-light" data-toggle="collapse" href="#collapseEdit1<?= $i ?>" role="button" aria-expanded="false" aria-controls="collapseEdit1<?= $i ?>"><i class="ri-edit-box-line m-0"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="collapse" id="collapseEdit1<?= $i ?>">
                                                        <form action="" method="post">
                                                            <input type="hidden" name="method" value="updateTask" />
                                                            <input type="hidden" name="task_id" value="<?= $task->getTaskID() ?>" />
                                                            <input type="hidden" name="user_id" value="<?= $task->getUserID() ?>" />
                                                            <input type="hidden" name="project_id" value="<?= $task->getProjectID() ?>" />
                                                            <div class="card card-list task-card">
                                                                <div class="card-header d-flex align-items-center justify-content-between px-0 mx-3">
                                                                    <div class="header-title">
                                                                        <h5 class="h5">Modification</h5>
                                                                    </div>
                                                                    <div><button type="submit" class="btn bg-primary-light">Enregistrer</button></div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group mb-3 position-relative">
                                                                        <input type="text" class="form-control bg-white" name="name" value="<?= $task->getName() ?>">
                                                                        <a href="#" class="task-edit task-simple-edit text-body"><i class="ri-edit-box-line"></i></a>
                                                                    </div>
                                                                    <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Statut</label>
                                                                                        <select name="status" class="selectpicker form-control" data-style="py-0">
                                                                                            <option value="unfinished" <?php echo $task->getStatus() == 'unfinished' ? 'selected' : ''; ?>>Non terminé</option>
                                                                                            <option value="completed" <?php echo $task->getStatus() == 'completed' ? 'selected' : ''; ?>>Terminé</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Date d'échéance*</label>
                                                                                        <input type="date" name="due_date" class="form-control" id="exampleInputText3" value="<?php echo date('Y-m-d', strtotime($task->getDueDate())) ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText2" class="h5">Projet</label>
                                                                                        <select name="project_id" class="selectpicker form-control" data-style="py-0">
                                                                                            <option>----------</option>
                                                                                            <?php
                                                                                            foreach ($projects as $project) {
                                                                                            ?>
                                                                                                <option value="<?= $project->getProjectID() ?>" <?php echo $task->getProjectID() == $project->getProjectID() ? 'selected' : ''; ?>><?= $project->getName() ?></option>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Statut</label>
                                                                                        <select name="status" class="selectpicker form-control" data-style="py-0">
                                                                                            <option value="unfinished" <?php echo $task->getStatus() == 'unfinished' ? 'selected' : ''; ?>>Non terminé</option>
                                                                                            <option value="completed" <?php echo $task->getStatus() == 'completed' ? 'selected' : ''; ?>>Terminé</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> -->

                                                                    <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="form-group mb-0">
                                                                                        <label class="mb-2 h5">Description</label>
                                                                                        <textarea name="description" class="form-control mb-0"><?= $task->getDescription() ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $i++;
                            }
                            ?>
                        </div>
                        <div class="tab-pane fade" id="unfinished" role="tabpanel">
                            <?php
                            $i = 1;
                            foreach ($unfinishedTasks as $task) {
                            ?>
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card card-widget task-card">
                                                        <div class="card-body">
                                                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h5 class="mb-2"><?= $task->getName() ?></h5>
                                                                        <div class="media align-items-center">
                                                                            <div class="btn bg-body mr-3"><i class="ri-survey-line mr-2"></i><?php echo date('d/m/Y', strtotime($task->getDueDate())) ?></div>
                                                                            <div class="btn bg-body"><?= substr($task->getDescription(), 0, 50) . '...' ?></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="media align-items-center mt-md-0 mt-3">
                                                                    <button type="button" onclick="selectTaskID(<?= $task->getTaskID() ?>)" class="btn bg-secondary-light mr-3" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="ri-delete-bin-6-line m-0"></i></button>
                                                                    <a class="btn bg-success-light" data-toggle="collapse" href="#collapseEdit1" role="button" aria-expanded="false" aria-controls="collapseEdit1"><i class="ri-edit-box-line m-0"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="collapse" id="collapseEdit1">
                                                        <form action="" method="post">
                                                            <input type="hidden" name="method" value="updateTask" />
                                                            <input type="hidden" name="task_id" value="<?= $task->getTaskID() ?>" />
                                                            <div class="card card-list task-card">
                                                                <div class="card-header d-flex align-items-center justify-content-between px-0 mx-3">
                                                                    <div class="header-title">
                                                                        <h5 class="h5">Modification</h5>
                                                                    </div>
                                                                    <div><button type="submit" class="btn bg-primary-light">Enregistrer</button></div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group mb-3 position-relative">
                                                                        <input type="text" class="form-control bg-white" name="name" value="<?= $task->getName() ?>">
                                                                        <a href="#" class="task-edit task-simple-edit text-body"><i class="ri-edit-box-line"></i></a>
                                                                    </div>
                                                                    <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Statut</label>
                                                                                        <select name="status" class="selectpicker form-control" data-style="py-0">
                                                                                            <option value="unfinished" <?php echo $task->getStatus() == 'unfinished' ? 'selected' : ''; ?>>Non terminé</option>
                                                                                            <option value="completed" <?php echo $task->getStatus() == 'completed' ? 'selected' : ''; ?>>Terminé</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Date d'échéance*</label>
                                                                                        <input type="date" name="due_date" class="form-control" id="exampleInputText3" value="<?php echo date('Y-m-d', strtotime($task->getDueDate())) ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText2" class="h5">Projet</label>
                                                                                        <select name="project_id" class="selectpicker form-control" data-style="py-0">
                                                                                            <option>----------</option>
                                                                                            <?php
                                                                                            foreach ($projects as $project) {
                                                                                            ?>
                                                                                                <option value="<?= $project->getProjectID() ?>" <?php echo $task->getProjectID() == $project->getProjectID() ? 'selected' : ''; ?>><?= $project->getName() ?></option>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Statut</label>
                                                                                        <select name="status" class="selectpicker form-control" data-style="py-0">
                                                                                            <option value="unfinished" <?php echo $task->getStatus() == 'unfinished' ? 'selected' : ''; ?>>Non terminé</option>
                                                                                            <option value="completed" <?php echo $task->getStatus() == 'completed' ? 'selected' : ''; ?>>Terminé</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> -->

                                                                    <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="form-group mb-0">
                                                                                        <label class="mb-2 h5">Description</label>
                                                                                        <textarea name="description" class="form-control mb-0"><?= $task->getDescription() ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $i++;
                            }
                            ?>
                        </div>

                        <div class="tab-pane fade" id="completed" role="tabpanel">
                            <?php
                            $i = 1;
                            foreach ($completedTasks as $task) {
                            ?>
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card card-widget task-card">
                                                        <div class="card-body">
                                                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <h5 class="mb-2"><?= $task->getName() ?></h5>
                                                                        <div class="media align-items-center">
                                                                            <div class="btn bg-body mr-3"><i class="ri-survey-line mr-2"></i><?php echo date('d/m/Y', strtotime($task->getDueDate())) ?></div>
                                                                            <div class="btn bg-body"><?= substr($task->getDescription(), 0, 50) . '...' ?></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="media align-items-center mt-md-0 mt-3">
                                                                    <button type="button" onclick="selectTaskID(<?= $task->getTaskID() ?>)" class="btn bg-secondary-light mr-3" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="ri-delete-bin-6-line m-0"></i></button>
                                                                    <a class="btn bg-success-light" data-toggle="collapse" href="#collapseEdit1" role="button" aria-expanded="false" aria-controls="collapseEdit1"><i class="ri-edit-box-line m-0"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="collapse" id="collapseEdit1">
                                                        <form action="" method="post">
                                                            <input type="hidden" name="method" value="updateTask" />
                                                            <input type="hidden" name="task_id" value="<?= $task->getTaskID() ?>" />
                                                            <div class="card card-list task-card">
                                                                <div class="card-header d-flex align-items-center justify-content-between px-0 mx-3">
                                                                    <div class="header-title">
                                                                        <h5 class="h5">Modification</h5>
                                                                    </div>
                                                                    <div><button type="submit" class="btn bg-primary-light">Enregistrer</button></div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="form-group mb-3 position-relative">
                                                                        <input type="text" class="form-control bg-white" name="name" value="<?= $task->getName() ?>">
                                                                        <a href="#" class="task-edit task-simple-edit text-body"><i class="ri-edit-box-line"></i></a>
                                                                    </div>
                                                                    <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Statut</label>
                                                                                        <select name="status" class="selectpicker form-control" data-style="py-0">
                                                                                            <option value="unfinished" <?php echo $task->getStatus() == 'unfinished' ? 'selected' : ''; ?>>Non terminé</option>
                                                                                            <option value="completed" <?php echo $task->getStatus() == 'completed' ? 'selected' : ''; ?>>Terminé</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Date d'échéance*</label>
                                                                                        <input type="date" name="due_date" class="form-control" id="exampleInputText3" value="<?php echo date('Y-m-d', strtotime($task->getDueDate())) ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText2" class="h5">Projet</label>
                                                                                        <select name="project_id" class="selectpicker form-control" data-style="py-0">
                                                                                            <option>----------</option>
                                                                                            <?php
                                                                                            foreach ($projects as $project) {
                                                                                            ?>
                                                                                                <option value="<?= $project->getProjectID() ?>" <?php echo $task->getProjectID() == $project->getProjectID() ? 'selected' : ''; ?>><?= $project->getName() ?></option>
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-0">
                                                                                        <label for="exampleInputText3" class="h5">Statut</label>
                                                                                        <select name="status" class="selectpicker form-control" data-style="py-0">
                                                                                            <option value="unfinished" <?php echo $task->getStatus() == 'unfinished' ? 'selected' : ''; ?>>Non terminé</option>
                                                                                            <option value="completed" <?php echo $task->getStatus() == 'completed' ? 'selected' : ''; ?>>Terminé</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> -->

                                                                    <div class="card mb-3">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-lg-12">
                                                                                    <div class="form-group mb-0">
                                                                                        <label class="mb-2 h5">Description</label>
                                                                                        <textarea name="description" class="form-control mb-0"><?= $task->getDescription() ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $i++;
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>