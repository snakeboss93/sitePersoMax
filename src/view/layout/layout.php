<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Base  -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <!-- CSS  -->
    <link href="css/min.css" type="text/css" rel="stylesheet"/>

    <!-- Meta generales -->
    <link rel="icon" type="image/x-icon" href="/favicon.png"/>
    <meta name="Author" content="Maxime Pardini"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>

    <!-- Title  -->
    <title>TODO</title>

    <!-- Description  -->
    <meta name="description" content="TODO"/>

    <!-- Meta OG || twitter || google -->
    {% block og %}{% endblock %}
    <meta property="og:type" content="article"/>
    <meta property="og:image" content="http://4erudition.fr{{ asset('img/logoMenu.png') }}"/>
    <meta name="twitter:card" content="http://4erudition.fr{{ asset('img/logoMenu.png') }}"/>
    <meta name="twitter:title" content="Participation de 4Erudition au 4L Trophy 2017 !">
    <meta name="twitter:description" content="Nous sommes deux étudiants et amis qui avons pour objectif de participer au 4L Trophy de 2017." />
    <meta name="twitter:creator" content="Pierre PEREZ"/>
    <meta name="twitter:site" content="Pierre PEREZ"/>
    <meta name="twitter:image" content="http://4erudition.fr{{ asset('img/logoMenu.png') }}"/>
    <link href="https://plus.google.com/collections/featured" rel="publisher" />

    <!-- Robot -->
    <meta name="robots" content="index, follow"/>
</head>

<body>
<noscript>
    Attention, ne pas activer le JavaScript peut amener à des problèmes de visibilité sur ce site.
</noscript>

<div class="container-fluid">
    <div class="hidden-lg hidden-md hidden-sm">
        <span id="menu-mobile-toggle">&#9776; Menu</span>
    </div>
    <div class="row">
        <nav id="nav-total" class="col-sm-3 col-lg-2 navbar">
            <div class="container-fluid">
                <div class="hidden-lg hidden-sm hidden-md" id="decalage-mobile"></div>
                <a href="?action=index">
                    <img src="images/favicon.png" height="325" width="325" class="img-logo text-center"
                         alt="logo du site">
                    <h1 class="sr-only">ifacebook</h1>
                    <p class="h1">acebook</p>
                </a>
                <h2 class="sr-only">Navigation</h2>
                <?php
                if (\lib\core\Context::getSessionAttribute('id')) {
                    include('menu.php');
                } else {
                    include('menuOut.php');
                }
                ?>
            </div>
        </nav>
        <section class="col-sm-9 col-lg-10 col-lg-offset-2 col-sm-offset-3">
            <div class="well wrapper-well">
                <div id="principale-well">
                    <?php include($template_view); ?>
                </div>
                <div id="spinner"></div>
            </div>
        </section>
    </div>
</div>

<footer>
    <?php include('footer.php'); ?>
</footer>

<!-- Scripts -->
<script src="js/min.js"></script>
</body>
</html>
