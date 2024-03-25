<?php

use function Utils\connexionToBD;

require_once 'utils/connexion.php';

//model
require_once 'models/User.class.php';
require_once 'models/Category.class.php';
require_once 'models/Project.class.php';
require_once 'models/Subtask.class.php';
require_once 'models/Task.class.php';

//services
require_once 'services/auth.service.php';
require_once 'services/category.service.php';
require_once 'services/project.service.php';
require_once 'services/subtask.service.php';
require_once 'services/task.service.php';
require_once 'services/user.service.php';

session_start();

$pdo = connexionToBD();
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once('./partials/head.php');
$connexion_inclus = true; ?>

<body>
    <!-- loader Start -->
    <!-- <div id="loading">
        <div id="loading-center"></div>
    </div> -->
    <!-- loader END -->

    <div class="wrapper">
        <?php
        if (isset($_GET['page']) && $_GET['page'] !== 'signin' && $_GET['page'] !== 'signup') {
            include_once('partials/sideBar.php');
            include_once('partials/navbar.php');
        }
        ?>

        <?php
        if (isset($_GET['page'])) {

            if ($_GET['page'] == 'home') {
                include_once('pages/home.php');
            } elseif ($_GET['page'] == 'home-user') {
                include_once('pages/home-user.php');
            } elseif ($_GET['page'] == 'projects') {
                include_once('pages/page-project.php');
            } elseif ($_GET['page'] == 'tasks') {
                include_once('pages/page-task.php');
            } elseif ($_GET['page'] == 'employees') {
                include_once('pages/page-employee.php');
            } elseif ($_GET['page'] == 'update-profil') {
                include_once('pages/user-profile-edit.php');
            } elseif ($_GET['page'] == 'user-detail') {
                include_once('pages/user-detail.php');
            } elseif ($_GET['page'] == 'project-detail') {
                include_once('pages/project-detail.php');
            } elseif ($_GET['page'] == 'signup') {
                include_once('pages/auth-sign-up.php');
            } else {
                include_once('pages/auth-sign-in.php');
            }
        } else {
            include_once('pages/auth-sign-in.php');
        }
        ?>
    </div>
    <?php
    if (isset($_GET['page']) && $_GET['page'] !== 'signin' && $_GET['page'] !== 'signup') {
        include_once('./partials/footer.php');
    }
    ?>
    <?php include_once('./partials/scripts.php') ?>

</body>

</html>