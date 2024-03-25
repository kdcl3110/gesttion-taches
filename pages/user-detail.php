<?php

use Model\Task;
use Services\CategoryService;
use Services\ProjectService;
use Services\TaskService;
use Services\UserService;

$taskService = new TaskService($pdo);

$tasks = [];
$projectService = new ProjectService($pdo);
$categoryService = new CategoryService($pdo);
$userService = new UserService($pdo);

$userInit = null;

if (isset($_GET['id'])) {
    $userInit = $userService->getUserByID($_GET['id']);
    $tasks = $taskService->getTasksForUser($userInit->getUserID());
}

$categories = $categoryService->getCategories();
$projects = $projectService->getProjects();
$users = $userService->getUsers();

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
                $tasks = $taskService->getTasks();
            } else {
                $error = "Echec de la mis à jour";
            }
        } else {
            $error = "ERREUR DE CREATION: Les champs 'Nom tache', 'Attribuer à', 'Dates d'échéance' et 'Projet' sont requis";
        }
    }
}

if (isset($_POST['delete_task']) && !empty($_POST['delete_task'])) {
    $delete = $taskService->deleteTask($_POST['delete_task']);
    if ($delete) {
        $success = "Tâche supprimé avec succes";
        $tasks = $taskService->getTasks();
    } else {
        $error = 'Echec de la suppréssion';
    }
}


// var_dump($tasks);
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
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between breadcrumb-content">
                            <div class="row">
                                <div class="odr-img">
                                    <img src="assets/images/user/user.png" class="img-fluid rounded-circle avatar-30" alt="image">
                                </div>
                                <h5 class="ml-1"><?php echo isset($userInit) ? $userInit->getUsername() : null ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $i = 1;
            foreach ($tasks as $task) {
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
                                                                        <label for="exampleInputText2" class="h5">Stagiaire</label>
                                                                        <select name="user_id" class="selectpicker form-control" data-style="py-0">
                                                                            <option>----------</option>
                                                                            <?php
                                                                            foreach ($users as $user) {
                                                                            ?>
                                                                                <option value="<?= $user->getUserID() ?>" <?php echo $task->getUserID() == $user->getUserID() ? 'selected' : ''; ?>><?= $user->getUsername() ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
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
                                                    <div class="card mb-3">
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
                                                    </div>

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
        <!-- Page end  -->
    </div>
</div>

<!-- Modal list start -->

<div class="modal fade bd-example-modal-lg" role="dialog" aria-modal="true" id="new-task-modal">
    <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-block text-center pb-3 border-bttom">
                <h3 class="modal-title" id="exampleModalCenterTitle">Nouvelle tâche</h3>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="method" value="saveTask">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText02" class="h5">Nom tache*</label>
                                <input type="text" class="form-control" name="name" id="exampleInputText02" placeholder="Entrer  le nom de la tache">
                                <a href="#" class="task-edit text-body"><i class="ri-edit-box-line"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Attribuer à*</label>
                                <select name="user_id" class="selectpicker form-control" data-style="py-0">
                                    <option>----------</option>
                                    <?php
                                    foreach ($users as $user) {
                                    ?>
                                        <option value="<?= $user->getUserID() ?>"><?= $user->getUsername() ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="exampleInputText05" class="h5">Dates d'échéance*</label>
                                <input type="date" name="due_date" class="form-control" id="exampleInputText05" value="">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="exampleInputText005" class="h5">Projet*</label>
                                <select name="project_id" class="selectpicker form-control" data-style="py-0">
                                    <option>----------</option>
                                    <?php
                                    foreach ($projects as $project) {
                                    ?>
                                        <option value="<?= $project->getProjectID() ?>"><?= $project->getName() ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText040" class="h5">Description</label>
                                <textarea class="form-control" name="description" id="exampleInputText040" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap align-items-ceter justify-content-center mt-4">
                                <button type="submit" class="btn btn-primary mr-3">Enregistrer</button>
                                <div class="btn btn-primary" data-dismiss="modal">Annuler</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <form method="post">
            <div class="modal-content">
                <input type="hidden" name="delete_task" value="" id="id-delete">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Voulez vous vraiment supprimer cet élément ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </div>
            </div>
    </div>
    </form>
</div>

<script>
    const selectTaskID = (task_id) => {
        let input = document.getElementById('id-delete')
        input.value = task_id;
    }
</script>