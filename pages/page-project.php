<?php

use Model\Project;
use Services\CategoryService;
use Services\ProjectService;
use Services\TaskService;

$projectService = new ProjectService($pdo);
$categoryService = new CategoryService($pdo);
$taskService = new TaskService($pdo);

$categories = $categoryService->getCategories();
$projects = $projectService->getProjects();

$success = null;
$error = null;

if (isset($_POST['method'])) {
    if ($_POST['method'] == 'saveProject') {
        extract($_POST);
        if (!empty($name) && !empty($due_date) && !empty($category_id)) {
            $project = new Project(null, $name, $due_date, $category_id, $description, null, null);

            $response = $projectService->createProject($project);
            if ($response) {
                $success = "Projet créé avec succes";
                $projects = $projectService->getProjects();
            } else {
                $error = "Echec de la création";
            }
        } else {
            $error = "ERREUR DE CREATION: Les champs 'Nom du projet', 'Date limite' et 'Catégorie' sont requis";
        }
    }
}

if (isset($_POST['delete_project']) && !empty($_POST['delete_project'])) {
    $delete = $projectService->deleteProject($_POST['delete_project']);
    if ($delete) {
        $success = "Projet supprimé avec succes";
        $projects = $projectService->getProjects();
    } else {
        $error = 'Echec de la suppréssion';
    }
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
                            <h5>Vos Projets</h5>
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <div class="list-grid-toggle d-flex align-items-center mr-3">
                                    <div data-toggle-extra="tab" data-target-extra="#grid" class="active">
                                        <div class="grid-icon mr-3">
                                            <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="3" y="3" width="7" height="7"></rect>
                                                <rect x="14" y="3" width="7" height="7"></rect>
                                                <rect x="14" y="14" width="7" height="7"></rect>
                                                <rect x="3" y="14" width="7" height="7"></rect>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="pl-3 border-left btn-new">
                                    <a href="#" class="btn btn-primary" data-target="#new-project-modal" data-toggle="modal">Nouveau projet</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="grid" class="item-content animate__animated animate__fadeIn active" data-toggle-extra="tab-content">
            <div class="row">
                <?php
                $i = 1;
                foreach ($projects as $project) {
                    $evolution = getPourcentageProject($project->getProjectID());
                    $project_array = array(
                        'project_id' => $project->getProjectID(),
                        'name' => $project->getName(),
                        'due_date' => $project->getDueDate(),
                        'category_id' => $project->getCategoryID(),
                        'description' => $project->getDescription(),
                        'created_at' => $project->getCreatedAt(),
                        'updated_at' => $project->getUpdatedAt()
                    );
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-block card-stretch card-height">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div id="circle-progress-0<?= $i ?>" class="circle-progress-01 circle-progress circle-progress-primary" data-min-value="0" data-max-value="100" data-value="<?= $evolution ?>" data-type="percent"></div>
                                    <i class="ri-star-fill m-0 text-warning"></i>
                                </div>
                                <h5 class="mb-1"><?= $project->getName() ?></h5>
                                <p class="mb-3"><?= substr($project->getDescription(), 0, 50) . '...' ?></p>
                                <div class="d-flex align-items-center justify-content-between pt-3 border-top">

                                    <!-- <input type="hidden" name="method" value="deleteProject" /> -->
                                    <button type="button" onclick="selectProjectID(<?= $project->getProjectID() ?>)" class="btn btn-outline-secondary" data-toggle="modal" data-target=".bd-example-modal-sm">
                                        <i class="ri-delete-bin-line"></i> Supprimer
                                    </button>
                                    <button type="button" onclick="selectProject(<?php echo htmlspecialchars(json_encode($project_array)) ?>);" class="btn btn-outline-success" data-toggle="modal" data-target="#exampleModalCenteredScrollable">
                                        <i class="ri-edit-box-line"></i> Editer
                                    </button>

                                    <a href="?page=project-detail&id=<?=$project->getProjectID()?>" class="btn btn-outline-primary">
                                        <i class="ri-eye-line"></i> Voir tâches
                                    </a>

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

<!-- Modal list start -->
<div class="modal fade" role="dialog" aria-modal="true" id="new-project-modal">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-block text-center pb-3 border-bttom">
                <h3 class="modal-title" id="exampleModalCenterTitle01">Nouveau projet</h3>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="row">
                        <input type="hidden" name="method" value="saveProject">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText01" class="h5">Nom du projet*</label>
                                <input type="text" class="form-control" name="name" id="exampleInputText01" placeholder="Project Name" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Categories *</label>
                                <select name="category_id" class="selectpicker form-control" data-style="py-0">
                                    <option>---------</option>
                                    <?php
                                    foreach ($categories as $category) {
                                    ?>
                                        <option value="<?= $category->getCategoryID() ?>"><?= $category->getName() ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText004" class="h5">Date limite*</label>
                                <input type="date" name="due_date" class="form-control" id="exampleInputText004" value="" require>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText040" class="h5">Description</label>
                                <textarea class="form-control" name="description" id="exampleInputText040" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap align-items-ceter justify-content-center mt-2">
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
                <input type="hidden" name="delete_project" value="" id="id-delete">
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

<div id="exampleModalCenteredScrollable" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Editer Projet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="row">
                        <input type="hidden" name="method" value="updateProject">
                        <input type="hidden" name="project_id">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="name" class="h5">Nom du projet*</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Project Name" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="category_id" class="h5">Categories *</label>
                                <select name="category_id" id="category_id" class="selectpicker form-control" data-style="py-0">
                                    <option>---------</option>
                                    <?php
                                    foreach ($categories as $category) {
                                    ?>
                                        <option value="<?= $category->getCategoryID() ?>"><?= $category->getName() ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="due_date" class="h5">Date limite*</label>
                                <input type="date" name="due_date" class="form-control" id="due_date" value="" require>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="description" class="h5">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap align-items-ceter justify-content-center mt-2">
                                <button type="submit" class="btn btn-primary mr-3">Editer</button>
                                <div class="btn btn-primary" data-dismiss="modal">Annuler</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    const selectProjectID = (projet) => {
        let input = document.getElementById('id-delete')
        input.value = projet;
    }

    const selectProject = (projet) => {
        let name = document.getElementById('name')
        let due_date = document.getElementById('due_date')
        let description = document.getElementById('description')
        let categorries = document.getElementById('category_id')

        name.value = projet?.name;
        due_date.value = new Date(projet?.due_date).toISOString().split('T')[0];
        description.value = projet?.description;


        for (var i = 0; i < categorries.options.length; i++) {
            if (categorries.options[i].value == projet?.category_id) {
                categorries.options[i].selected = true;
                // categorries.value = categorries.options[i].value;
                // console.log(categorries.options[i]);
                break;
            }
        }
        console.log(categorries.value);

        // console.log(projet);
    }
</script>