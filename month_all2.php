<?php

/**
 * month_all2.php
 * Interface d'accueil avec affichage par mois des réservation de toutes les ressources d'un domaine
 * Ce script fait partie de l'application GRR.
 *
 * @author    Laurent Delineau <laurent.delineau@ac-poitiers.fr>
 * @copyright Copyright 2003-2008 Laurent Delineau
 * @author    Bouteillier Nicolas <contact@kaizendo.fr>
 * @copyright Copyright 2015 Bouteillier Nicolas
 *
 * @link      http://www.gnu.org/licenses/licenses.html
 *
 * This file is part of GRR.
 *
 * GRR is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GRR is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GRR; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
include 'include/connect.inc.php';
include 'include/config.inc.php';
include 'include/misc.inc.php';
include 'include/functions.inc.php';
include "include/$dbsys.inc.php";
include 'include/mincals.inc.php';
include 'include/mrbs_sql.inc.php';
include 'include/init.php';
$grr_script_name = 'month_all2.php';
require_once './include/settings.class.php';
$tplArray = [];
if (!Settings::load()) {
    die('Erreur chargement settings');
}
require_once './include/session.inc.php';
include 'include/resume_session.php';
Definition_ressource_domaine_site();
get_planning_area_values($area);
include 'include/language.inc.php';
$affiche_pview = '1';
if (!isset($_GET['pview'])) {
    $_GET['pview'] = 0;
} else {
    $_GET['pview'] = 1;
}
/*if ($_GET['pview'] == 1) {
    $class_image = 'print_image';
} else {
    $class_image = 'image';
}*/
$tplArray['pview'] = $_GET['pview'];

if (empty($debug_flag)) {
    $debug_flag = 0;
}
if (empty($month) || empty($year) || !checkdate($month, 1, $year)) {
    $month = date('m');
    $year = date('Y');
}
if (!isset($day)) {
    $day = 1;
}
if ((Settings::get('authentification_obli') == 0) && (getUserName() == '')) {
    $type_session = 'no_session';
    $tplArray['typeSession'] = 'no_session';
} else {
    $type_session = 'with_session';
    $tplArray['typeSession'] = 'with_session';
}
if ($type_session == 'with_session') {
    $_SESSION['type_month_all'] = 'month_all2';
}
$type_month_all = 'month_all2';
$back = '';
if (isset($_SERVER['HTTP_REFERER'])) {
    $back = htmlspecialchars($_SERVER['HTTP_REFERER']);
}
print_header($day, $month, $year, $type_session, false);

if (check_begin_end_bookings($day, $month, $year)) {
    showNoBookings($day, $month, $year, $back);
    exit();
}
if (((authGetUserLevel(getUserName(), -1) < 1) && (Settings::get('authentification_obli') == 1)) || authUserAccesArea(getUserName(), $area) == 0) {
    showAccessDenied($back);
    exit();
}
if (Settings::get('verif_reservation_auto') == 0) {
    verify_confirm_reservation();
    verify_retard_reservation();
}
$month_start = mktime(0, 0, 0, $month, 1, $year);
$weekday_start = (date('w', $month_start) - $weekstarts + 7) % 7;
$days_in_month = date('t', $month_start);
$month_end = mktime(23, 59, 59, $month, $days_in_month, $year);
if ($enable_periods == 'y') {
    $resolution = 60;
    $morningstarts = 12;
    $eveningends = 12;
    $eveningends_minutes = count($periods_name) - 1;
}
$this_area_name = '';
$this_room_name = '';
$this_area_name = grr_sql_query1('SELECT area_name FROM '.TABLE_PREFIX."_area WHERE id=$area");
$i = mktime(0, 0, 0, $month - 1, 1, $year);
$yy = date('Y', $i);
$ym = date('n', $i);
$i = mktime(0, 0, 0, $month + 1, 1, $year);
$ty = date('Y', $i);
$tm = date('n', $i);

include 'menu_gauche.php';
$tplArray['tplArrayMenuGauche'] = $tplArrayMenuGauche;

//include 'chargement.php';

$tplArray['vocab']['monthbefore'] = get_vocab('monthbefore');
$tplArray['vocab']['monthafter'] = get_vocab('monthafter');
$tplArray['vocab']['all_areas'] = get_vocab('all_areas');
$tplArray['vocab']['change_view'] = get_vocab('change_view');
$tplArray['vocab']['rep_type_6'] = get_vocab('rep_type_6');

