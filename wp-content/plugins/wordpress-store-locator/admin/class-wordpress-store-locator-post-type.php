<?php
/**
 * Custom Post Type for Stores and Taxonomies.
 */
class WordPress_Store_Locator_Post_Type
{
    private $plugin_name;
    private $version;
    /**
     * Constructor.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    https://welaunch.io/plugins
     *
     * @param string $plugin_name
     * @param string $version
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->prefix = 'wordpress_store_locator_';

        add_filter('manage_stores_posts_columns', array($this, 'columns_head'));
        add_action('manage_stores_posts_custom_column', array($this, 'columns_content'), 10, 1);
    }

    /**
     * Init.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    https://welaunch.io/plugins
     *
     * @return bool
     */
    public function init()
    {
        $this->register_store_locator_post_type();
        $this->register_store_locator_taxonomy();
        $this->add_custom_meta_fields();
    }

    /**
     * Register Store Post Type.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    https://welaunch.io/plugins
     *
     * @return bool
     */
    public function register_store_locator_post_type()
    {
        $singular = __('Store', 'wordpress-store-locator');
        $plural = __('Stores', 'wordpress-store-locator');

        $labels = array(
            'name' => __('Store Locator', 'wordpress-store-locator'),
            'all_items' => sprintf(__('All %s', 'wordpress-store-locator'), $plural),
            'singular_name' => $singular,
            'add_new' => sprintf(__('New %s', 'wordpress-store-locator'), $singular),
            'add_new_item' => sprintf(__('Add New %s', 'wordpress-store-locator'), $singular),
            'edit_item' => sprintf(__('Edit %s', 'wordpress-store-locator'), $singular),
            'new_item' => sprintf(__('New %s', 'wordpress-store-locator'), $singular),
            'view_item' => sprintf(__('View %s', 'wordpress-store-locator'), $plural),
            'search_items' => sprintf(__('Search %s', 'wordpress-store-locator'), $plural),
            'not_found' => sprintf(__('No %s found', 'wordpress-store-locator'), $plural),
            'not_found_in_trash' => sprintf(__('No %s found in trash', 'wordpress-store-locator'), $plural),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'exclude_from_search' => false,
            'show_ui' => true,
            'menu_position' => 57,
            'rewrite' => array(
                'slug' => 'store',
                'with_front' => FALSE
            ),
            'query_var' => 'stores',
            'supports' => array('title', 'editor', 'author', 'revisions', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-location-alt',
            // 'taxonomies' => array('post_tag'),
        );

        register_post_type('stores', $args);

    }

    /**
     * Register Store Categories and Store Filter Taxonomies.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    https://welaunch.io/plugins
     *
     * @return bool
     */
    public function register_store_locator_taxonomy()
    {
    	// Store Category
        $singular = __('Store Category', 'wordpress-store-locator');
        $plural = __('Store Categories', 'wordpress-store-locator');

        $labels = array(
            'name' => sprintf(__('%s', 'wordpress-store-locator'), $plural),
            'singular_name' => sprintf(__('%s', 'wordpress-store-locator'), $singular),
            'search_items' => sprintf(__('Search %s', 'wordpress-store-locator'), $plural),
            'all_items' => sprintf(__('All %s', 'wordpress-store-locator'), $plural),
            'parent_item' => sprintf(__('Parent %s', 'wordpress-store-locator'), $singular),
            'parent_item_colon' => sprintf(__('Parent %s:', 'wordpress-store-locator'), $singular),
            'edit_item' => sprintf(__('Edit %s', 'wordpress-store-locator'), $singular),
            'update_item' => sprintf(__('Update %s', 'wordpress-store-locator'), $singular),
            'add_new_item' => sprintf(__('Add New %s', 'wordpress-store-locator'), $singular),
            'new_item_name' => sprintf(__('New %s Name', 'wordpress-store-locator'), $singular),
            'menu_name' => sprintf(__('%s', 'wordpress-store-locator'), $plural),
        );

        $args = array(
                'labels' => $labels,
                'public' => true,
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var' => true,
                'rewrite' => array(
                    'slug' => 'store-categories',
                    'with_front' => FALSE
                ),
        );

        register_taxonomy('store_category', 'stores', $args);

        // Store Filter
        $singular = __('Store Filter', 'wordpress-store-locator');
        $plural = __('Store Filter', 'wordpress-store-locator');
        $labels = array(
            'name' => sprintf(__('%s', 'wordpress-store-locator'), $plural),
            'singular_name' => sprintf(__('%s', 'wordpress-store-locator'), $singular),
            'search_items' => sprintf(__('Search %s', 'wordpress-store-locator'), $plural),
            'all_items' => sprintf(__('All %s', 'wordpress-store-locator'), $plural),
            'parent_item' => sprintf(__('Parent %s', 'wordpress-store-locator'), $singular),
            'parent_item_colon' => sprintf(__('Parent %s:', 'wordpress-store-locator'), $singular),
            'edit_item' => sprintf(__('Edit %s', 'wordpress-store-locator'), $singular),
            'update_item' => sprintf(__('Update %s', 'wordpress-store-locator'), $singular),
            'add_new_item' => sprintf(__('Add New %s', 'wordpress-store-locator'), $singular),
            'new_item_name' => sprintf(__('New %s Name', 'wordpress-store-locator'), $singular),
            'menu_name' => sprintf(__('%s', 'wordpress-store-locator'), $plural),
        );

        $args = array(
                'labels' => $labels,
                'public' => false,
                'hierarchical' => true,
                'show_ui' => true,
                'show_admin_column' => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var' => true,
                'rewrite' => array('slug' => 'store-filter'),
        );

        register_taxonomy('store_filter', 'stores', $args);
    }

    /**
     * Add Custom Meta Fields to Store Categories and Filters.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    https://welaunch.io/plugins
     *
     * @return bool
     */
    public function add_custom_meta_fields()
    {
        $custom_taxonomy_meta_config = array(
            'id' => 'stores_meta_box',
            'title' => 'Stores Meta Box',
            'pages' => array('store_category', 'store_filter'),
            'context' => 'side',
            'fields' => array(),
            'local_images' => false,
            'use_with_theme' => false,
        );

        $custom_taxonomy_meta_fields = new Tax_Meta_Class($custom_taxonomy_meta_config);
        // $custom_taxonomy_meta_fields->addImage($prefix.'image', array('name' => __('Map Icon ', 'wordpress-store-locator')));
        // No ID!
        // $custom_taxonomy_meta_fields->addTaxonomy($prefix.'product_category',array('taxonomy' => 'product_cat'),array('name'=> __('Link to Product Category ','wordpress-store-locator')));

        $options = array('' => 'Select Category');
        $categories = get_terms('product_cat');
        if(is_array($categories)) {
            foreach ($categories as $category) {
                $options[$category->term_id] = $category->name;
            }
            $custom_taxonomy_meta_fields->addSelect($this->prefix . 'product_category', $options, array('name' => __('Link to Product Category ', 'wordpress-store-locator')));
        }

        $options = array('' => 'Select Industry');
        $categories = get_terms('industries');
        if(is_array($categories)) {
            foreach ($categories as $category) {
                $options[$category->term_id] = $category->name;
            }
            $custom_taxonomy_meta_fields->addSelect($this->prefix . 'industry', $options, array('name' => __('Link to Product Industry ', 'wordpress-store-locator')));
        }

        $custom_taxonomy_meta_fields->addImage($this->prefix . 'icon', array('name'=> __('Custom Icon ','wordpress-store-locator')));
        $custom_taxonomy_meta_fields->Finish();


        $custom_taxonomy_meta_config = array(
            'id' => 'stores_meta_box2',
            'title' => 'Stores Meta Box',
            'pages' => array('store_filter'),
            'context' => 'side',
            'fields' => array(),
            'local_images' => false,
            'use_with_theme' => false,
        );

        $term = new stdClass();

        if(isset($_GET['tag_ID']) && !empty($_GET['tag_ID'])) {
            $term = get_term($_GET['tag_ID']);
        }

        if(isset($_POST['tag_ID']) && !empty($_POST['tag_ID'])) {
            $term = get_term($_POST['tag_ID']);   
        }

        if(isset($term->parent) && $term->parent == 0) {

            $custom_taxonomy_meta_fields = new Tax_Meta_Class($custom_taxonomy_meta_config);

            $options = array(
                'checkbox' => 'Checkboxes',
                'select' => 'Select Field',
                // 'radio' => 'Radio Buttons',
            );
            $custom_taxonomy_meta_fields->addSelect($this->prefix . 'input_type', $options, array('name' => __('Input Type ', 'wordpress-store-locator')));

            $options = array('' => 'Select Store Category');
            $categories = get_terms('store_category');
            if(is_array($categories)) {
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }
                $custom_taxonomy_meta_fields->addSelect($this->prefix . 'store_category', $options, array('name' => __('Show for Store Category', 'wordpress-store-locator')));
            }

            $custom_taxonomy_meta_fields->Finish();
        }
    }

