<?php
/**
 * MODx language File
 *
 * @author Grégory Pakosz (guardian)
 * @package MODx
 * @version 1.0
 * 
 * Filename:       /install/lang/french/french.inc.php
 * Language:       French
 * Encoding:       UTF-8
 */




$_lang['license'] = '<p class="title">MODx Contrat de Licence.</p>
	    <hr style="text-align:left;height:1px;width:90%" />
		<h4>Vous devez accepter les termes du contrat de licence avant de poursuivre l\'installation.</h4>
		<p>L\'utilisation de ce logiciel est soumise à la licence GPL. Pour vous aider à comprendre ce qu\'est
    la licence GPL et de quelle manière elle régit vos droits d\'utilisation de ce logiciel, nous vous en
    proposons le résumé suivant:</p>
		<h4>La Licence Publique Générale GNU est une licence du logiciel libre.</h4>
		<p>Comme toute licence du logiciel libre, elle vous accorde les quatre libertés suivantes:</p>
		<ul>
            <li>La liberté d\'utilisation du logiciel. </li>
            <li>La liberté d\'étudier le fonctionnement du logiciel et de l\'adapter à vos besoins. </li>
            <li>La liberté d\'en redistribuer des copies. </li>
            <li>La liberté d\'améliorer le fonctionnement du logiciel et de rendre publiques vos modifications pour que celles-ci
            profitent à toute la communauté. </li>
		</ul>
		<p>Vous pouvez exercer les libertés mentionnées dans le présent document à condition de respecter rigoureusement les principales
    conditions de cette licence:</p>
		<ul>
            <li>Vous devez apposer sur chaque copie, de manière ad hoc et parfaitement visible,
            l\'avis de droit d\'auteur adéquat et une exonération de garantie ; garder intacts
            tous les avis faisant référence à la présente Licence et à l\'absence de toute garantie ;
            et fournir à tout destinataire du Logiciel autre que vous-même un exemplaire de la
            Licence Générale Publique GNU en même temps que le Logiciel. Toute traduction de la Licence
            Publique Générale GNU doit être accompagnée de la version originale de la Licence
            Publique Générale GNU.</li>
            
            <li>Si vous modifiez votre copie ou des copies du logiciel, ou n\'importe quelle
            partie de celui-ci, vous avez le droit de redistribuer le travail dérivé à condition de le faire
            sous les conditions imposées par la Licence Générale Publique GNU. Toute traduction de la Licence
            Publique Générale GNU doit être accompagnée de la version originale de la Licence
            Publique Générale GNU. </li>

            <li>Si vous copiez ou distribuez le logiciel, vous devez l\'accompagner de l\'intégralité
            du code source correspondant, sous une forme lisible par un ordinateur, ou de l\'accompagner
            d\'une proposition écrite, valable pendant au moins trois ans, de fournir à tout tiers, une
            copie intégrale du code source correspondant sous une forme lisible par un ordinateur.</li>
            
            <li>Chacune de ces conditions peut être levée si vous obtenez l\'autorisation du
            titulaire des droits.</li>

            <li>Ce qui précède n\'affecte en rien vos droits en tant qu\'utilisateur (exceptions
            au droit d\'auteur : copies réservées à l\'usage privé du copiste, courtes citations, parodie...)</li>
        </ul>
		<p>Ceci est un récapitulatif des claues principales de la Licence Générale Public GNU. En acceptant le contrat
    de licence, vous vous engagez à respecter la licence GNU GPL dans son intégralité. Le résumé proposé ci-dessus
    est uniquement destiné à vous faciliter sa compréhension et complétude n\'est pas garantie. Il est vivement
    recommandé de lire la version intégrale de la <a href="http://www.gnu.org/copyleft/gpl.html" target=_blank>Licence Générale Publique GNU</a>
    avant de poursuivre l\'installation. Vous en trouverez également une copie dans le fichier de licence qui accompagne
    la distribution de ce Logiciel.</p>';