/*echo '<div id="planningMonthAll2">';
echo '<div class="titre_planning"><table class="table-header">';*/
if ((!isset($_GET['pview'])) or ($_GET['pview'] != 1)) {
    $tplArray['linkMonthBefore'] = 'month_all2.php?year='.$yy.'&month='.$ym.'&area='.$area;
    $tplArray['linkMonthAfter'] = 'month_all2.php?year='.$ty.'&month='.$tm.'&area='.$area;
/*    echo "\n
	<tr>
		<td class=\"left\">
			<input type=\"button\" class=\"btn btn-default btn-xs\" onclick=\"charger();javascript: location.href='month_all2.php?year=$yy&amp;month=$ym&amp;area=$area';\" value=\"&lt;&lt; ".get_vocab('monthbefore').' "/>
		</td>';

    echo ' <td>';
    include 'include/trailer.inc.php';
    echo "</td>

		<td class=\"right\">
			<input type=\"button\" class=\"btn btn-default btn-xs\" onclick=\"charger();javascript: location.href='month_all2.php?year=$ty&amp;month=$tm&amp;area=$area';\" value=\" ".get_vocab('monthafter').'  &gt;&gt;"/>
		</td>
	</tr>';

    echo '<tr>';
    echo '<td class="left"> ';
    $month_all2 = 1;
    echo "<input type=\"button\" class=\"btn btn-default btn-xs\" id=\"voir\" value=\"Afficher le menu à gauche.\" onClick=\"divaffiche($month_all2)\" style=\"display:inline;\" /> ";
    echo '</td>';*/
}
/*echo ' <td>';*/
$tplArray['monthStart'] = utf8_strftime('%B %Y', $month_start);
$tplArray['thisAreaName'] = $this_area_name;