    /**
     * Columns Head.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    https://welaunch.io/plugins
     *
     * @param string $columns Columnd
     *
     * @return string
     */
    public function columns_head($columns)
    {
        $output = array();
        foreach ($columns as $column => $name) {
            $output[$column] = $name;

            if ($column === 'title') {
                $output['address'] = __('Address', 'wordpress-store-locator');
                $output['contact'] = __('Contact', 'wordpress-store-locator');
                $output['coordinates'] = __('Coordinates', 'wordpress-store-locator');
            }
        }

        return $output;
    }

    /**
     * Columns Content.
     *
     * @author Daniel Barenkamp
     *
     * @version 1.0.0
     *
     * @since   1.0.0
     * @link    https://welaunch.io/plugins
     *
     * @param string $column_name Column Name
     *
     * @return string
     */
    public function columns_content($column_name)
    {
        global $post;

        if ($column_name == 'address') {
            $address = array();
            $address['address1'] = get_post_meta($post->ID, 'wordpress_store_locator_address1', true);
            $address['address2'] = get_post_meta($post->ID, 'wordpress_store_locator_address2', true);
            $address['city'] = get_post_meta($post->ID, 'wordpress_store_locator_zip', true).', '.get_post_meta($post->ID, 'wordpress_store_locator_city', true);
            $address['country'] = get_post_meta($post->ID, 'wordpress_store_locator_region', true).', '.get_post_meta($post->ID, 'wordpress_store_locator_country', true);

            echo implode('<br/>', array_filter($address));
        }

        if ($column_name == 'contact') {
            $contact = array();
            $contact['telephone'] = __('Tel.:', 'wordpress-store-locator').' '.get_post_meta($post->ID, 'wordpress_store_locator_telephone', true);
            $contact['mobile'] = __('Mobile:', 'wordpress-store-locator').' '.get_post_meta($post->ID, 'wordpress_store_locator_mobile', true);
            $contact['email'] = __('Email:', 'wordpress-store-locator').' <a href="mailto'.get_post_meta($post->ID, 'wordpress_store_locator_email', true).'"> '.get_post_meta($post->ID, 'wordpress_store_locator_email', true).'</a>';
            $contact['website'] = __('Website:', 'wordpress-store-locator') .' <a href="'.get_post_meta($post->ID, 'wordpress_store_locator_website', true).'"> '.get_post_meta($post->ID, 'wordpress_store_locator_website', true).'</a>';

            echo implode('<br/>', array_filter($contact));
        }

        if ($column_name == 'coordinates') {
            $coordinates = array();
            $coordinates['lat'] = __('Lat:', 'wordpress-store-locator') . ' ' . get_post_meta($post->ID, 'wordpress_store_locator_lat', true);
            $coordinates['lng'] = __('Lng:', 'wordpress-store-locator') . ' ' . get_post_meta($post->ID, 'wordpress_store_locator_lng', true);

            echo implode('<br/>', array_filter($coordinates));
        }
    }

/**
     * Add custom ticket metaboxes
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                       $post_type [description]
     * @param   [type]                       $post      [description]
     */
    public function add_custom_metaboxes($post_type, $post)
    {
        add_meta_box('wordpress-store-locator-address', 'Address', array($this, 'address'), 'stores', 'normal', 'high');
        add_meta_box('wordpress-store-locator-contact', 'Contact Information', array($this, 'contact'), 'stores', 'normal', 'high');
        add_meta_box('wordpress-store-locator-additional', 'Additional', array($this, 'additional'), 'stores', 'normal', 'high');
        add_meta_box('wordpress-store-locator-opening', 'Opening Hours', array($this, 'opening'), 'stores', 'normal', 'high');

        add_meta_box('wordpress-store-locator-opening2', 'Opening Hours 2', array($this, 'opening2'), 'stores', 'normal', 'high');
    }