$_lang["encoding"] = 'utf-8';	//charset encoding for html header
$_lang["modx_install"] = 'MODx &raquo; Installation';
$_lang["loading"] = 'Chargement...';
$_lang["Begin"] = 'Démarrer';
$_lang["status_connecting"] = ' Connexion à l\'hôte: ';
$_lang["status_failed"] = 'echec!';
$_lang["status_passed"] = 'succès';
$_lang["status_checking_database"] = '...    Vérification de la base de données: ';
$_lang["status_failed_could_not_select_database"] = 'echec - impossible de sélectionner la base de données';
$_lang["status_failed_table_prefix_already_in_use"] = 'echec - prefixe de table déjà utilisé!';
$_lang["welcome_message_welcome"] = 'Bienvenue dans le programme d\'installation de MODx.';
$_lang["welcome_message_text"] = 'Ce programme vous guidera tout au long de la phase d\'installation.';
$_lang["welcome_message_select_begin_button"] = 'Appuyez sur le bouton `Démarrer` pour commencer l\'installation:';
$_lang["installation_mode"] = 'Type d\'installation';
$_lang["installation_new_installation"] = 'Nouvelle Installation';
$_lang["installation_install_new_copy"] = 'Installation d\'une nouvelle copie';
$_lang["installation_install_new_note"] = 'Attention, cette option est susceptible d\'écraser les données de la base.';
$_lang["installation_upgrade_existing"] = 'Mise A Jour d\'une installation existante';
$_lang["installation_upgrade_existing_note"] = 'Mise à jour des fichiers existants et de la base de données.';
$_lang["installation_upgrade_advanced"] = 'Mise A Jour Avancée<br /><small>(configuration de la base de donées)</small>';
$_lang["installation_upgrade_advanced_note"] = 'Destiné aux administrateurs avancés ou à la migration vers un serveur de base de données disposant d\'un encodage différent. <b>Vous devez disposer du nom complet de la base de données, de l\'identifiant utilisateur, du mot de passe et des details de connexion/collation.</b>';
$_lang["connection_screen_connection_information"] = 'Paramètres de Connexion';
$_lang["connection_screen_connection_and_login_information"] = 'Connection à la Base de Données et Identification';
$_lang["connection_screen_connection_note"] = 'Veuillez saisir le nom de la base de données créée pour MODx. Si la base est inexistante, le programme d\'installation tentera de la créer pour vous. Cette opération est susceptible d\'échouer en fonction de la configuration MySQL ou des droits d\'accès à la base de données pour votre domaine/installation.';
$_lang["connection_screen_database_name"] = 'Nom de la Base:';
$_lang["connection_screen_table_prefix"] = 'Préfixe de Table:';
$_lang["connection_screen_collation"] = 'Collation:';
$_lang["connection_screen_character_set"] = 'Jeu de caractères de la connexion:';
$_lang["connection_screen_database_info"] = 'Veuillez saisir l\'identifiant utilisateur de la base de données.';
$_lang["connection_screen_database_host"] = 'Serveur hébergeant la base:';
$_lang["connection_screen_database_login"] = 'Identifiant utilisateur de la base:';
$_lang["connection_screen_database_pass"] = 'Mot de passe:';
$_lang["connection_screen_test_connection"] = 'Test de la connexion';
$_lang["connection_screen_default_admin_user"] = 'Administrateur par defaut';
$_lang["connection_screen_default_admin_note"] = 'Vous allez maintenent saisir des informations du compte administrateur princpal. Vous pouvez donner ici votre nom et un mot de passe facile à retenir. Vous aurez besoin de ces informations pour vous connecter comme administrateur après l\'installation.';
$_lang["connection_screen_default_admin_login"] = 'Nom d\'utilisateur administrateur:';
$_lang["connection_screen_default_admin_email"] = 'Email de l\'administrateur:';
$_lang["connection_screen_default_admin_password"] = 'Mot de passe administrateur:';
$_lang["connection_screen_default_admin_password_confirm"] = 'Confirmation du mot de passe:';
$_lang["optional_items"] = 'Options d\'installation';
$_lang["optional_items_note"] = 'Selectionnez les options d\'installation et cliquez sur Installer:';
$_lang["sample_web_site"] = 'Exemple de Site Web';
$_lang["install_overwrite"] = 'Installation/Ecrasement';
$_lang["sample_web_site_note"] = 'Attention, cette opération va <b style=\"color:#CC0000\">écraser</b> les documents et ressources existants.';
$_lang["checkbox_select_options"] = 'Cochez pour sélectionner les options:';
$_lang["all"] = 'Tout';
$_lang["none"] = 'Aucun';
$_lang["toggle"] = 'Toggle';
$_lang["templates"] = 'Gabarits';
$_lang["install_update"] = 'Installation/Mise A Jour';
$_lang["chunks"] = 'Chunks';
$_lang["modules"] = 'Modules';
$_lang["plugins"] = 'Plugins';
$_lang["snippets"] = 'Snippets';
$_lang["preinstall_validation"] = 'Validation de la phase de Pre-Installation';
$_lang["summary_setup_check"] = 'Le programme d\'installation a effectué une série de vérifications afin de déterminer si tout est prêt pour démarrer l\'installation.';
$_lang["checking_php_version"] = "Vérification de la version PHP: ";
$_lang["failed"] = 'Failed!';
$_lang["ok"] = 'OK!';
$_lang["you_running_php"] = ' - Vous utilisez PHP ';
$_lang["modx_requires_php"] = ', alors que MODx nécessite PHP 4.1.0 ou ultérieur';
$_lang["php_security_notice"] = '<legend>Avertissement Sécurité</legend><p>Bien que MODx fonctionne avec votre version de PHP, nous n\'en recommandons pas l\'utilisation. Votre version de PHP comporte de nombreuses vulnérabilités de sécurité. Veuillez mettre à jour PHP vers une version 4.3.8 ou supérieure afin de corriger ces failles. Cette mise à jour est recommandée pour la sécurité de votre site internet.</p>';
$_lang["checking_sessions"] = 'Vérifications des paramètres de sessions: ';
$_lang["checking_if_cache_exist"] = 'Vérification de l\'existence du répertoire <span class=\"mono\">assets/cache</span>: ';
$_lang["checking_if_cache_writable"] = 'Vérification des droits en écriture du répertoire <span class=\"mono\">assets/cache</span>: ';
$_lang["checking_if_cache_file_writable"] = 'Vérification des droits en écriture du fichier <span class=\"mono\">assets/cache/siteCache.idx.php</span>: ';
$_lang["checking_if_cache_file2_writable"] = 'Vérification des droits en écriture du fichier<span class=\"mono\">assets/cache/sitePublishing.idx.php</span>: ';
$_lang["checking_if_images_exist"] = 'Vérification de l\'existence du répertoire <span class=\"mono\">assets/images</span>: ';
$_lang["checking_if_images_writable"] = 'Vérification des droits en écriture du répertoire <span class=\"mono\">assets/images</span>: ';
$_lang["checking_if_export_exists"] = 'Vérification de l\'existence du répertoire <span class=\"mono\">assets/export</span>: ';
$_lang["checking_if_export_writable"] = 'Vérification des droits en écriture du répertoire <span class=\"mono\">assets/export</span>: ';
$_lang["checking_if_config_exist_and_writable"] = 'Vérification de l\'existence et des droits en écriture du fichier <span class=\"mono\">manager/includes/config.inc.php</span>: ';
$_lang["config_permissions_note"] = 'Lors des installations Linux/Unix, veuillez créer un nouveau fichier nommé <span class=\"mono\">config.inc.php</span> dans le répertoire <span class=\"mono\">manager/includes/</span> avec les droits d\'accès 0666.';
$_lang["creating_database_connection"] = 'Création de la connexion à la base de données: ';
$_lang["database_connection_failed"] = 'Echec de connexion à la base de données!';
$_lang["database_connection_failed_note"] = 'Veuillez vérifier les paramètres de connexion à la base de données et réessayez.';
$_lang["database_use_failed"] = 'Impossible d\'accéder à la base de données!';
$_lang["database_use_failed_note"] = 'Veuillez verifier les droits d\'accès utilisateur à la base de données et réessayez.';
$_lang["checking_table_prefix"] = 'Vérification du préfixe de table `';
$_lang["table_prefix_already_inuse"] = ' - Le préfixe de table est déjà utilisé dans cette base de données!';
$_lang["table_prefix_already_inuse_note"] = 'Le programme d\'installation n\'a pas pu utiliser la base de données spécifiée parce qu\'elle contient déjà des tables comportant le préfixe que vous avez choisi. Veuillez sélectionner un autre préfixe de table et recommencer l\'installation.';
$_lang["table_prefix_not_exist"] = ' - Le prefixe de table n\'existe pas dans la base de données!';
$_lang["table_prefix_not_exist_note"] = 'Le programme d\'installation n\'a pas pu utiliser la base de données spécifiée parce qu\'elle ne contient pas de tables comportant le préfixe que vous avez choisi pour la mise à jour. Veuillez choisir un préfixe de table existant et recommencer l\'installation.';
$_lang["setup_cannot_continue"] = 'Impossible de poursuivre l\'installation';
$_lang["error"] = 'erreur';
$_lang["please_correct"] = '. Veuillez corriger l\'erreur';
$_lang["and_try_again"] = ', et reessayer. Si vous avez besoin d\'aide pour corriger le problème';
$_lang["visit_forum"] = ', visitez les <a href="http://www.modxcms.com/forums/" target="_blank">Forums Utilisateurs de MODx</a>.';
$_lang["testing_connection"] = 'Test de la connexion...';
$_lang["btnback_value"] = 'Précedent';
$_lang["btnnext_value"] = 'Suivant';
$_lang["retry"] = 'Réessayer';
$_lang["alert_enter_database_name"] = 'Vous devez saisir le nom de la base de données!';
$_lang["alert_table_prefixes"] = 'Les préfixes de table doivent commencer par une lettre!';
$_lang["alert_enter_host"] = 'Vous devez saisir une adresse You need to enter a value for database host!';
$_lang["alert_enter_login"] = 'Vous devez saisir un nom d\'utilisateur pour la base de données!';
$_lang["alert_enter_adminlogin"] = 'Vous devez saisir un nom d\'utilisateur pour le compte administrateur du système!';
$_lang["alert_enter_adminpassword"] = 'Vous devez saisir un mot de passe pour le compte administrateur du système!';
$_lang["alert_enter_adminconfirm"] = 'Le mot de passe administrateur et la confirmation du mot de passe ne correspondent pas!';
$_lang["iagree_box"] = 'J\'accepte le contrat de license.';
$_lang["btnclose_value"] = 'Fermer';
$_lang["running_setup_script"] = 'Execution du script d\'installation... Veuillez patienter';
$_lang["modx_footer1"] = '&copy; 2005-2007 the <a href="http://www.modxcms.com/" target="_blank" style="color: green; text-decoration:underline">MODx</a> Content Management Framework (CMF) project. All rights reserved. MODx is licensed under the GNU GPL.';
$_lang["modx_footer2"] = 'MODx est un logiciel libre.  Nous vous encourageons à être créatifs et à utiliser MODx comme bon il vous semble. Votre seule obligation est de redistribuer gratuitement! votre version modifiée de MODx.';
$_lang["setup_database"] = 'Le programme d\'installation va tenter de configurer la base de données:<br />';
$_lang["setup_database_create_connection"] = 'Création de la connexion à la base de données: ';
$_lang["setup_database_create_connection_failed"] = 'Echec de connexion à la base de données!';
$_lang["setup_database_create_connection_failed_note"] = 'Veuillez vérifier les paramètres de connexion à la base de données et réessayer.';
$_lang["setup_database_selection"] = 'Sélection de la base de données `';
$_lang["setup_database_selection_failed"] = 'La sélection de la base de données a échoué...';
$_lang["setup_database_selection_failed_note"] = 'La base de données n\'existe pas. Le programme d\'installation va essayer de la créer.';
$_lang["setup_database_creation"] = 'Création de la base de données `';
$_lang["setup_database_creation_failed"] = 'La création de la base de données a échoué!';
$_lang["setup_database_creation_failed_note"] = ' - Le programme d\'installation n\'a pas pu créer la base de données!';
$_lang["setup_database_creation_failed_note2"] = 'Le programme d\'installation n\'a pas pu créer la base de données, et aucune base de données existante avec le même nom n\'a été trouvée. Vraisemblablement, les réglages de sécurité de votre hébergeur n\'autorisent pas les scripts externes à créer une base de données. Veuillez suivre la procédure mise à disposition par votre hébergeur afin de créer la base, puis recommencez l\'installation.';
$_lang["setup_database_creating_tables"] = 'Création des tables de la base: ';
$_lang["database_alerts"] = 'Alertes de la base!';
$_lang["setup_couldnt_install"] = 'Le programme d\'installation n\'a pas pu créer/modifier certaines tables dans la base de données spécifiée.';
$_lang["installation_error_occured"] = 'Les erreurs suivantes se sont produites au cours de l\'installation';
$_lang["during_execution_of_sql"] = ' lors de l\'execiton de la requête SQL ';
$_lang["some_tables_not_updated"] = 'Certaines tables n\'ont pas été mises à jour. Ceci peut être du à des modifications précédentes.';
$_lang["installing_demo_site"] = 'Installation du site de démonstration: ';
$_lang["writing_config_file"] = 'Ecriture des fichiers de configuration: ';
$_lang["cant_write_config_file"] = 'MODx n\'a pas pu écrire le fichier de configuration. Veuillez copier/coller ceci dans le fichier ';
$_lang["cant_write_config_file_note"] = 'Une fois l\'opération effectuée, vous pouvez vous connecter à l\'interface administrateur de MODx en utilisant l\'adresse  VotreSite.com/manager/.';
$_lang["unable_install_template"] = 'Impossible d\'installer le gabarit.  Fichier';
$_lang["unable_install_chunk"] = 'Impossible d\'installer le chunk.  Fichier';
$_lang["unable_install_module"] = 'Impossible d\'installer le module.  Fichier';
$_lang["unable_install_plugin"] = 'Impossible d\'installer le plugin.  Fichier';
$_lang["unable_install_snippet"] = 'Impossible d\'installer le snippet.  File';
$_lang["not_found"] = 'non trouvé';
$_lang["upgraded"] = 'Mis à jour';
$_lang["installed"] = 'Installé';
$_lang["running_database_updates"] = 'Mise à jour de la base de données: ';
$_lang["installation_successful"] = 'Installation réalisée avec succès!';
$_lang["to_log_into_content_manager"] = 'Pour vous connecter au gestionnaire de contenu (manager/index.php) cliquez sur le bouton `Fermer`.';
$_lang["install"] = 'Install';
$_lang["remove_install_folder_auto"] = 'Effacer automatiquement le repertoire &quot;<b>install</b>&quot; de mon site <br />&nbsp;(Cette opération nécessite des droits d\'accès en effacement sur le répertoire &quot;<b>install</b>&quot;).';
$_lang["remove_install_folder_manual"] = 'Veuillez effacer le répertoire &quot;<b>install</b>&quot; avant de vous connecter au Gestionnaire de Contenu.';
$_lang["install_results"] = 'Etat de l\'installation';
$_lang["installation_note"] = '<strong>Information:</strong> Après vous être connecté au manager, vous devez éditer et sauvegarder les paramètres de Configuration Système avant de visiter le site en sélectionnant <strong>Administration</strong> -> Configuration Système dans le Gestionnaire MODx.';
$_lang["upgrade_note"] = '<strong>Information:</strong> Avant de visiter le site, il vous est conseillé de vous connecter en tant qu\'administrateur au Gestionnaire et de vérifier les paramètres de Configuration Système.';
?>
