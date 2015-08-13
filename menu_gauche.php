<?php

if ($_GET['pview'] != 1) {
    //global $twig;
    $tplArrayMenuGauche = [];
    $tplArrayMenuGauche['pview'] = false;
    $path = $_SERVER['PHP_SELF'];
    $file = basename($path);
    if ($file == 'month_all2.php' or Settings::get('menu_gauche') == 0) {
        $tplArrayMenuGauche['menuGaucheMonthAll2'] = true;
        //echo '<div id="menuGaucheMonthAll2">';
    } else {
        $tplArrayMenuGauche['menuGaucheMonthAll2'] = false;
        //echo '<div class="col-lg-3 col-md-12 col-xs-12">';
    }
    //echo '<div id="menuGauche">';

    $pageActuel = str_replace('.php', '', basename($_SERVER['PHP_SELF']));
    minicals($year, $month, $day, $area, $room, $pageActuel);
    if (isset($_SESSION['default_list_type']) || (Settings::get('authentification_obli') == 1)) {
        $area_list_format = $_SESSION['default_list_type'];
    } else {
        $area_list_format = Settings::get('area_list_format');
    }

    /* je récupére an tableau $tplArray par list ou false si il n'y a rien à affichier */
    $tplArrayMenuGauche['siteSelection'] = make_site_selection_fields('week_all.php', $id_site, $year, $month, $day, getUserName(), $area_list_format);

    $tplArrayMenuGauche['areaSelection'] =make_area_selection_fields('week_all.php', $id_site, $area, $year, $month, $day, getUserName(), $area_list_format);

    $tplArrayMenuGauche['roomSelection'] =make_room_selection_fields('week', $area, $room, $year, $month, $day, $area_list_format);


    if (Settings::get('legend') == '0') {
        $tplArrayMenuGauche['showColour'] = show_colour_key($area);
    }
    //var_dump($tplArrayMenuGauche);
    //echo $twig->render('menuGauche.html.twig', $tplArrayMenuGauche);
    //$tplArrayMenuGauche;
}
