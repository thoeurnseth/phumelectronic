<?php
/*
Plugin Name: Traveling Abroad Importer
Plugin URI: https://bizsolution.com.kh
Description: This plugin allows user to import, custom_post_type, custom taxonomy by uploading CSV with an instance.
Version: 1.0
Author: Biz Afril
Author URI: https://bizsolution.com.kh
*/

class CSVImporterPlugin {
    var $defaults = array(
        'csv_post_title'      => null,
        'csv_post_post'       => null,
        'csv_post_type'       => 'traveling-abroad',
        'csv_post_excerpt'    => null,
        'csv_post_date'       => null,
        'csv_post_tags'       => null,
        'csv_post_categories' => null,
        'csv_post_author'     => null,
        'csv_post_slug'       => null,
        'csv_post_parent'     => 0,
    );

    var $log = array();

    /**
     * Determine value of option $name from database, $default value or $params,
     * save it to the db if needed and return it.
     *
     * @param string $name
     * @param mixed  $default
     * @param array  $params
     * @return string
     */
    function process_option($name, $default, $params) {
        if (array_key_exists($name, $params)) {
            $value = stripslashes($params[$name]);
        } elseif (array_key_exists('_'.$name, $params)) {
            // unchecked checkbox value
            $value = stripslashes($params['_'.$name]);
        } else {
            $value = null;
        }
        $stored_value = get_option($name);
        if ($value == null) {
            if ($stored_value === false) {
                if (is_callable($default) &&
                    method_exists($default[0], $default[1])) {
                    $value = call_user_func($default);
                } else {
                    $value = $default;
                }
                add_option($name, $value);
            } else {
                $value = $stored_value;
            }
        } else {
            if ($stored_value === false) {
                add_option($name, $value);
            } elseif ($stored_value != $value) {
                update_option($name, $value);
            }
        }
        return $value;
    }

    /**
     * Plugin's interface
     *
     * @return void
     */
    function form() {
        $opt_draft = $this->process_option('csv_importer_import_as_draft',
            'publish', $_POST);
        $opt_cat = $this->process_option('csv_importer_cat', 0, $_POST);

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $this->post(compact('opt_draft', 'opt_cat'));
        }

        // form HTML {{{
        ?>

        <div class="wrap">
            <h2>Traveling Abroad CSV Importer</h2>
            <form class="add:the-list: validate" method="post" enctype="multipart/form-data">

                <p>
                    <h3>
                        Please keep in mind that:
                    </h3>
                    <ol>
                        <li>This plugin is used to insert new records only. Please update or delete your existing data manually.</li>
                        <li>"csv_taxonomy_slug_plan" must be a slug of the plan. eg: postpaid, prepaid</li>
                        <li>"csv_taxonomy_slug_country" must be a slug of the country. eg: cambodia, ស្រីលង្កា, u-s-virgin-islands</li>
                        <li>The records from the CSV file won't be inserted, if the "csv_taxonomy_slug_plan" or "csv_taxonomy_slug_country" do not exist on our database.</li>
                    </ol>
                </p>

                <!-- Parent category -->
                <h3>Click these links to download CSV sample files.
                    <a href="<?php echo plugin_dir_url(__DIR__) . 'biz-importer/samples/sample-prepaid.csv'; ?>" download>Prepaid Sample</a> and
                    <a href="<?php echo plugin_dir_url(__DIR__) . 'biz-importer/samples/sample-postpaid.csv'; ?>" download>Postpaid Sample</a>
                </h3>

                <p>
                    <input name="csv_import" id="csv_import" type="file" value="" aria-required="true" />
                </p>

                <p class="submit"><input type="submit" class="button" name="submit" value="Import" /></p>

            </form>
        </div><!-- end wrap -->

