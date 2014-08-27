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
 * Authentication Development Tools
 * Uses to developers and main admins.
 *
 * @package    auth_dev
 * @author     Carlos Escobedo <http://www.twitter.com/carlosagile>)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * Authentication Development Tools plugin.
 *
 * @package    auth
 * @subpackage dev
 * @author     Carlos Escobedo <http://www.twitter.com/carlosagile>)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class auth_plugin_dev extends auth_plugin_base {
    /**
    * The name of the component. Used by the configuration.
    */
    const COMPONENT_NAME = 'auth_dev';

    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'dev';
        $this->config = get_config(self::COMPONENT_NAME);
    }

    public function prelogout_hook() {
        global $CFG, $SESSION, $USER;

        if (\core\session\manager::is_loggedinas()) {
            $id = optional_param('id', 0, PARAM_INT);
            // IF ID==0 then logout request, notthing to do.
            if ($id) {
                $realuser = \core\session\manager::get_realuser();
                // Check is siteadmin. 
                // Only siteadmins can use this tool.
                if (is_siteadmin($realuser)) {
                    complete_user_login($realuser);
                    $SESSION->wantsurl = "$CFG->wwwroot/course/view.php?id=".$id;
                    redirect($SESSION->wantsurl);
                }
            }
        }
    }
}