    /**
     * Display Metabox Address
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function address()
    {
        global $post, $wordpress_store_locator_options;

        wp_nonce_field(basename(__FILE__), 'wordpress_store_locator_meta_nonce');

        if($this->is_new_store()) {
            $address1 = $wordpress_store_locator_options['defaultAddress1'];
            $address2 = $wordpress_store_locator_options['defaultAddress2'];
            $zip = $wordpress_store_locator_options['defaultZIP'];
            $city = $wordpress_store_locator_options['defaultCity'];
            $region = $wordpress_store_locator_options['defaultRegion'];
            $lat = '';
            $lng = '';

            $country = $wordpress_store_locator_options['defaultCountry'];

        } else {
            $address1 = get_post_meta($post->ID, $this->prefix . 'address1', true);
            $address2 = get_post_meta($post->ID, $this->prefix . 'address2', true);
            $zip = get_post_meta($post->ID, $this->prefix . 'zip', true);
            $city = get_post_meta($post->ID, $this->prefix . 'city', true);
            $region = get_post_meta($post->ID, $this->prefix . 'region', true);
            $country = get_post_meta($post->ID, $this->prefix . 'country', true);
            $lat = get_post_meta($post->ID, $this->prefix . 'lat', true);
            $lng = get_post_meta($post->ID, $this->prefix . 'lng', true);
        }

        echo '<div class="wordpress-store-locator-container">';
            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'address1">' . __( 'Address Line 1', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'address1" value="' . $address1 . '" type="text">';
                echo '</div>';
            
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'address2">' . __( 'Address Line 2', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'address2" value="' . $address2 . '" type="text">';
                echo '</div>';
            echo '</div>';

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'zip">' . __( 'ZIP', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'zip" value="' . $zip . '" type="text">';
                echo '</div>';
            
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'city">' . __( 'City', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'city" value="' . $city . '" type="text">';
                echo '</div>';
            echo '</div>';

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'region">' . __( 'State / Province / Region', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'region" value="' . $region . '" type="text">';
                echo '</div>';
            
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'country">' . __( 'Country', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<select name="' . $this->prefix . 'country" class="wordpress-store-locator-input-field">';
                    $countries = $this->get_countries();
                    foreach ($countries as $code => $countryName) {
                        $selected = "";
                        if($country == $code) {
                            $selected = 'selected="selected"';
                        }
                        echo '<option value="' . $code . '" ' . $selected . '>' . $countryName . '</option>';
                    }
                    echo '</select>';
                echo '</div>';
            echo '</div>';

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-12">';
                    echo '<a href="#" id="wordpress-store-locator-get-position" class="btn button">Get Position</a>';
                    echo '<div class="wordpress-store-locator-map" data-lat="' . $lat . '" data-lng="' . $lng . '">';
                        echo '<div id="wordpress-store-locator-map-container"></div>';
                    echo '</div>';
                echo '</div>';    
            echo '</div>';
            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'lat">' . __( 'Latitude', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input id="wordpress-store-locator-lat" class="wordpress-store-locator-input-field" name="' . $this->prefix . 'lat" value="' . $lat . '" type="text">';
                echo '</div>';
            
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'lng">' . __( 'Longitude', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input id="wordpress-store-locator-lng" class="wordpress-store-locator-input-field" name="' . $this->prefix . 'lng" value="' . $lng . '" type="text">';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }

    /**
     * Display Metabox Address
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function contact()
    {
        global $post, $wordpress_store_locator_options;

        wp_nonce_field(basename(__FILE__), 'wordpress_store_locator_meta_nonce');

        if($this->is_new_store()) {
            $telephone = $wordpress_store_locator_options['defaultTelephone'];
            $mobile = $wordpress_store_locator_options['defaultMobile'];
            $fax = $wordpress_store_locator_options['defaultFax'];
            $email = $wordpress_store_locator_options['defaultEmail'];
            $website = $wordpress_store_locator_options['defaultWebsite'];
        } else {
            $telephone = get_post_meta($post->ID, $this->prefix . 'telephone', true);
            $mobile = get_post_meta($post->ID, $this->prefix . 'mobile', true);
            $fax = get_post_meta($post->ID, $this->prefix . 'fax', true);
            $email = get_post_meta($post->ID, $this->prefix . 'email', true);
            $website = get_post_meta($post->ID, $this->prefix . 'website', true);
        }

        echo '<div class="wordpress-store-locator-container">';
            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'telephone">' . __( 'Telephone', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'telephone" value="' . $telephone . '" type="text">';
                echo '</div>';
            
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'mobile">' . __( 'Mobile', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'mobile" value="' . $mobile . '" type="text">';
                echo '</div>';
            echo '</div>';

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'fax">' . __( 'Fax', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'fax" value="' . $fax . '" type="text">';
                echo '</div>';
            
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'email">' . __( 'Email', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'email" value="' . $email . '" type="text">';
                echo '</div>';
            echo '</div>';

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'website">' . __( 'Website', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'website" value="' . $website . '" type="text">';
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }

    /**
     * Display Metabox Address
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function additional()
    {
        global $post, $wordpress_store_locator_options;

        if($this->is_new_store()) {
            $ranking = $wordpress_store_locator_options['defaultRanking'];
        } else {
            $ranking = get_post_meta($post->ID, $this->prefix . 'ranking', true);
        }

        $premium = get_post_meta($post->ID, $this->prefix . 'premium', true) == "1" ? 'checked="checked"' : '';
        $icon = get_post_meta($post->ID, $this->prefix . 'icon', true);
        $customerId = get_post_meta($post->ID, $this->prefix . 'customerId', true);

        echo '<div class="wordpress-store-locator-container">';

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-12">';
                    echo '<label for="' . $this->prefix . 'premium">' . __( 'Premium Store', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'premium" value="1" ' . $premium . ' type="checkbox">';
                echo '</div>';
            echo '</div>';

            echo '<div class="wordpress-store-locator-row">';

                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'ranking">' . __( 'Ranking', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'ranking" value="' . $ranking . '" type="number">';
                echo '</div>';

                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . 'customerId">' . __( 'Customer Id', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'customerId" value="' . $customerId . '" type="text">';
                echo '</div>';
            echo '</div>';

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-12">';
                    echo '<label for="' . $this->prefix . 'icon">' . __( 'Custom Icon (URL)', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . 'icon" value="' . $icon . '" type="url">';
                echo '</div>';
            echo '</div>';

            $customFields = $wordpress_store_locator_options['showCustomFields'];
            if(!empty($customFields)) {

                echo '<div class="wordpress-store-locator-row">';

                foreach ($customFields as $customFieldKey => $customFieldName) {

                    $customFieldKey = $this->prefix . $customFieldKey;
                    $customFieldValue = get_post_meta($post->ID, $customFieldKey, true);

                    echo '<div class="wordpress-store-locator-col-sm-6">';
                        echo '<label for="' . $customFieldKey . '">' . $customFieldName . '</label><br/>';
                        echo '<input class="wordpress-store-locator-input-field" name="' . $customFieldKey . '" value="' . $customFieldValue . '" type="text">';
                    echo '</div>';

                }

                echo '</div>';
            }


        echo '</div>';
    }

    /**
     * Display Metabox Address
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function opening()
    {
        global $post, $wordpress_store_locator_options;

        $weekdays = array(
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        );

        echo '<div class="wordpress-store-locator-container">';
        $openingHours = array();
        foreach ($weekdays as $weekday) {
            $open = "";
            $close = "";

            if($this->is_new_store() && ($weekday != "Saturday" && $weekday != "Sunday")) {
                $open = $wordpress_store_locator_options['defaultOpen'];
                $close = $wordpress_store_locator_options['defaultClose'];
            } else {
                $open = get_post_meta($post->ID, $this->prefix . $weekday . '_open', true);
                $close = get_post_meta($post->ID, $this->prefix . $weekday . '_close', true);
            }

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . $weekday . '_open">' . __( $weekday . ' (open)', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . $weekday . '_open" value="' . $open .'" type="text">';
                echo '</div>';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . $weekday . '_close">' . __( $weekday . ' (close)', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . $weekday . '_close" value="' . $close .'" type="text">';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * Display Metabox Address
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function opening2()
    {
        global $post, $wordpress_store_locator_options;

        $weekdays = array(
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        );

        echo '<div class="wordpress-store-locator-container">';
        $openingHours = array();
        foreach ($weekdays as $weekday) {
            $open = "";
            $close = "";

            if($this->is_new_store() && ($weekday != "Saturday" && $weekday != "Sunday")) {
                $open = $wordpress_store_locator_options['defaultOpen'];
                $close = $wordpress_store_locator_options['defaultClose'];
            } else {
                $open = get_post_meta($post->ID, $this->prefix . $weekday . '_open2', true);
                $close = get_post_meta($post->ID, $this->prefix . $weekday . '_close2', true);
            }

            echo '<div class="wordpress-store-locator-row">';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . $weekday . '_open2">' . __( $weekday . ' (open)', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . $weekday . '_open2" value="' . $open .'" type="text">';
                echo '</div>';
                echo '<div class="wordpress-store-locator-col-sm-6">';
                    echo '<label for="' . $this->prefix . $weekday . '_close2">' . __( $weekday . ' (close)', 'wordpress-store-locator' ) . '</label><br/>';
                    echo '<input class="wordpress-store-locator-input-field" name="' . $this->prefix . $weekday . '_close2" value="' . $close .'" type="text">';
                echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * Save Custom Metaboxes
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                       $post_id [description]
     * @param   [type]                       $post    [description]
     * @return  [type]                                [description]
     */
    public function save_custom_metaboxes($post_id, $post)
    {
        global $wordpress_store_locator_options;

        if($post->post_type !== "stores") {
            return false;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post->ID)) {
            return $post->ID;
        }

