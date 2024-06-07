<?php
require_once 'google2.php';

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $userinfo = [
    'email' => $google_account_info['email'],
    'first_name' => $google_account_info['givenName'],
    'last_name' => $google_account_info['familyName'],
    'gender' => $google_account_info['gender'],
    'full_name' => $google_account_info['name'],
    'picture' => $google_account_info['picture'],
    'verifiedEmail' => $google_account_info['verifiedEmail'],
    'token' => $google_account_info['id'],
  ];

  // checking if user is already exists in database
  $sql = "SELECT * FROM users WHERE email ='{$userinfo['email']}'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // user is exists
    $userinfo = mysqli_fetch_assoc($result);
    $token = $userinfo['token'];
  } else {
    // user is not exists
    $sql = "INSERT INTO users (email, first_name, last_name, gender, full_name, picture, verifiedEmail, token) VALUES ('{$userinfo['email']}', '{$userinfo['first_name']}', '{$userinfo['last_name']}', '{$userinfo['gender']}', '{$userinfo['full_name']}', '{$userinfo['picture']}', '{$userinfo['verifiedEmail']}', '{$userinfo['token']}')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $token = $userinfo['token'];
    } else {
      echo "User is not created";
      die();
    }
  }

  // save user data into session
  $_SESSION['user_token'] = $token;
} else {
  if (!isset($_SESSION['user_token'])) {
    header("Location: login.php");
    die();
  }

  // checking if user is already exists in database
  $sql = "SELECT * FROM users WHERE token ='{$_SESSION['user_token']}'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // user is exists
    $userinfo = mysqli_fetch_assoc($result);
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phantesis</title>
    <link rel="shortcut icon" href="imagenes/Logo.png" type="image/x-icon">

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/pagina.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link rel="stylesheet" href="css/perfil.css">

</head>

<body>

    <!-- header section starts  -->

    <header class="header">

        <div class="flex">

            <a href="#home" class="logo"><img src="imagenes/Logo.png" alt=""></a>

            <nav class="navbar">
                <a onclick="dia()" href="#"><img style="width:30px;height: 30px;" src="imagenes/sun.png" alt="dia"></a>
                <a onclick="noche()" href="#"><img style="width:30px;height: 30px;" src="imagenes/luna.png" alt="noche"></a>
                <a href="#home">Inicio</a>
                <a href="#about">Phantesis</a>
              
                <a href="#server">Servicios</a>
                <a href="#gallery">Galería</a>
                <a href="#team">Nuestro Equipo</a>
                <a href="contactenos.php">Contactanos</a>
               <a > <div id="user-btn" class='bx bxs-user-circle'></div></a>
                <div class="profile"  >
                <img  src="<?= $userinfo['picture'] ?>" alt="" width="90px" height="90px">
                <a href="logout.php" onclick="return confirm('Realmente quieres cerrar sesión?');" class="btn1" >Salir</a>
  
                </div>
                
        
                
            </nav>


            <div id="menu-btn" class="fas fa-bars"></div>

        </div>

    </header>

    <a href="#top" class="scroll-top-btn">
        <span  class="icon-angle-up">⬆</span>
    </a>

    <!-- header section ends -->

    <!-- home section starts  -->

    <div class="home-bg">

        <section class="home" id="home">

            <div class="content">
                <h3>Phantesis</h3>
                <p>Para el desarrollo de las competencias literarias y de escritura, leer, compartir y editar historias; el unico limite es tu imaginacion.</p>
                <a href="#team" class="btn">Sobre Nosotros</a>
            </div>

        </section>

    </div>

    <!-- home section ends -->

    <!-- about section starts  -->

    <section class="about" id="about">

        <div class="image">
            <img src="imagenes/logo2.webp" alt="">
        </div>

        <div class="content">
            <h3>una lectura Puede Completar Tu Día</h3>
            <p>puedes crear historias y disfrutar de la lectura diaria.</p>
            <a href="#server" class="btn">Nuestros Servicios</a>
        </div>

    </section>

    <!-- about section ends -->

    <!-- facility section starts  -->

    <section class="facility" id="server">

        <div class="heading">
            <img src="imagenes/" alt="">
            <h3>Nuestros servicios</h3>
        </div>

        <div class="box-container">

            <div class="box">
                <a href="#"><img src="imagenes/crear.avif" alt=""></a>
                <h3>crea tu historia</h3>
                <p>Disfruta de tu creatividad para crear mundos nuevos</p>
            </div>

            <div class="box">
                <a href="#"><img src="imagenes/leer.jpg" alt=""></a>
                <h3>lectura</h3>
                <p>lee historias de diferentes usuari@s</p>
            </div>

            <div class="box">
                <a href="#"><img src="imagenes/juegos.jpg" alt=""></a>
                <h3>juegos creativos</h3>
                <p>disfruta de un mini juego para impulsar tu creatividad</p>
            </div>

            <div class="box">
                <a href="#"><img src="imagenes/lapi.jpg" alt=""></a>
                <h3>editar historias </h3>
                <p>disfruta de editar historias de los demas usuarios </p>
            </div>

        </div>

    </section>

    <!-- facility section ends -->

    <!-- menu section starts  -->

    <section class="menu" id="menu">

        <div class="heading">
            <img src="images/heading-img.png" alt="">
            
            <h3>Categorías</h3>
        </div>

        <div class="box-container">

            <div class="box">
                <img src="imagenes/romance.jpeg" alt="">
                <h3>Romance</h3>
            </div>

            <div class="box">
                <img src="imagenes/terror.jpg" alt="">
                <h3>Terror</h3>
            </div>

            <div class="box">
                <img src="imagenes/ficcion.jpeg" alt="">
                <h3>Ficcion</h3>
            </div>

            <div class="box">
                <img src="imagenes/fantasia.jpeg" alt="">
                <h3>Fantasia</h3>
            </div>

            <div class="box">
                <img src="imagenes/poesia.jpeg" alt="">
                <h3>Poesia</h3>
            </div>

            <div class="box">
                <img src="imagenes/no ficcion.jpg" alt="">
                <h3>No-Ficción</h3>
            </div>

        </div>

    </section>

    <!-- menu section ends -->

    <!-- gallery section starts  -->

    <section class="gallery" id="gallery">

        <div class="heading">
            <img src="images/heading-img.png" alt="">
            
            <h3>Descarga un libro gratis!</h3>
        </div>

        <div class="box-container">
            <img src="images/gallery-1.webp" alt="">
            <img src="images/gallery-2.webp" alt="">
            <img src="images/gallery-3.webp" alt="">
            <img src="images/gallery-4.webp" alt="">
            <img src="images/gallery-5.webp" alt="">
            <img src="images/gallery-6.webp" alt="">
        </div>

    </section>

    <!-- gallery section ends -->

    <!-- team section starts  -->

    <section class="team" id="team">

        <div class="heading">
            <img src="images/heading-img.png" alt="">
            <h3>Nuestro Equipo de Trabajo</h3>
        </div>

        <div class="box-container">

            <div class="box">
                <img src="imagenes/Adrian.jpeg" alt="">
                <h3>Adrian</h3>
            </div>
            <div class="box">
                <img src="imagenes/Brayan.jpeg" alt="">
                <h3>Braian</h3>
            </div>
            <div class="box">
                <img src="imagenes/Felipe.jpeg" alt="">
                <h3>Felipe</h3>
            </div>
            <div class="box">
                <img src="imagenes/Miguel.jpeg" alt="">
                <h3>Miguel</h3>
            </div>
            <div class="box">
                <img src="imagenes/susana.jpg" alt="">
                <h3>Susana</h3>
            </div>
            <div class="box">
                <img src="imagenes/fer.jpg" alt="">
                <h3>Luisa</h3>
            </div>

        </div>

    </section>





    <!-- footer section starts  -->

    <section class="footer">

        <div class="box-container">

            <div class="box">
                <i class="fas fa-envelope"></i>
                <h3>Nuestro Correo Electrónico</h3>
                <p>phantesis@gmail.com</p>
                
            </div>

            <div class="box">
                <i class="fas fa-clock"></i>
                <h3>Horario</h3>
                <p>07:00am to 09:00pm</p>
            </div>

            <div class="box">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Ubicación</h3>
                <p>Medellin, Colombia - Codigo Postal 0500005</p>
            </div>

            <div class="box">
                <i class="fas fa-phone"></i>
                <h3>Números de contacto</h3>
                <p>(057)(604)3125456321</p>
                <p>(057)(604)3005578521</p>
            </div>

        </div>

        <div class="credit"> &copy; copyright <span>Phantesis</span> | Todos los derechos reservedos! </div>

    </section>

    <!-- footer section ends -->

    <!-- custom js file link  -->
    <script defer src="js/app.js"></script>
    <script defer src="js/script.js"></script>
    <script src="js/sweetalert2@11.js"></script>
    <script src="js/responsive.js"></script>
    <script>
Swal.fire({
  icon: "success",
  title: "Bienvenido:  <?= $userinfo['full_name'] ?>",
  text: "Correo: <?= $userinfo['email'] ?>",
   
 
});
    </script>

</body>

</html>
