<?php

use Model\User;
use Services\UserService;

$userService = new UserService($pdo);

$users = $userService->getUsers();
$success = null;
$error = null;

if (isset($_POST['method'])) {
    if ($_POST['method'] == 'saveUser') {
        extract($_POST);
        if (!empty($username) && !empty($email) && !empty($password) && !empty($full_name) && !empty($phone)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $user = new User(null, $username, $email, $hashedPassword, $full_name, $phone, 'employe', null, null);

            $response = $userService->createUser($user);
            if ($response) {
                $success = "Stagiaire créé avec succes";
                $users = $userService->getUsers();
            } else {
                $error = "Echec de la création";
            }
        } else {
            $error = "ERREUR DE CREATION: Les champs 'Username', 'Nom complet', 'Téléphone', 'Email' et 'Mot de passe' sont requis";
        }
    } elseif ($_POST['method'] == 'updateUser') {
        extract($_POST);
        if (!empty($username) && !empty($email) && !empty($user_id) && !empty($full_name) && !empty($phone)) {
            $user = new User($user_id, $username, $email, '', $full_name, $phone, 'employe', null, null);

            $response = $userService->updateUser($user);
            if ($response) {
                $success = "Informations mis à jour avec succes";
                $users = $userService->getUsers();
            } else {
                $error = "Echec de la mis à jour";
            }
        } else {
            $error = "ERREUR DE CREATION: Les champs 'Username', 'Nom complet', 'Téléphone', et 'Email' sont requis";
        }
    }
}


if (isset($_POST['delete_user']) && !empty($_POST['delete_user'])) {
    $delete = $userService->deleteUser($_POST['delete_user']);
    if ($delete) {
        $success = "Stagiaire supprimé avec succes";
        $users = $userService->getUsers();
    } else {
        $error = 'Echec de la suppréssion';
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
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between breadcrumb-content">
                            <h5>Vos Stagiaires</h5>
                            <div class="d-flex align-items-center">
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
                                    <a href="#" class="btn btn-primary" data-target="#new-user-modal" data-toggle="modal">Nouveau stagiaire</a>
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
                foreach ($users as $user) {
                    $user_array = array(
                        'user_id' => $user->getUserID(),
                        'username' => $user->getUsername(),
                        'email' => $user->getEmail(),
                        'password' => $user->getPassword(),
                        'full_name' => $user->getFullName(),
                        'phone' => $user->getPhone(),
                        'role' => $user->getRole(),
                        'created_at' => $user->getCreatedAt(),
                        'updated_at' => $user->getUpdatedAt()
                    );
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card-transparent card-block card-stretch card-height">
                            <div class="card-body text-center p-0">
                                <div class="item">
                                    <div class="odr-img">
                                        <img src="assets/images/user/user.png" class="img-fluid rounded-circle avatar-90 m-auto" alt="image">
                                    </div>
                                    <div class="odr-content rounded">
                                        <h4 class="mb-2"><?= $user->getFullName() ?></h4>
                                        <p class="mb-1">Username : <?= $user->getUsername() ?></p>
                                        <p class="mb-3"><?= $user->getEmail() ?></p>
                                        <ul class="list-unstyled mb-3">
                                            <li class="bg-primary-light"><i class="ri-phone-line m-0"></i><?= $user->getPhone() ?></li>
                                        </ul>
                                        <div class="pt-3 border-top">
                                            <a href="#" onclick="selectUserID(<?= $user->getUserID()  ?>)" class="btn btn-outline-secondary" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="ri-delete-bin-line"></i> Supprimer</a>
                                            <a href="#" class="btn btn-outline-success ml-2" onclick="selectUser(<?php echo htmlspecialchars(json_encode($user_array)) ?>);" data-target="#update-user-modal" data-toggle="modal"><i class="ri-edit-box-line"></i> Editer</a>
                                            <a href="?page=user-detail&id=<?= $user->getUserID() ?>" class="btn btn-outline-primary ml-2"><i class="ri-eye-line"></i> Vois tâches</a>
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
        <!-- Page end  -->
    </div>
</div>
<!-- Wrapper End-->

<!-- Modal list start -->
<div class="modal fade bd-example-modal-lg" role="dialog" aria-modal="true" id="new-user-modal">
    <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-block text-center pb-3 border-bttom">
                <h3 class="modal-title" id="exampleModalCenterTitle02">Nouveau stagiaire</h3>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="row">
                        <input type="hidden" name="method" value="saveUser">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Username*</label>
                                <input type="text" name="username" class="form-control" id="exampleInputText2" placeholder="Entrer username" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText2" class="h5">Nom complet</label>
                                <input type="text" name="full_name" class="form-control" id="exampleInputText2" placeholder="Entrer nom complet" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText04" class="h5">Téléphone*</label>
                                <input type="text" name="phone" class="form-control" id="exampleInputText04" placeholder="Entrer téléphone" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="exampleInputText006" class="h5">Email*</label>
                                <input type="email" name="email" class="form-control" id="exampleInputText006" placeholder="Entrer email" require>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="exampleInputText006" class="h5">Mot de passe*</label>
                                <input type="text" name="password" class="form-control" id="exampleInputText006" placeholder="Entrer mot de passe" require>
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

<div class="modal fade bd-example-modal-lg" role="dialog" aria-modal="true" id="update-user-modal">
    <div class="modal-dialog  modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header d-block text-center pb-3 border-bttom">
                <h3 class="modal-title" id="exampleModalCenterTitle02">Editer stagiaire</h3>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="row">
                        <input type="hidden" name="method" value="updateUser">
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="username" class="h5">Username*</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Entrer username" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="full_name" class="h5">Nom complet</label>
                                <input type="text" name="full_name" id="full_name" class="form-control" placeholder="Entrer nom complet" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="phone" class="h5">Téléphone*</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Entrer téléphone" require>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="email" class="h5">Email*</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Entrer email" require>
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
                <input type="hidden" name="delete_user" value="" id="id-delete">
                <div class="modal-header">
                    <h5 class="modal-title">Supprimer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Voulez vous vraiment supprimer ce stagiaire ?</p>
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
    const selectUserID = (user_id) => {
        let input = document.getElementById('id-delete')
        input.value = user_id;
    }

    const selectUser = (user) => {
        let user_id = document.getElementById('user_id')
        let username = document.getElementById('username')
        let full_name = document.getElementById('full_name')
        let phone = document.getElementById('phone')
        let email = document.getElementById('email')

        user_id.value = user?.user_id;
        username.value = user?.username;
        full_name.value = user?.full_name;
        phone.value = user?.phone;
        email.value = user?.email;

        console.log(user);
    }
</script>