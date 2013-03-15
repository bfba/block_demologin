<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local language pack from http://extensions.moodle2.de
 *
 * @package    block
 * @subpackage demologin
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['configure_democourse_category'] = 'Kurskategorie für Demonstrationskurse';
$string['configure_democourse_category_title'] = 'Kurskategorie';
$string['configure_democourse_demouser_role_course'] = 'Die Rolle die Demousern im Kurskontext zugewiesen werden soll. <span style="font-weight:bold;color:red;">Achtung: Weisen Sie hier nur die Teilnehmerrolle oder eine andere Rolle mit entsprechend wenigen Rechten zu!</span>';
$string['configure_democourse_demouser_role_course_title'] = 'Kursrolle f&uuml;r Demouser';
$string['configure_democourse_description'] = 'Die Beschreibung wird in dem Block auf der Startseite über der Liste der Kurse angezeigt.';
$string['configure_democourse_description_title'] = 'Beschreibung:';
$string['configure_democourse_max_demo_users'] = 'Anzahl der Demonutzer. Der Zugang wird nacheinander an die \'fiktiven\' Demo-Nutzerzugänge vergeben. Beim nächsten Zugrif oder Zugriff durch eine andere Person wird ein weiterer Demozugang verwendet. Nicht mehr genutzte Demozugänge werden zurückgesetzt.  Die Zahl der benötigten Zugänge hängt von dem Umfang der Nutzung auf Ihrer Plattform ab. Der Wert sollte mindestens so hoch sein, dass kein noch aktiver Nutzergang zurückgesetzt wird.';
$string['configure_democourse_max_demo_users_title'] = 'Höchstzahl Demonutzer';
$string['configure_description'] = 'Es ist notwendig eine Kurskategorie anzulegen und als Kurskategorie für die Demokurse festzulegen. Alle darin abgelegten Kurse werden als Demokurse zur Verfügung gestellt.
Ohne sich einloggen zu müssen wird für diese Kurse ein temporärer Zugriff gewährt. Der Zugriff erfolgt mit allen Rechten eines Teilnehmers im Moodle-System. Der Zugriff endet durch einen Klick auf Logout, durch Zeitablauf der Session oder durch Schließen des Browsers.
<b>Wichtig:</b> Die Kurzbezeichnung des Kurses darf nur alphanumerische Zeichen und kein Leerzeichen enthalten.';
$string['error_cannot_login_demouser'] = 'Sorry. Es gibt mit dem Demo-Zugriff zur Zeit ein Problem. Bitte nehmen Sie mit dem Administrator Kontakt auf, falls das Problem bestehen bleibt.';
$string['error_enable_cookies'] = 'Sie müssen in Ihrem Browser Cookies zulassen.';
$string['error_no_category_defined'] = 'Es ist keine Kurskategorie für Demokurse festgelegt worden.';
$string['error_no_courses_in_category'] = 'Es gibt zur Zeit in der ausgewählten Kurskategorie keine Kurse.';
$string['error_no_demouser_role_defined'] = 'Die Höchstzahl der Demonutzer ist nicht festgelegt worden.';
$string['error_no_max_users_defined'] = 'Die Höchstzahl der Demonutzer ist nicht festgelegt worden.';
$string['error_user_still_active'] = 'Sorry. Zur Zeit sind keine Demonutzerzugänge mehr verfügbar. Bitte versuchen Sie es später nochmals.';
$string['pluginname'] = 'Demo-Login';
$string['title'] = 'Demo-Kurse';

$string['configure_democourse_demouser_role_system_title'] = 'Systemrolle f&uuml;r Demousers.';
$string['configure_democourse_demouser_role_system'] = 'Die Rolle die Demousern im Systemkontext zugewiesen werden soll. <span style="font-weight:bold;color:red;">Achtung: Weisen Sie hier nur die Gastrolle oder eine andere Rolle mit entsprechend wenigen Rechten zu!</span>';
$string['error_no_demouser_role_system_defined'] = 'Systemrolle f&uuml;r Demouser nicht definiert.';

$string['flooding_error'] = 'Sie haben die Seite zu häufig aufgerufen.';
$string['argument_error'] = 'Fehlender Kurs oder ungültiges Parameter Format.';
$string['course_error'] = 'Kurs nicht definiert oder nicht in erlaubter Kategorie.';

$string['demologin:addinstance'] = 'demologin Block hinzufügen';