/*echo '<h4 class="titre">'.ucfirst(utf8_strftime('%B %Y', $month_start)).' '.ucfirst($this_area_name).' - '.get_vocab('all_areas').'</h4>';*/
if ($_GET['pview'] != 1) {
    $tplArray['hrefChangeView'] = 'month_all.php?year='.$year.'&month='.$month.'&area='.$area;
    /*echo " <a href=\"month_all.php?year=$year&amp;month=$month&amp;area=$area\"><img src=\"img_grr/change_view.png\" alt=\"".get_vocab('change_view').'" title="'.get_vocab('change_view').'" class="image" /></a>';*/
}
/*echo ' </td>';
echo ' </tr>';
echo '</table>';
echo "</div>\n";*/
/*if ($_GET['pview'] == 1 && $_GET['precedent'] == 1) {
    echo '<span id="lienPrecedent">
	<button class="btn btn-default btn-xs" onclick="charger();javascript:history.back();">Précedent</button>
</span>';
}*/
if ($_GET['pview'] == 1 && $_GET['precedent'] == 1) {
    $tplArray['precedant'] = true;
}
$all_day = preg_replace('/ /', ' ', get_vocab('all_day'));
$sql = 'SELECT start_time, end_time,'.TABLE_PREFIX.'_entry.id, name, beneficiaire, room_name, statut_entry, '.TABLE_PREFIX.'_entry.description, '.TABLE_PREFIX.'_entry.option_reservation, '.TABLE_PREFIX.'_room.delais_option_reservation, type, '.TABLE_PREFIX.'_entry.moderate
FROM '.TABLE_PREFIX.'_entry inner join '.TABLE_PREFIX.'_room on '.TABLE_PREFIX.'_entry.room_id='.TABLE_PREFIX."_room.id
WHERE (start_time <= $month_end AND end_time > $month_start and area_id='".$area."')
ORDER by start_time, end_time, ".TABLE_PREFIX.'_room.room_name';
$res = grr_sql_query($sql);
/*var_dump($sql);*/
if (!$res) {
    echo grr_sql_error();
} else {
    //echo ' <div class="contenu_planning">';
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
        $sql_beneficiaire = 'SELECT prenom, nom FROM '.TABLE_PREFIX."_utilisateurs WHERE login='$row[4]'";
        $res_beneficiaire = grr_sql_query($sql_beneficiaire);
        if ($res_beneficiaire) {
            $row_user = grr_sql_row($res_beneficiaire, 0);
        }
        if ($debug_flag) {
            echo "<br />DEBUG: result $i, id $row[2], starts $row[0], ends $row[1]\n";
        }
        $t = max((int) $row[0], $month_start);
        $end_t = min((int) $row[1], $month_end);
        $day_num = date('j', $t);
        if ($enable_periods == 'y') {
            $midnight = mktime(12, 0, 0, $month, $day_num, $year);
        } else {
            $midnight = mktime(0, 0, 0, $month, $day_num, $year);
        }
        while ($t < $end_t) {
            if ($debug_flag) {
                echo "<br />DEBUG: Entry $row[2] day $day_num\n";
            }
            $d[$day_num]['id'][] = $row[2];
            $temp = '';
            if (Settings::get('display_info_bulle') == 1) {
                $temp = get_vocab('reservee au nom de').$row_user[0].' '.$row_user[1];
            } elseif (Settings::get('display_info_bulle') == 2) {
                $temp = $row[7];
            }
            if ($temp != '') {
                $temp = ' - '.$temp;
            }
            $d[$day_num]['who1'][] = affichage_lien_resa_planning($row[3], $row[2]);
            $d[$day_num]['room'][] = $row[5];
            $d[$day_num]['res'][] = $row[6];
            $d[$day_num]['color'][] = $row[10];
            $d[$day_num]['type'][] = grr_sql_query1('SELECT type_name FROM '.TABLE_PREFIX."_type_area WHERE type_letter='".$row[10]."'");
            if ($row[9] > 0) {
                $d[$day_num]['option_reser'][] = $row[8];
            } else {
                $d[$day_num]['option_reser'][] = -1;
            }
            $d[$day_num]['moderation'][] = $row[11];
            $midnight_tonight = $midnight + 86400;
            if ($enable_periods == 'y') {
                $start_str = preg_replace('/ /', ' ', period_time_string($row[0]));
                $end_str = preg_replace('/ /', ' ', period_time_string($row[1], -1));
                switch (cmp3($row[0], $midnight).cmp3($row[1], $midnight_tonight)) {
                    case '> < ':
                    case '= < ':
                    if ($start_str == $end_str) {
                        $d[$day_num]['data'][] = $start_str;
                    } else {
                        $d[$day_num]['data'][] = $start_str.get_vocab('to').$end_str;
                    }
                    break;
                    case '> = ':
                    $d[$day_num]['data'][] = $start_str.get_vocab('to').'24:00';
                    break;
                    case '> > ':
                    $d[$day_num]['data'][] = $start_str.get_vocab('to').'&gt;';
                    break;
                    case '= = ':
                    $d[$day_num]['data'][] = $all_day;
                    break;
                    case '= > ':
                    $d[$day_num]['data'][] = $all_day.'&gt;';
                    break;
                    case '< < ':
                    $d[$day_num]['data'][] = '&lt;'.get_vocab('to').$end_str;
                    break;
                    case '< = ':
                    $d[$day_num]['data'][] = '&lt;'.$all_day;
                    break;
                    case '< > ':
                    $d[$day_num]['data'][] = '&lt;'.$all_day.'&gt;';
                    break;
                }
            } else {
                switch (cmp3($row[0], $midnight).cmp3($row[1], $midnight_tonight)) {
                    case '> < ':
                    case '= < ':
                    $d[$day_num]['data'][] = date(hour_min_format(), $row[0]).get_vocab('to').date(hour_min_format(), $row[1]);
                    break;
                    case '> = ':
                    $d[$day_num]['data'][] = date(hour_min_format(), $row[0]).get_vocab('to').'24:00';
                    break;
                    case '> > ':
                    $d[$day_num]['data'][] = date(hour_min_format(), $row[0]).get_vocab('to').'&gt;';
                    break;
                    case '= = ':
                    $d[$day_num]['data'][] = $all_day;
                    break;
                    case '= > ':
                    $d[$day_num]['data'][] = $all_day.'&gt;';
                    break;
                    case '< < ':
                    $d[$day_num]['data'][] = '&lt;'.get_vocab('to').date(hour_min_format(), $row[1]);
                    break;
                    case '< = ':
                    $d[$day_num]['data'][] = '&lt;'.$all_day;
                    break;
                    case '< > ':
                    $d[$day_num]['data'][] = '&lt;'.$all_day.'&gt;';
                    break;
                }
            }
            if ($row[1] <= $midnight_tonight) {
                break;
            }
            ++$day_num;
            $t = $midnight = $midnight_tonight;
        }
    }
}
if ($debug_flag) {
    echo '<p>DEBUG: Array of month day data:<p><pre>'.PHP_EOL;
    for ($i = 1; $i <= $days_in_month; ++$i) {
        if (isset($d[$i]['id'])) {
            $n = count($d[$i]['id']);
            echo 'Day '.$i.' has '.$n.' entries:'.PHP_EOL;
            for ($j = 0; $j < $n; ++$j) {
                echo '  ID: '.$d[$i]['id'][$j].
            ' Data: '.$d[$i]['data'][$j]."\n";
            }
        }
    }
    echo '</pre>'.PHP_EOL;
}
$weekcol = 0;
//echo '<table class="table-bordered">'.PHP_EOL;
$sql = 'SELECT room_name, capacity, id, description FROM '.TABLE_PREFIX."_room WHERE area_id=$area ORDER BY order_display,room_name";
$res = grr_sql_query($sql);
//echo "<tr><th></th>\n";
//$t2 = mktime(0, 0, 0, $month, 1, $year);
for ($k = 1; $k <= $days_in_month; $k++) {
    /* un tour de boucle par jour du mois */
    $t2 = mktime(0, 0, 0, $month, $k, $year);
    $cday = date('j', $t2);
    $cweek = date('w', $t2);
    $name_day = ucfirst(utf8_strftime('%a %d', $t2));
    $temp = mktime(0, 0, 0, $month, $cday, $year);
    $jour_cycle = grr_sql_query1('SELECT Jours FROM '.TABLE_PREFIX."_calendrier_jours_cycle WHERE DAY='$temp'");
    //$t2 += 86400;
    $t2 = mktime(0, 0, 0, $month, $k, $year);
    if ($display_day[$cweek] == 1) {
        $tplArray['joursDuMois'][$k]['display'] = true;
        $tplArray['joursDuMois'][$k]['nom'] = utf8_strftime('%a %d', $t2);
        //echo "<th class=\"tableau_month_all2\">$name_day";
        if (Settings::get('jours_cycles_actif') == 'Oui' && intval($jour_cycle) > -1) {
            if (intval($jour_cycle) > 0) {
                $tplArray['joursDuMois'][$k]['cycleFirstDay'] = true;
                //echo '<br /></><i> '.ucfirst(substr(get_vocab('rep_type_6'), 0, 1)).$jour_cycle.'</i>';
            } else {
                $tplArray['joursDuMois'][$k]['cycleFirstDay'] = false;
                if (strlen($jour_cycle) > 5) {
                    $jour_cycle = substr($jour_cycle, 0, 3).'..';
                }
                //echo '<br /></><i>'.$jour_cycle.'</i>';
            }
            $tplArray['joursDuMois'][$k]['cycleJour'] = $jour_cycle;
        } else {
            $tplArray['joursDuMois'][$k]['cycleJour'] = false;
        }
        //echo "</th>\n";
    } else {
        $tplArray['joursDuMois'][$k]['display'] = false;
    }
}
//echo '</tr>';
$tplArray['vocab']['reservation_impossible'] =  get_vocab('reservation_impossible');
$tplArray['vocab']['en_attente_moderation'] =  get_vocab('en_attente_moderation');
$tplArray['vocab']['reservation_a_confirmer_au_plus_tard_le'] =  get_vocab('reservation_a_confirmer_au_plus_tard_le');
$tplArray['vocab']['ressource_actuellement_empruntee'] =  get_vocab('ressource actuellement empruntee');