        <?php
        // end form HTML }}}

    }

    function print_messages() {
        if (!empty($this->log)) {

                // messages HTML {{{
        ?>

        <div class="wrap">
            <?php if (!empty($this->log['error'])): ?>

            <div class="error">

                <?php foreach ($this->log['error'] as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>

            </div>

            <?php endif; ?>

            <?php if (!empty($this->log['notice'])): ?>

            <div class="updated fade">

                <?php foreach ($this->log['notice'] as $notice): ?>
                    <p><?php echo $notice; ?></p>
                <?php endforeach; ?>

            </div>

            <?php endif; ?>
        </div><!-- end wrap -->

        <?php
                // end messages HTML }}}

                    $this->log = array();
                }
    }

    /**
     * Handle POST submission
     *
     * @param array $options
     * @return void
     */
    function post($options) {
        if (empty($_FILES['csv_import']['tmp_name'])) {
            $this->log['error'][] = 'No file uploaded, aborting.';
            $this->print_messages();
            return;
        }

        if (!current_user_can('publish_pages') || !current_user_can('publish_posts')) {
            $this->log['error'][] = 'You don\'t have the permissions to publish posts and pages. Please contact the blog\'s administrator.';
            $this->print_messages();
            return;
        }

        require_once 'File_CSV_DataSource/DataSource.php';

        $time_start = microtime(true);
        $csv = new File_CSV_DataSource;
        $file = $_FILES['csv_import']['tmp_name'];
        $this->stripBOM($file);

        if (!$csv->load($file)) {
            $this->log['error'][] = 'Failed to load file, aborting.';
            $this->print_messages();
            return;
        }

        // pad shorter rows with empty values
        $csv->symmetrize();

        // WordPress sets the correct timezone for date functions somewhere
        // in the bowels of wp_insert_post(). We need strtotime() to return
        // correct time before the call to wp_insert_post().
        $tz = get_option('timezone_string');
        if ($tz && function_exists('date_default_timezone_set')) {
            date_default_timezone_set($tz);
        }

        $skipped = 0;
        $imported = 0;
        $line = 0;
        foreach ($csv->connect() as $csv_data) {
            $line++;
            if(
                // Check if Plan Taxonomy is valid (can only be postpaid, prepaid)
                strtolower($csv_data['csv_taxonomy']) == 'province' ||
                strtolower($csv_data['csv_taxonomy']) == 'district' ||
                strtolower($csv_data['csv_taxonomy']) == 'commune'
            )
            {
                if(
                    // Check if Country Taxonomy is valid (can only be postpaid, prepaid)
                    !term_exists($csv_data['csv_term'], $csv_data['csv_taxonomy'])
                )
                {
                    if ($post_id = $this->create_term($csv_data, $options) ) {
                        if($post_id == false)
                            $skipped++;
                        else
                            $imported++;
                        // $this->create_custom_fields($post_id, $csv_data);
                        // $this->update_acf_fields($post_id, $csv_data);
                    }
                    else
                    {
                        $skipped++;
                    }
                }
                else
                {
                    $skipped++;
                    $this->log['error'][] = "The term of \"" . $csv_data['csv_term'] . "\" already exists. Please check row #" . $line;
                }
            }
            else
            {
                $skipped++;
                $this->log['error'][] = "The taxonomy of \"". $csv_data['csv_taxonomy'] ."\" not found Please check row #" . $line;
            }
        }

        if (file_exists($file)) {
            @unlink($file);
        }

        $exec_time = microtime(true) - $time_start;

        if ($skipped) {
            $this->log['notice'][] = "<b>Skipped {$skipped} posts (most likely due to empty title, plan and country).</b>";
        }
        $this->log['notice'][] = sprintf("<b>Imported {$imported} posts in %.2f seconds.</b>", $exec_time);
        $this->print_messages();
    }

    function create_term($data, $options) {
        $opt_draft = isset($options['opt_draft']) ? $options['opt_draft'] : null;
        $opt_cat = isset($options['opt_cat']) ? $options['opt_cat'] : null;

        $data = array_merge($this->defaults, $data);
        $taxonomy = $data['csv_taxonomy'] ? $data['csv_taxonomy'] : 'category';
        $valid_type = (function_exists('taxonomy_exists') &&
            taxonomy_exists($taxonomy)) || in_array($taxonomy, array('location'));

        if (!$valid_type) {
            $this->log['error']["type-{$taxonomy}"] = sprintf(
                'Unknown taxonomy "%s".', $taxonomy);
        }

        $parent_term    = get_term_by('slug', sanitize_title($data['csv_parent']), 'location');
        $term_name      = wp_strip_all_tags($data['csv_term_name']);

        
        $args = array(
                'slug'          => sanitize_title($term_name),
                'parent'        => $parent_term->term_id
            );

        if( !empty($parent_term)){
            $id = wp_insert_term($term_name, 'location', $args);
            $id = $id['term_id'];
            // $term_meta = get_term_meta($id, 'name_kh');
            // if (!$term_meta) {
            //     $term_meta = add_term_meta($id, 'name_kh', $data['csv_term_name_kh']);
            // }
            // else{
            //     update_term_meta( $id, 'name_kh', $data['csv_term_name_kh'] );
            // }
            add_term_meta( $id, "name_kh" ,         $data['csv_term_name_kh'] );
            add_term_meta( $id, "code" ,            $data['csv_term_code'] );
            add_term_meta( $id, "taxonomy_type" ,   sanitize_title($data['csv_taxonomy']) );
        }
        else{
            wp_reset_postdata();
            return false;
        }


        if ('page' !== $taxonomy && !$id) {
            // cleanup new categories on failure
            foreach ($cats['cleanup'] as $c) {
                wp_delete_term($c, 'category');
            }
        }
        
        wp_reset_postdata();
        return $id;
    }

    function update_acf_fields($term_id, $csv_data){

        // $data = array_merge($this->defaults, $csv_data);
        // $taxonomy = $data['csv_taxonomy'];
        // switch($taxonomy)
        // {
        //     case "province":
        //         // update_field('code',                 $data['csv_code'], $term_id);
        //         // update_field('name_kh',              $data['csv_name_kh'], $term_id);
        //         // update_field('country',              $data['csv_country'], $term_id);
        //     break;
        //     case "district":
        //         $term = get_term_by('slug', sanitize_title($data['csv_parent']), 'province');
        //         update_term_meta($term_id, 'province', 12);
        //     break;
        //     case "commune":
        //         update_field('district',                $data['csv_parent'], $term_id);
        //     break;
        // }
    }

    /**
     * Return an array of category ids for a post.
     *
     * @param string  $data csv_post_categories cell contents
     * @param integer $common_parent_id common parent id for all categories
     * @return array category ids
     */
    function create_or_get_categories($data, $common_parent_id) {
        $ids = array(
            'post' => array(),
            'cleanup' => array(),
        );
        $items = array_map('trim', explode(',', $data['csv_post_categories']));
        foreach ($items as $item) {
            if (is_numeric($item)) {
                if (get_category($item) !== null) {
                    $ids['post'][] = $item;
                } else {
                    $this->log['error'][] = "Category ID {$item} does not exist, skipping.";
                }
            } else {
                $parent_id = $common_parent_id;
                // item can be a single category name or a string such as
                // Parent > Child > Grandchild
                $categories = array_map('trim', explode('>', $item));
                if (count($categories) > 1 && is_numeric($categories[0])) {
                    $parent_id = $categories[0];
                    if (get_category($parent_id) !== null) {
                        // valid id, everything's ok
                        $categories = array_slice($categories, 1);
                    } else {
                        $this->log['error'][] = "Category ID {$parent_id} does not exist, skipping.";
                        continue;
                    }
                }
                foreach ($categories as $category) {
                    if ($category) {
                        $term = $this->term_exists($category, 'category', $parent_id);
                        if ($term) {
                            $term_id = $term['term_id'];
                        } else {
                            // $term_id = wp_insert_category(array(
                            //     'cat_name' => $category,
                            //     'category_parent' => $parent_id,
                            // ));
                            // $ids['cleanup'][] = $term_id;
                        }
                        $parent_id = $term_id;
                    }
                }
                $ids['post'][] = $term_id;
            }
        }
        return $ids;
    }

    /**
     * Parse taxonomy data from the file
     *
     * array(
     *      // hierarchical taxonomy name => ID array
     *      'my taxonomy 1' => array(1, 2, 3, ...),
     *      // non-hierarchical taxonomy name => term names string
     *      'my taxonomy 2' => array('term1', 'term2', ...),
     * )
     *
     * @param array $data
     * @return array
     */
    function get_taxonomies($data) {
        $taxonomies = array();
        foreach ($data as $k => $v) {
            if (preg_match('/^csv_taxonomy_slug_(.*)$/', $k, $matches)) {
                $t_name = $matches[1];
                if ($this->taxonomy_exists($t_name)) {
                    $taxonomies[$t_name] = $this->create_terms($t_name,
                        $data[$k]);
                } else {
                    $this->log['error'][] = "Unknown taxonomy $t_name";
                }
            }
        }
        return $taxonomies;
    }

    /**
     * Return an array of term IDs for hierarchical taxonomies or the original
     * string from CSV for non-hierarchical taxonomies. The original string
     * should have the same format as csv_post_tags.
     *
     * @param string $taxonomy
     * @param string $field
     * @return mixed
     */
    function create_terms($taxonomy, $field) {
        if (is_taxonomy_hierarchical($taxonomy)) {
            $term_ids = array();
            foreach ($this->_parse_tax($field) as $row) {
                list($parent, $child) = $row;
                $parent_ok = true;
                if ($parent) {
                    $parent_info = $this->term_exists($parent, $taxonomy);
                    if (!$parent_info) {
                        // create parent
                        // $parent_info = wp_insert_term($parent, $taxonomy);
                    }
                    if (!is_wp_error($parent_info)) {
                        $parent_id = $parent_info['term_id'];
                    } else {
                        // could not find or create parent
                        $parent_ok = false;
                    }
                } else {
                    $parent_id = 0;
                }

                if ($parent_ok) {
                    $child_info = $this->term_exists($child, $taxonomy, $parent_id);
                    if (!$child_info) {
                        // create child
                        // $child_info = wp_insert_term($child, $taxonomy,
                        //     array('parent' => $parent_id));
                    }
                    if (!is_wp_error($child_info)) {
                        $term_ids[] = $child_info['term_id'];
                    }
                }
            }
            return $term_ids;
        } else {
            return $field;
        }
    }

    /**
     * Compatibility wrapper for WordPress term lookup.
     */
    function term_exists($term, $taxonomy = '', $parent = 0) {
        if (function_exists('term_exists')) { // 3.0 or later
            return term_exists($term, $taxonomy, $parent);
        } else {
            return is_term($term, $taxonomy, $parent);
        }
    }

    /**
     * Compatibility wrapper for WordPress taxonomy lookup.
     */
    function taxonomy_exists($taxonomy) {
        if (function_exists('taxonomy_exists')) { // 3.0 or later
            return taxonomy_exists($taxonomy);
        } else {
            return is_taxonomy($taxonomy);
        }
    }

    /**
     * Hierarchical taxonomy fields are tiny CSV files in their own right.
     *
     * @param string $field
     * @return array
     */
    function _parse_tax($field) {
        $data = array();
        if (function_exists('str_getcsv')) { // PHP 5 >= 5.3.0
            $lines = $this->split_lines($field);

            foreach ($lines as $line) {
                $data[] = str_getcsv($line, ',', '"');
            }
        } else {
            // Use temp files for older PHP versions. Reusing the tmp file for
            // the duration of the script might be faster, but not necessarily
            // significant.
            $handle = tmpfile();
            fwrite($handle, $field);
            fseek($handle, 0);

            while (($r = fgetcsv($handle, 999999, ',', '"')) !== false) {
                $data[] = $r;
            }
            fclose($handle);
        }
        return $data;
    }

    /**
     * Try to split lines of text correctly regardless of the platform the text
     * is coming from.
     */
    function split_lines($text) {
        $lines = preg_split("/(\r\n|\n|\r)/", $text);
        return $lines;
    }

    function create_custom_fields($post_id, $data) {
        foreach ($data as $k => $v) {
            // anything that doesn't start with csv_ is a custom field
            if (!preg_match('/^csv_/', $k) && $v != '') {
                add_post_meta($post_id, $k, $v);
            }
        }
    }

    function get_auth_id($author) {
        if (is_numeric($author)) {
            return $author;
        }

        // get_userdatabylogin is deprecated as of 3.3.0
        if (function_exists('get_user_by')) {
            $author_data = get_user_by('login', $author);
        } else {
            $author_data = get_userdatabylogin($author);
        }

        return ($author_data) ? $author_data->ID : 0;
    }

    /**
     * Convert date in CSV file to 1999-12-31 23:52:00 format
     *
     * @param string $data
     * @return string
     */
    function parse_date($data) {
        $timestamp = strtotime($data);
        if (false === $timestamp) {
            return '';
        } else {
            return date('Y-m-d H:i:s', $timestamp);
        }
    }

    /**
     * Delete BOM from UTF-8 file.
     *
     * @param string $fname
     * @return void
     */
    function stripBOM($fname) {
        $res = fopen($fname, 'rb');
        if (false !== $res) {
            $bytes = fread($res, 3);
            if ($bytes == pack('CCC', 0xef, 0xbb, 0xbf)) {
                $this->log['notice'][] = 'Getting rid of byte order mark...';
                fclose($res);

                $contents = file_get_contents($fname);
                if (false === $contents) {
                    trigger_error('Failed to get file contents.', E_USER_WARNING);
                }
                $contents = substr($contents, 3);
                $success = file_put_contents($fname, $contents);
                if (false === $success) {
                    trigger_error('Failed to put file contents.', E_USER_WARNING);
                }
            } else {
                fclose($res);
            }
        } else {
            $this->log['error'][] = 'Failed to open file, aborting.';
        }
    }
}


/*
add_action('admin_menu', 'csv_admin_menu', 10);
function csv_admin_menu(){
    //require_once ABSPATH . '/wp-admin/admin.php';
    $plugin = new CSVImporterPlugin;
    add_submenu_page( 
        'edit.php?post_type=offers',
        'Traveling Abroad Importer',
        'Traveling Abroad Importer',
        'edit_pages',
        'traveling-abroad-importer',
        array($plugin, 'form')
    );
}
*/

function csv_admin_menu() {
    require_once ABSPATH . '/wp-admin/admin.php';
    $plugin = new CSVImporterPlugin;
    add_management_page('edit.php', 'Traveling Abroad Importer', 'manage_categories', __FILE__,
        array($plugin, 'form'));
}

add_action('admin_menu', 'csv_admin_menu');


?>
