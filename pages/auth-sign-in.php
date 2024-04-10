<?php

use Model\User;
// use function Services\login;
// use function Services\register;

$error = null;

if (isset($_POST['username']) && isset($_POST['password'])) {
   if (!empty($_POST['username']) && !empty($_POST['password'])) {
      extract($_POST);
      $user = \Services\login($pdo, $username, $password);
      if ($user == null) {
         $error = "Identifiant incorrect";
      } else {
         $_SESSION['user'] = $user;
         $location = $user->getRole() == 'admin' ? 'home' : 'home-user';

         header('Location:?page=' . $location);
      }
   } else {
      $error = "veuillez remplir tous les champs";
   }
}

?>

<section class="login-content">
   <div class="container">
      <div class="row align-items-center justify-content-center height-self-center">
         <div class="col-lg-8">
            <div class="card auth-card">
               <div class="card-body p-0">
                  <div class="d-flex align-items-center auth-content">
                     <div class="col-lg-6 bg-primary content-left">
                        <div class="p-3">
                           <?php if ($error) { ?>
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
                           <h2 class="mb-2 text-white">Connexion</h2>
                           <p>Connectez-vous</p>
                           <form method="post">
                              <div class="row">
                                 <div class="col-lg-12">
                                    <div class="floating-label form-group">
                                       <input class="floating-input form-control" name="username" type="text" placeholder=" ">
                                       <label>username</label>
                                    </div>
                                 </div>
                                 <div class="col-lg-12">
                                    <div class="floating-label form-group">
                                       <input class="floating-input form-control" type="password" name="password" placeholder=" ">
                                       <label>Mot de passe</label>
                                    </div>
                                 </div>

                              </div>
                              <button type="submit" class="btn btn-white">Connexion</button>
                              <p class="mt-3">
                                 Retourner à l'écan <a href="?page=landing" class="text-white text-underline">d'accueil</a>
                              </p>
                           </form>
                        </div>
                     </div>
                     <div class="col-lg-6 content-right">
                        <img src="assets/images/login/01.png" class="img-fluid image-right" alt="">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>