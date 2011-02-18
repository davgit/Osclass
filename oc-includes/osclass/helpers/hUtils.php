<?php
    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    //get params
    function osc_get_param($key) {
        return Params::getParam($key) ;
    }

    //generic function for view layer
    function osc_field($item, $field, $locale) {
        if(!is_null($item)) {
            if($locale == "") {
                if(isset($item[$field])) {
                    return $item[$field] ;
                }
            } else {
                if(isset($item["locale"]) && isset($item["locale"][$locale]) && isset($item["locale"][$locale][$field])) {
                    return $item["locale"][$locale][$field] ;
                }
            }
        }
        return '' ;
    }
    

    function osc_show_widgets($location) {
        $widgets = Widget::newInstance()->findByLocation($location);
        foreach ($widgets as $w)
            echo $w['s_content'] ;
    }

    /**
     * @return true if the item has uploaded a thumbnail.
     */
    //osc_itemHasThumbnail
    function osc_item_has_thumbnail($item) {
        $conn = getConnection() ;
        $resource = $conn->osc_dbFetchResult('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']) ;
        return!is_null($resource) ;
    }

    /**
     * Formats the date using the appropiate format.
     */
    //osc_formatDate
    function osc_format_date($date) {
        //$date = strtotime($item['dt_pub_date']) ;
        return date(osc_date_format(), strtotime($date)) ;
    }

    /**
     * Prints the user's account menu
     *
     * @param array with options of the form array('name' => 'display name', 'url' => 'url of link')
     *
     * @return void
     */
    function osc_private_user_menu($options = null)
    {
        if($options == null) {
            $options = array();
            $options[] = array('name' => __('Dashboard'), 'url' => osc_user_dashboard_url()) ;
            $options[] = array('name' => __('Manage your items'), 'url' => osc_user_list_items_url()) ;
            $options[] = array('name' => __('Manage your alerts'), 'url' => osc_user_alerts_url()) ;
            $options[] = array('name' => __('My account'), 'url' => osc_user_profile_url()) ;
            $options[] = array('name' => __('Logout'), 'url' => osc_user_logout_url()) ;
        }

        echo '<script type="text/javascript">' ;
            echo '$(".user_menu > :first-child").addClass("first") ;' ;
            echo '$(".user_menu > :last-child").addClass("last") ;' ;
        echo '</script>' ;
        echo '<ul class="user_menu">' ;

            $var_l = count($options) ;
            for($var_o = 0 ; $var_o < $var_l ; $var_o++) {
                echo '<li><a href="' . $options[$var_o]['url'] . '" >' . $options[$var_o]['name'] . '</a></li>' ;
            }

            osc_run_hook('user_menu') ;

        echo '</ul>' ;
    }

?>