        if ($post->post_type == 'revision') {
            return false;
        }

        if (!isset($_POST['wordpress_store_locator_meta_nonce']) || !wp_verify_nonce($_POST['wordpress_store_locator_meta_nonce'], basename(__FILE__))) {
            return false;
        }

        $possible_inputs = array(
            'wordpress_store_locator_address1',
            'wordpress_store_locator_address2',
            'wordpress_store_locator_zip',
            'wordpress_store_locator_city',
            'wordpress_store_locator_region',
            'wordpress_store_locator_country',
            'wordpress_store_locator_lat',
            'wordpress_store_locator_lng',
            'wordpress_store_locator_meta_nonce',
            'wordpress_store_locator_telephone',
            'wordpress_store_locator_mobile',
            'wordpress_store_locator_fax',
            'wordpress_store_locator_email',
            'wordpress_store_locator_website',
            'wordpress_store_locator_premium',
            'wordpress_store_locator_ranking',
            'wordpress_store_locator_icon',
            'wordpress_store_locator_customerId',

            // Opening Hours 1
            'wordpress_store_locator_Monday_open',
            'wordpress_store_locator_Monday_close',
            'wordpress_store_locator_Tuesday_open',
            'wordpress_store_locator_Tuesday_close',
            'wordpress_store_locator_Wednesday_open',
            'wordpress_store_locator_Wednesday_close',
            'wordpress_store_locator_Thursday_open',
            'wordpress_store_locator_Thursday_close',
            'wordpress_store_locator_Friday_open',
            'wordpress_store_locator_Friday_close',
            'wordpress_store_locator_Saturday_open',
            'wordpress_store_locator_Saturday_close',
            'wordpress_store_locator_Sunday_open',
            'wordpress_store_locator_Sunday_close',

            // Opening Hours 2
            'wordpress_store_locator_Monday_open2',
            'wordpress_store_locator_Monday_close2',
            'wordpress_store_locator_Tuesday_open2',
            'wordpress_store_locator_Tuesday_close2',
            'wordpress_store_locator_Wednesday_open2',
            'wordpress_store_locator_Wednesday_close2',
            'wordpress_store_locator_Thursday_open2',
            'wordpress_store_locator_Thursday_close2',
            'wordpress_store_locator_Friday_open2',
            'wordpress_store_locator_Friday_close2',
            'wordpress_store_locator_Saturday_open2',
            'wordpress_store_locator_Saturday_close2',
            'wordpress_store_locator_Sunday_open2',
            'wordpress_store_locator_Sunday_close2',
        );

