# Ajout en cours
## Ajout fonction clé donné / rendu
* Alteration de la table de reservation - OK
* Ajout de la fonction dans functions.inc.php + mbrs_sql.php- OK
* Ajout du choix de view_entry.php - Ok
* Ajout du choix dans Edit_entry.php - OK
* Ajout d'un indicateur visuel sur les calendriers (functions.inc.php ou day/week/week_all/month/month_all/month_all2.php) - OK
* Ajout de l'alteration des tables dans l'installeur/updateur - OK
* Modification du numéro de version - OK
## Ajout fonction courrier de validation
* Alteration de la table de reservation - OK
* Ajout de la fonction dans functions.inc.php + mbrs_sql.php - OK
* Ajout du choix de view_entry.php - OK
* Ajout du choix dans Edit_entry.php - OK
* Ajout d'un indicateur visuel sur les calendriers (functions.inc.php ou day/week/week_all/month/month_all/month_all2.php) - OK
* Ajout de l'alteration des tables dans l'installeur/updateur - OK
* Modification du numéro de version - OK


# Futur Ajout
* Mise en place de la generation de pdf avancé - Partiel
* Envois d'une lettre en pdf avec le mail de confirmation - 0%
* Sauvegarde de la lettre sur le serveur - 0%

# Refacto idées
* Gestion lib externes avec composer
* IMPORTANT sécuriser toutes les entrées utilisateurs avec strip_tags, possibilité actuellement d'XSS dans la bdd 
* refacto begin_page pour l'intégrer à print_header puisque les deux préparent les infos pour afficher le début de page
* dé dupliquer le code des fonctions make_site_*
* il faudrait voir pour au moins, dédupliquer le code de week et week_all, puis refaire les events en fonction
* idem pour mont et month_all

# Warnings
* Les vars sont passées en GET à edit_entry_handler, avec les plugins risque de dépasser la taille max du _GET, il faudrait passer en POST
* tester les events sans passer le tpl, et faire les get et set tpl, peut être event dispatcher à accès aux vars en cours directement ?