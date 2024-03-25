<?php

use Model\User;
use Services\UserService;

$user = $_SESSION['user'];
$userService = new UserService($pdo);

$success = null;
$error = null;

if (isset($_POST['method'])) {
    if ($_POST['method'] == 'updateUser') {
        extract($_POST);
        if (!empty($username) && !empty($email) && !empty($full_name) && !empty($phone)) {
            $newuser = new User($user->getUserID(), $username, $email, '', $full_name, $phone, 'employe', null, null);

            $response = $userService->updateUser($newuser);
            if ($response) {
                $success = "Informations mis à jour avec succes";
                $user = $userService->getUserByID($user->getUserID());
                $_SESSION['user'] = $user;
            } else {
                $error = "Echec de la mis à jour";
            }
        } else {
            $error = "ERREUR DE CREATION: Les champs 'Username', 'Nom complet', 'Téléphone', et 'Email' sont requis";
        }
    } else if ($_POST['method'] == 'updatePassword') {
        extract($_POST);
        if (!empty($current_password) && !empty($password) && !empty($confirm_password)) {
            $response = $userService->updatePassword($user->getUserID(), $current_password, $password, $confirm_password);
            if ($response == 'success') {
                $success = "Mot de passe mis à jour avec succes";
                $user = $userService->getUserByID($user->getUserID());
                $_SESSION['user'] = $user;
            } else {
                $error = $response;
            }
        } else {
            $error = "ERREUR DE CREATION: Les champs 'Username', 'Nom complet', 'Téléphone', et 'Email' sont requis";
        }
    }
}

?>
<div class="content-page">
    <div class="container-fluid">
        <?php
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
                                    <a class="nav-link active" data-toggle="pill" href="#personal-information">
                                        Informations personelles
                                    </a>
                                </li>
                                <li class="col-md-3 p-0">
                                    <a class="nav-link" data-toggle="pill" href="#chang-pwd">
                                        Changer mot de passe
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
                        <div class="tab-pane fade active show" id="personal-information" role="tabpanel">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Informations personelles</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <input type="hidden" name="method" value="updateUser">
                                        <div class=" row align-items-center">
                                            <div class="form-group col-sm-6">
                                                <label for="fname">Nom complet:</label>
                                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $user->getFullName() ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="uname">Nom d'utilisateur:</label>
                                                <input type="text" class="form-control" name="username" value="<?= $user->getUsername() ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="email">Email:</label>
                                                <input type="email" class="form-control" name="email" id="email" value="<?= $user->getEmail() ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="phone">Téléphone:</label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="<?= $user->getPhone() ?>">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                                        <!-- <button type="reset" class="btn iq-bg-danger">Cancel</button> -->
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="chang-pwd" role="tabpanel">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <div class="iq-header-title">
                                        <h4 class="card-title">Changer le mot de passe</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="post">
                                        <input type="hidden" name="method" value="updatePassword">
                                        <div class="form-group">
                                            <label for="cpass">Mot de passe actuel:</label>
                                            <input type="Password" name="current_password" class="form-control" id="cpass" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="npass">Nouveau mot de passe:</label>
                                            <input type="Password" name="password" class="form-control" id="npass" value="">
                                        </div>
                                        <div class="form-group">
                                            <label for="vpass">Confirmer mot de passe:</label>
                                            <input type="Password" name="confirm_password" class="form-control" id="vpass" value="">
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2">Enregistrer</button>
                                        <button type="reset" class="btn iq-bg-danger">Annuler</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>