        $customFields = $wordpress_store_locator_options['showCustomFields'];
        if(!empty($customFields)) {
            foreach ($customFields as $customFieldKey => $customFieldName) {
                $possible_inputs[] = $this->prefix . $customFieldKey;
            }
        }

        // Add values of $ticket_meta as custom fields
        foreach ($possible_inputs as $possible_input) {
            $val = isset($_POST[$possible_input]) ? $_POST[$possible_input] : '';

            if(empty($val) && !in_array($possible_input, array('wordpress_store_locator_premium', 'wordpress_store_locator_ranking'))) {
                delete_post_meta($post->ID, $possible_input);    
                continue;
            }
            update_post_meta($post->ID, $possible_input, $val);
        }
    }

    private function is_new_store()
    {
        global $pagenow;

        if (!is_admin()) return false;

        return in_array( $pagenow, array( 'post-new.php' ) );
    }

    private function get_countries()
    {
        $countries = array( 
            "AF" => __("Afghanistan", 'wordpress-store-locator'),"AL" => __("Albania", 'wordpress-store-locator'),"DZ" => __("Algeria", 'wordpress-store-locator'),"AS" => __("American Samoa", 'wordpress-store-locator'),"AD" => __("Andorra", 'wordpress-store-locator'),"AO" => __("Angola", 'wordpress-store-locator'),"AI" => __("Anguilla", 'wordpress-store-locator'),"AQ" => __("Antarctica", 'wordpress-store-locator'),"AG" => __("Antigua and Barbuda", 'wordpress-store-locator'),"AR" => __("Argentina", 'wordpress-store-locator'),"AM" => __("Armenia", 'wordpress-store-locator'),"AW" => __("Aruba", 'wordpress-store-locator'),"AU" => __("Australia", 'wordpress-store-locator'),"AT" => __("Austria", 'wordpress-store-locator'),"AZ" => __("Azerbaijan", 'wordpress-store-locator'),"BS" => __("Bahamas", 'wordpress-store-locator'),"BH" => __("Bahrain", 'wordpress-store-locator'),"BD" => __("Bangladesh", 'wordpress-store-locator'),"BB" => __("Barbados", 'wordpress-store-locator'),"BY" => __("Belarus", 'wordpress-store-locator'),"BE" => __("Belgium", 'wordpress-store-locator'),"BZ" => __("Belize", 'wordpress-store-locator'),"BJ" => __("Benin", 'wordpress-store-locator'),"BM" => __("Bermuda", 'wordpress-store-locator'),"BT" => __("Bhutan", 'wordpress-store-locator'),"BO" => __("Bolivia", 'wordpress-store-locator'),"BA" => __("Bosnia and Herzegovina", 'wordpress-store-locator'),"BW" => __("Botswana", 'wordpress-store-locator'),"BV" => __("Bouvet Island", 'wordpress-store-locator'),"BR" => __("Brazil", 'wordpress-store-locator'),"BQ" => __("British Antarctic Territory", 'wordpress-store-locator'),"IO" => __("British Indian Ocean Territory", 'wordpress-store-locator'),"VG" => __("British Virgin Islands", 'wordpress-store-locator'),"BN" => __("Brunei", 'wordpress-store-locator'),"BG" => __("Bulgaria", 'wordpress-store-locator'),"BF" => __("Burkina Faso", 'wordpress-store-locator'),"BI" => __("Burundi", 'wordpress-store-locator'),"KH" => __("Cambodia", 'wordpress-store-locator'),"CM" => __("Cameroon", 'wordpress-store-locator'),"CA" => __("Canada", 'wordpress-store-locator'),"CT" => __("Canton and Enderbury Islands", 'wordpress-store-locator'),"CV" => __("Cape Verde", 'wordpress-store-locator'),"KY" => __("Cayman Islands", 'wordpress-store-locator'),"CF" => __("Central African Republic", 'wordpress-store-locator'),"TD" => __("Chad", 'wordpress-store-locator'),"CL" => __("Chile", 'wordpress-store-locator'),"CN" => __("China", 'wordpress-store-locator'),"CX" => __("Christmas Island", 'wordpress-store-locator'),"CC" => __("Cocos [Keeling] Islands", 'wordpress-store-locator'),"CO" => __("Colombia", 'wordpress-store-locator'),"KM" => __("Comoros", 'wordpress-store-locator'),"CG" => __("Congo - Brazzaville", 'wordpress-store-locator'),"CD" => __("Congo - Kinshasa", 'wordpress-store-locator'),"CK" => __("Cook Islands", 'wordpress-store-locator'),"CR" => __("Costa Rica", 'wordpress-store-locator'),"HR" => __("Croatia", 'wordpress-store-locator'),"CU" => __("Cuba", 'wordpress-store-locator'),"CY" => __("Cyprus", 'wordpress-store-locator'),"CZ" => __("Czech Republic", 'wordpress-store-locator'),"CI" => __("Côte d’Ivoire", 'wordpress-store-locator'),"DK" => __("Denmark", 'wordpress-store-locator'),"DJ" => __("Djibouti", 'wordpress-store-locator'),"DM" => __("Dominica", 'wordpress-store-locator'),"DO" => __("Dominican Republic", 'wordpress-store-locator'),"NQ" => __("Dronning Maud Land", 'wordpress-store-locator'),"DD" => __("East Germany", 'wordpress-store-locator'),"EC" => __("Ecuador", 'wordpress-store-locator'),"EG" => __("Egypt", 'wordpress-store-locator'),"SV" => __("El Salvador", 'wordpress-store-locator'),"GQ" => __("Equatorial Guinea", 'wordpress-store-locator'),"ER" => __("Eritrea", 'wordpress-store-locator'),"EE" => __("Estonia", 'wordpress-store-locator'),"ET" => __("Ethiopia", 'wordpress-store-locator'),"FK" => __("Falkland Islands", 'wordpress-store-locator'),"FO" => __("Faroe Islands", 'wordpress-store-locator'),"FJ" => __("Fiji", 'wordpress-store-locator'),"FI" => __("Finland", 'wordpress-store-locator'),"FR" => __("France", 'wordpress-store-locator'),"GF" => __("French Guiana", 'wordpress-store-locator'),"PF" => __("French Polynesia", 'wordpress-store-locator'),"TF" => __("French Southern Territories", 'wordpress-store-locator'),"FQ" => __("French Southern and Antarctic Territories", 'wordpress-store-locator'),"GA" => __("Gabon", 'wordpress-store-locator'),"GM" => __("Gambia", 'wordpress-store-locator'),"GE" => __("Georgia", 'wordpress-store-locator'),"DE" => __("Germany", 'wordpress-store-locator'),"GH" => __("Ghana", 'wordpress-store-locator'),"GI" => __("Gibraltar", 'wordpress-store-locator'),"GR" => __("Greece", 'wordpress-store-locator'),"GL" => __("Greenland", 'wordpress-store-locator'),"GD" => __("Grenada", 'wordpress-store-locator'),"GP" => __("Guadeloupe", 'wordpress-store-locator'),"GU" => __("Guam", 'wordpress-store-locator'),"GT" => __("Guatemala", 'wordpress-store-locator'),"GG" => __("Guernsey", 'wordpress-store-locator'),"GN" => __("Guinea", 'wordpress-store-locator'),"GW" => __("Guinea-Bissau", 'wordpress-store-locator'),"GY" => __("Guyana", 'wordpress-store-locator'),"HT" => __("Haiti", 'wordpress-store-locator'),"HM" => __("Heard Island and McDonald Islands", 'wordpress-store-locator'),"HN" => __("Honduras", 'wordpress-store-locator'),"HK" => __("Hong Kong SAR China", 'wordpress-store-locator'),"HU" => __("Hungary", 'wordpress-store-locator'),"IS" => __("Iceland", 'wordpress-store-locator'),"IN" => __("India", 'wordpress-store-locator'),"ID" => __("Indonesia", 'wordpress-store-locator'),"IR" => __("Iran", 'wordpress-store-locator'),"IQ" => __("Iraq", 'wordpress-store-locator'),"IE" => __("Ireland", 'wordpress-store-locator'),"IM" => __("Isle of Man", 'wordpress-store-locator'),"IL" => __("Israel", 'wordpress-store-locator'),"IT" => __("Italy", 'wordpress-store-locator'),"JM" => __("Jamaica", 'wordpress-store-locator'),"JP" => __("Japan", 'wordpress-store-locator'),"JE" => __("Jersey", 'wordpress-store-locator'),"JT" => __("Johnston Island", 'wordpress-store-locator'),"JO" => __("Jordan", 'wordpress-store-locator'),"KZ" => __("Kazakhstan", 'wordpress-store-locator'),"KE" => __("Kenya", 'wordpress-store-locator'),"KI" => __("Kiribati", 'wordpress-store-locator'),"KW" => __("Kuwait", 'wordpress-store-locator'),"KG" => __("Kyrgyzstan", 'wordpress-store-locator'),"LA" => __("Laos", 'wordpress-store-locator'),"LV" => __("Latvia", 'wordpress-store-locator'),"LB" => __("Lebanon", 'wordpress-store-locator'),"LS" => __("Lesotho", 'wordpress-store-locator'),"LR" => __("Liberia", 'wordpress-store-locator'),"LY" => __("Libya", 'wordpress-store-locator'),"LI" => __("Liechtenstein", 'wordpress-store-locator'),"LT" => __("Lithuania", 'wordpress-store-locator'),"LU" => __("Luxembourg", 'wordpress-store-locator'),"MO" => __("Macau SAR China", 'wordpress-store-locator'),"MK" => __("Macedonia", 'wordpress-store-locator'),"MG" => __("Madagascar", 'wordpress-store-locator'),"MW" => __("Malawi", 'wordpress-store-locator'),"MY" => __("Malaysia", 'wordpress-store-locator'),"MV" => __("Maldives", 'wordpress-store-locator'),"ML" => __("Mali", 'wordpress-store-locator'),"MT" => __("Malta", 'wordpress-store-locator'),"MH" => __("Marshall Islands", 'wordpress-store-locator'),"MQ" => __("Martinique", 'wordpress-store-locator'),"MR" => __("Mauritania", 'wordpress-store-locator'),"MU" => __("Mauritius", 'wordpress-store-locator'),"YT" => __("Mayotte", 'wordpress-store-locator'),"FX" => __("Metropolitan France", 'wordpress-store-locator'),"MX" => __("Mexico", 'wordpress-store-locator'),"FM" => __("Micronesia", 'wordpress-store-locator'),"MI" => __("Midway Islands", 'wordpress-store-locator'),"MD" => __("Moldova", 'wordpress-store-locator'),"MC" => __("Monaco", 'wordpress-store-locator'),"MN" => __("Mongolia", 'wordpress-store-locator'),"ME" => __("Montenegro", 'wordpress-store-locator'),"MS" => __("Montserrat", 'wordpress-store-locator'),"MA" => __("Morocco", 'wordpress-store-locator'),"MZ" => __("Mozambique", 'wordpress-store-locator'),"MM" => __("Myanmar [Burma]", 'wordpress-store-locator'),"NA" => __("Namibia", 'wordpress-store-locator'),"NR" => __("Nauru", 'wordpress-store-locator'),"NP" => __("Nepal", 'wordpress-store-locator'),"NL" => __("Netherlands", 'wordpress-store-locator'),"AN" => __("Netherlands Antilles", 'wordpress-store-locator'),"NT" => __("Neutral Zone", 'wordpress-store-locator'),"NC" => __("New Caledonia", 'wordpress-store-locator'),"NZ" => __("New Zealand", 'wordpress-store-locator'),"NI" => __("Nicaragua", 'wordpress-store-locator'),"NE" => __("Niger", 'wordpress-store-locator'),"NG" => __("Nigeria", 'wordpress-store-locator'),"NU" => __("Niue", 'wordpress-store-locator'),"NF" => __("Norfolk Island", 'wordpress-store-locator'),"KP" => __("North Korea", 'wordpress-store-locator'),"VD" => __("North Vietnam", 'wordpress-store-locator'),"MP" => __("Northern Mariana Islands", 'wordpress-store-locator'),"NO" => __("Norway", 'wordpress-store-locator'),"OM" => __("Oman", 'wordpress-store-locator'),"PC" => __("Pacific Islands Trust Territory", 'wordpress-store-locator'),"PK" => __("Pakistan", 'wordpress-store-locator'),"PW" => __("Palau", 'wordpress-store-locator'),"PS" => __("Palestinian Territories", 'wordpress-store-locator'),"PA" => __("Panama", 'wordpress-store-locator'),"PZ" => __("Panama Canal Zone", 'wordpress-store-locator'),"PG" => __("Papua New Guinea", 'wordpress-store-locator'),"PY" => __("Paraguay", 'wordpress-store-locator'),"YD" => __("People's Democratic Republic of Yemen", 'wordpress-store-locator'),"PE" => __("Peru", 'wordpress-store-locator'),"PH" => __("Philippines", 'wordpress-store-locator'),"PN" => __("Pitcairn Islands", 'wordpress-store-locator'),"PL" => __("Poland", 'wordpress-store-locator'),"PT" => __("Portugal", 'wordpress-store-locator'),"PR" => __("Puerto Rico", 'wordpress-store-locator'),"QA" => __("Qatar", 'wordpress-store-locator'),"RO" => __("Romania", 'wordpress-store-locator'),"RU" => __("Russia", 'wordpress-store-locator'),"RW" => __("Rwanda", 'wordpress-store-locator'),"RE" => __("Réunion", 'wordpress-store-locator'),"BL" => __("Saint Barthélemy", 'wordpress-store-locator'),"SH" => __("Saint Helena", 'wordpress-store-locator'),"KN" => __("Saint Kitts and Nevis", 'wordpress-store-locator'),"LC" => __("Saint Lucia", 'wordpress-store-locator'),"MF" => __("Saint Martin", 'wordpress-store-locator'),"PM" => __("Saint Pierre and Miquelon", 'wordpress-store-locator'),"VC" => __("Saint Vincent and the Grenadines", 'wordpress-store-locator'),"WS" => __("Samoa", 'wordpress-store-locator'),"SM" => __("San Marino", 'wordpress-store-locator'),"SA" => __("Saudi Arabia", 'wordpress-store-locator'),"SN" => __("Senegal", 'wordpress-store-locator'),"RS" => __("Serbia", 'wordpress-store-locator'),"CS" => __("Serbia and Montenegro", 'wordpress-store-locator'),"SC" => __("Seychelles", 'wordpress-store-locator'),"SL" => __("Sierra Leone", 'wordpress-store-locator'),"SG" => __("Singapore", 'wordpress-store-locator'),"SK" => __("Slovakia", 'wordpress-store-locator'),"SI" => __("Slovenia", 'wordpress-store-locator'),"SB" => __("Solomon Islands", 'wordpress-store-locator'),"SO" => __("Somalia", 'wordpress-store-locator'),"ZA" => __("South Africa", 'wordpress-store-locator'),"GS" => __("South Georgia and the South Sandwich Islands", 'wordpress-store-locator'),"KR" => __("South Korea", 'wordpress-store-locator'),"ES" => __("Spain", 'wordpress-store-locator'),"LK" => __("Sri Lanka", 'wordpress-store-locator'),"SD" => __("Sudan", 'wordpress-store-locator'),"SR" => __("Suriname", 'wordpress-store-locator'),"SJ" => __("Svalbard and Jan Mayen", 'wordpress-store-locator'),"SZ" => __("Swaziland", 'wordpress-store-locator'),"SE" => __("Sweden", 'wordpress-store-locator'),"CH" => __("Switzerland", 'wordpress-store-locator'),"SY" => __("Syria", 'wordpress-store-locator'),"ST" => __("São Tomé and Príncipe", 'wordpress-store-locator'),"TW" => __("Taiwan", 'wordpress-store-locator'),"TJ" => __("Tajikistan", 'wordpress-store-locator'),"TZ" => __("Tanzania", 'wordpress-store-locator'),"TH" => __("Thailand", 'wordpress-store-locator'),"TL" => __("Timor-Leste", 'wordpress-store-locator'),"TG" => __("Togo", 'wordpress-store-locator'),"TK" => __("Tokelau", 'wordpress-store-locator'),"TO" => __("Tonga", 'wordpress-store-locator'),"TT" => __("Trinidad and Tobago", 'wordpress-store-locator'),"TN" => __("Tunisia", 'wordpress-store-locator'),"TR" => __("Turkey", 'wordpress-store-locator'),"TM" => __("Turkmenistan", 'wordpress-store-locator'),"TC" => __("Turks and Caicos Islands", 'wordpress-store-locator'),"TV" => __("Tuvalu", 'wordpress-store-locator'),"UM" => __("U.S. Minor Outlying Islands", 'wordpress-store-locator'),"PU" => __("U.S. Miscellaneous Pacific Islands", 'wordpress-store-locator'),"VI" => __("U.S. Virgin Islands", 'wordpress-store-locator'),"UG" => __("Uganda", 'wordpress-store-locator'),"UA" => __("Ukraine", 'wordpress-store-locator'),"SU" => __("Union of Soviet Socialist Republics", 'wordpress-store-locator'),"AE" => __("United Arab Emirates", 'wordpress-store-locator'),"GB" => __("United Kingdom", 'wordpress-store-locator'),"US" => __("United States", 'wordpress-store-locator'),"ZZ" => __("Unknown or Invalid Region", 'wordpress-store-locator'),"UY" => __("Uruguay", 'wordpress-store-locator'),"UZ" => __("Uzbekistan", 'wordpress-store-locator'),"VU" => __("Vanuatu", 'wordpress-store-locator'),"VA" => __("Vatican City", 'wordpress-store-locator'),"VE" => __("Venezuela", 'wordpress-store-locator'),"VN" => __("Vietnam", 'wordpress-store-locator'),"WK" => __("Wake Island", 'wordpress-store-locator'),"WF" => __("Wallis and Futuna", 'wordpress-store-locator'),"EH" => __("Western Sahara", 'wordpress-store-locator'),"YE" => __("Yemen", 'wordpress-store-locator'),"ZM" => __("Zambia", 'wordpress-store-locator'),"ZW" => __("Zimbabwe", 'wordpress-store-locator'),"AX" => __("Åland Islands", 'wordpress-store-locator'));

        return $countries;
    }

    /**
     * Join postmeta in admin stores search
     *
     * @return string SQL join
     */
    public function admin_meta_search_join($join)
    {
        global $pagenow, $wpdb;
        if ( is_admin() && $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'stores' && ! empty( $_GET['s'] ) ) {
            $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
        }
        return $join;
    }

    /**
     * Filtering the where clause in admin stores search query
     *
     * @return string SQL WHERE
     */
    function admin_meta_search_where( $where ){
        global $pagenow, $wpdb;
        if ( is_admin() && $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'stores' && ! empty( $_GET['s'] ) ) {
            $where = preg_replace(
           "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
           "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
        }
        return $where;
    }


    /**
     * Limit by one
     *
     * @return string SQL WHERE
     */    
    function admin_meta_search_limits($groupby) {
        global $pagenow, $wpdb;
        if ( is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type']=='stores' && isset($_GET['s']) && $_GET['s'] != '' ) {
            $groupby = "$wpdb->posts.ID";
        }
        return $groupby;
    }
}