$li = 0;
$incrementRoomAccessible = 0;
$incrementDisplayDay = 0;
for ($ir = 0; ($row = grr_sql_row($res, $ir)); ++$ir) {
    /* un tour par ressources */
    $verif_acces_ressource = verif_acces_ressource(getUserName(), $row[2]);
    if ($verif_acces_ressource) {
        $tplArray['rooms'][$incrementRoomAccessible]['nom'] = strip_tags(htmlspecialchars($row[0]));

        $acces_fiche_reservation = verif_acces_fiche_reservation(getUserName(), $row[2]);
        //echo '<tr><th class="tableau_month_all2">'.htmlspecialchars($row[0])."</th>\n";
        $li++;
        //$t2 = mktime(0, 0, 0, $month, 1, $year);
        for ($k = 1; $k <= $days_in_month; $k++) {
            /* un tour par jour pour la room en cours $ir */
            $t2 = mktime(0, 0, 0, $month,$k,$year);
            $cday = date('j', $t2);
            $cweek = date('w', $t2);
            $name_day = ucfirst(utf8_strftime("%d", $t2));
            /*echo "<pre>";
            echo "<br>MONTH -> ".$month;
            echo "<br>T2  -> ".$t2;
            echo "<br>year -> ".$year;
            echo "<br>K -> ".$k;
            echo "<br>WEEK -> ".$cday;
            echo "<br>DAY -> ".$cweek;
            echo "</pre>";*/

            //$t2 += 86400;
            if ($display_day[$cweek] == 1) {
                $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['display'] = true;
                $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['nameDay'] = $name_day;
                $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['linkDay'] = 'day.php?year='.$year.'&month='.$month.'&day='.$cday.'&area='.$area;
                //echo '<td class="cell_month"> ';
                if (est_hors_reservation(mktime(0, 0, 0, $month, $cday, $year), $area)) {
                    $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['horsResa'] = true;
                    /*echo '<div class="empty_cell">';
                    echo '<img src="img_grr/stop.png" alt="'.get_vocab('reservation_impossible').'"  title="'.get_vocab('reservation_impossible').'" width="16" height="16" class="'.$class_image."\"  /></div>\n";*/
                } else {
                    $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['horsResa'] = false;
                    if (isset($d[$cday]['id'][0])) {
                        $n = count($d[$cday]['id']);
                        for ($i = 0; $i < $n; $i++) {
                            /* un tour de boucle par résa pour ce jour */
                            if ($i == 11 && $n > 12) {
                                //echo " ...\n";
                                break;
                            }
                            for ($i = 0; $i < $n; $i++) {
                                if ($d[$cday]['room'][$i] == $row[0]) {
                                    /*echo "\n<br /><table class='table-header'><tr>";
                                    tdcell($d[$cday]['color'][$i]);*/
                                    $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['color'] = getColor($d[$cday]['color'][$i]);
                                    $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['type'] = $d[$cday]['type'][$i];
                                    $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['data'] = $d[$cday]['data'][$i];
                                    /*if ($d[$cday]['res'][$i] != '-') {
                                        echo ' <img src="img_grr/buzy.png" alt="'.get_vocab('ressource actuellement empruntee').'" title="'.get_vocab('ressource actuellement empruntee')."\" width=\"20\" height=\"20\" class=\"image\" /> \n";
                                    }
                                    if ((isset($d[$cday]['option_reser'][$i])) && ($d[$cday]['option_reser'][$i] != -1)) {
                                        echo ' <img src="img_grr/small_flag.png" alt="'.get_vocab('reservation_a_confirmer_au_plus_tard_le').'" title="'.get_vocab('reservation_a_confirmer_au_plus_tard_le').' '.time_date_string_jma($d[$cday]['option_reser'][$i], $dformat)."\" width=\"20\" height=\"20\" class=\"image\" /> \n";
                                    }
                                    if ((isset($d[$cday]['moderation'][$i])) && ($d[$cday]['moderation'][$i] == 1)) {
                                        echo ' <img src="img_grr/flag_moderation.png" alt="'.get_vocab('en_attente_moderation').'" title="'.get_vocab('en_attente_moderation')."\" class=\"image\" /> \n";
                                    }*/
                                    if ($d[$cday]['res'][$i] != '-') {
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['empruntee'] = true;
                                        //echo '<img src="img_grr/buzy.png" alt="'.get_vocab('ressource actuellement empruntee').'" title="'.get_vocab('ressource actuellement empruntee').'" width="20" height="20" class="image" />'.PHP_EOL;
                                    } else {
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['empruntee'] = false;
                                    }
                                    if ((isset($d[$cday]['option_reser'][$i])) && ($d[$cday]['option_reser'][$i] != -1)) {
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['aConfirmerAuPlusTard'] =  utf8_strftime($dformat, $d[$cday]['option_reser'][$i]);
                                        //echo '<img src="img_grr/small_flag.png" alt="',get_vocab('reservation_a_confirmer_au_plus_tard_le'),'" title="',get_vocab('reservation_a_confirmer_au_plus_tard_le'),' ',time_date_string_jma($d[$cday]['option_reser'][$i], $dformat),'" width="20" height="20" class="image" />',PHP_EOL;
                                    } else {
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['aConfirmerAuPlusTard'] = false;
                                    }
                                    if ((isset($d[$cday]['moderation'][$i])) && ($d[$cday]['moderation'][$i] == 1)) {
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['moderation'] = true;
                                        //echo '<img src="img_grr/flag_moderation.png" alt="',get_vocab('en_attente_moderation'),'" title="',get_vocab('en_attente_moderation'),'" class="image" />',PHP_EOL;
                                    } else {
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['aConfirmerAuPlusTard'] = false;
                                    }

                                    /*echo '<span class="small_planning">';*/
                                    if ($acces_fiche_reservation) {
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['accessFicheResa'] = true;

                                        if (Settings::get('display_level_view_entry') == 0) {
                                            //$currentPage = 'week_all';
                                            //$id = $d[$cday]['id'][$i];
                                            //echo '<a title="'.htmlspecialchars($d[$cday]['who'][$i]).'" data-width="675" onclick="request('.$id.','.$cday.','.$cmonth.','.$cyear.',\''.$currentPage.'\',readData);" data-rel="popup_name" class="poplight" style = "border-bottom:1px solid #FFF">'.PHP_EOL;
                                                                                                //var_dump($d[$cday]['id'][$i]);
                                            $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['linkOnclick'] = 'request('.$d[$cday]['id'][$i].','.$cday.','.$month.','.$year.',\'month_all2\',readData);';
                                            $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['linkHref'] = false;
                                        } else {
                                            //echo '<a class="lienCellule" title="'.htmlspecialchars($d[$cday]['who'][$i]).'" href="view_entry.php?id='.$d[$cday]['id'][$i].'&amp;page=week_all&amp;day='.$cday.'&amp;month='.$cmonth.'&amp;year='.$cyear.'&amp;">'.PHP_EOL;
                                            $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['linkOnclick'] = false;
                                            $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['linkHref'] = 'view_entry.php?id='.$d[$cday]['id'][$i].'&page=week_all&day='.$cday.'&month='.$month.'&year='.$year;
                                        }

                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['linkTitle'] = strip_tags(htmlspecialchars($d[$cday]['who1'][$i]));
                                        /*if (Settings::get('display_level_view_entry') == 0) {
                                            $currentPage = 'month_all2';
                                            $id = $d[$cday]['id'][$i];
                                            echo '<a title="'.htmlspecialchars($d[$cday]['who1'][$i])."\" data-width=\"675\" onclick=\"request($id,$cday,$month,$year,'$currentPage',readData);\" data-rel=\"popup_name\" class=\"poplight\">".$d[$cday]['who1'][$i].'</a>';
                                        } else {
                                            echo '<a title="'.htmlspecialchars($d[$cday]['data'][$i]).'" href="view_entry.php?id='.$d[$cday]['id'][$i].'&amp;page=month">'
                                            .$d[$cday]['who1'][$i]{0}
                                            .'</a>';
                                        }*/
                                    } else {
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['accessFicheResa'] = false;
                                        $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['reservations'][$i]['who1'] = $d[$cday]['who1'][$i]{0};
                                    }
                                    //echo '</span></td></tr></table>';
                                }
                            }
                        }
                    }
                }
                //echo "</td>\n";
                $incrementDisplayDay++;
            } else {
                $tplArray['rooms'][$incrementRoomAccessible]['jours'][$incrementDisplayDay]['display'] = false;
            }
        }
        //echo '</tr>';
        $incrementRoomAccessible++;
    }
}
//echo '</table>';

//echo  '<div id="popup_name" class="popup_block" ></div>';
/*if ($_GET['pview'] != 1) {
    echo '<div id="toTop"> ^ Haut de la page';
}
bouton_retour_haut();*/
/*echo ' </div>';
echo ' </div>';
echo ' </div>';*/
//affiche_pop_up(get_vocab('message_records'), 'user');
/*include 'footer.php';*/
echo $twig->render('monthAll2.html.twig', $tplArray);
?>
