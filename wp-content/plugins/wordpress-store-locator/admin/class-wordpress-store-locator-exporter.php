<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class WordPress_Store_Locator_Exporter
{
    private $plugin_name;
    private $version;
    private $options;

    /**
     * Construct Store Locator Admin Class
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    http://plugins.welaunch.io
     * @param   string                         $plugin_name
     * @param   string                         $version    
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->notice = "";
    }

    /**
     * Get Options
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    http://plugins.welaunch.io
     * @param   mixed                         $option The option key
     * @return  mixed                                 The option value
     */
    private function get_option($option)
    {
        if(!is_array($this->options)) {
            return false;
        }

        if (!array_key_exists($option, $this->options)) {
            return false;
        }

        return $this->options[$option];
    }

    public function init()
    {
        global $wordpress_store_locator_options;

        $this->options = $wordpress_store_locator_options;

        add_action( 'admin_notices', array($this, 'notice' ));

        if ( ! is_admin() || !is_user_logged_in() || !current_user_can('manage_options')) {
            $this->notice .= "You are not an admin";
            return FALSE;
        }

        $stores = $this->get_stores();
        if(empty($stores)) {
            $this->notice .= "No Stores to Export";
            return FALSE;
        }
        
        if($this->build_export($stores)) {
            $this->notice .= "Your Store Export is ready. The Download should start automatically.";
        } else {
            $this->notice .= "Something was wrong with the export generation ...";
        };
        
    }

    public function get_stores()
    {
        $args = array(
            'posts_per_page'   => -1,
            'post_type'        => 'stores',
            'post_status'      => 'publish',
        );
        $posts = get_posts( $args );

        $prefix = 'wordpress_store_locator_';
        $possibleCategories = get_terms(array( 'taxonomy' =>'store_category', 'hide_empty' => false));
        $possibleFilters = get_terms(array( 'taxonomy' =>'store_filter', 'hide_empty' => false));
        
        $stores = array();
        foreach ($posts as $post) {
            $id = $post->ID;

            $stores[$id]['id'] = $id;
            $stores[$id]['customerId'] = get_post_meta( $id, $prefix . 'customerId', true);
            $stores[$id]['name'] = $post->post_title;
            
            $stores[$id]['address1'] = get_post_meta( $id, $prefix . 'address1', true);
            $stores[$id]['address2'] = get_post_meta( $id, $prefix . 'address2', true);
            $stores[$id]['zip'] = get_post_meta( $id, $prefix . 'zip', true);
            $stores[$id]['city'] = get_post_meta( $id, $prefix . 'city', true);
            $stores[$id]['region'] = get_post_meta( $id, $prefix . 'region', true);
            $stores[$id]['country'] = get_post_meta( $id, $prefix . 'country', true);
            $stores[$id]['telephone'] = get_post_meta( $id, $prefix . 'telephone', true);
            $stores[$id]['mobile'] = get_post_meta( $id, $prefix . 'mobile', true);
            $stores[$id]['fax'] = get_post_meta( $id, $prefix . 'fax', true);
            $stores[$id]['email'] = get_post_meta( $id, $prefix . 'email', true);
            $stores[$id]['website'] = get_post_meta( $id, $prefix . 'website', true);
            $stores[$id]['premium'] = get_post_meta( $id, $prefix . 'premium', true);
            $stores[$id]['ranking'] = get_post_meta( $id, $prefix . 'ranking', true);
            $stores[$id]['icon'] = get_post_meta( $id, $prefix . 'icon', true);
            $stores[$id]['lat'] = get_post_meta( $id, $prefix . 'lat', true);
            $stores[$id]['lng'] = get_post_meta( $id, $prefix . 'lng', true);

            $customFields = $this->get_option('showCustomFields');
            if(!empty($customFields)) {

                foreach ($customFields as $customFieldKey => $customFieldName) {

                    $originalCustomFieldKey = $customFieldKey;
                    $customFieldKey = $prefix . $customFieldKey;
                    
                    $stores[$id][$originalCustomFieldKey] = get_post_meta( $id, $customFieldKey, true);
                }
            }

            $stores[$id]['description'] = $post->post_content;

            $storeFilters = wp_get_post_terms( $id, 'store_filter', array('fields' => 'slugs') );
            foreach ($possibleFilters as $possibleFilter) {

                $filter = $possibleFilter->slug;
                if(in_array($filter, $storeFilters))
                {
                    $stores[$id][$filter] = 1;
                } else {
                    $stores[$id][$filter] = 0;
                }
            }

            $storeCategories = wp_get_post_terms( $id, 'store_category', array('fields' => 'slugs') );
            foreach ($possibleCategories as $possibleCategory) {

                $category = $possibleCategory->slug;
                if(in_array($category, $storeCategories))
                {
                    $stores[$id][$category] = 1;
                } else {
                    $stores[$id][$category] = 0;
                }
            }

            $weekdays = array(
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday',
            );

            foreach ($weekdays as $weekday) {
                $stores[$id][$weekday . '_open'] = get_post_meta( $id, $prefix . $weekday . '_open', true);
                $stores[$id][$weekday . '_close'] = get_post_meta( $id, $prefix . $weekday . '_close', true);
            }

            foreach ($weekdays as $weekday) {
                $stores[$id][$weekday . '_open2'] = get_post_meta( $id, $prefix . $weekday . '_open2', true);
                $stores[$id][$weekday . '_close2'] = get_post_meta( $id, $prefix . $weekday . '_close2', true);
            }
        }

        return $stores;
    }

    public function build_export($stores)
    {
        $excelExt = '.xlsx';
        $writer = 'Xlsx';

        $useExcel2007 = $this->get_option('excel2007');

        if($useExcel2007 == "1") {
            $excelExt = '.xls';
            $writer = 'Xls';
        }

        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator("weLaunch")
                                     ->setLastModifiedBy("weLaunch")
                                     ->setTitle("Store Export (".date('Y.m.d - H:i:s').")")
                                     ->setSubject("Stores export")
                                     ->setDescription("Stores export.")
                                     ->setKeywords("wordpress stores");
        // Add some data
        // A note from the manual: In PHPExcel column index is 0-based while row index is 1-based. That means 'A1' ~ (0,1)
        $row = 1; // 1-based index
        $firstLine = true;
        foreach ($stores as $fields) {
            $col = 1;
            if ($firstLine) {
                $keys = array_keys($fields);
                foreach ($keys as $key) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $key);
                    $col++;
                }
                $row++;
                $col = 1;
                $firstLine = false;
            } 
           
            foreach($fields as $key => $value) {

                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }

        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Export ('.date('Y.m.d - H.i.s').')');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        // ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="stores-export_' . date('Y-m-d_H-i-s') . $excelExt . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, $writer);
        // ob_end_clean();
        $writer->save('php://output');

        exit();
        return TRUE;
    }

    public function getSampleImportFile()
    {
        global $wordpress_store_locator_options;

        $this->options = $wordpress_store_locator_options;

        $stores = array();

        $possibleCategories = get_terms(array( 'taxonomy' =>'store_category', 'hide_empty' => false));
        $possibleFilters = get_terms(array( 'taxonomy' =>'store_filter', 'hide_empty' => false));
        
        $id = 1;
        $stores[$id]['id'] = '';
        $stores[$id]['customerId'] = '123-CRM';
        $stores[$id]['name'] = 'weLaunch';

        $stores[$id]['address1'] = 'In den Sandbergen 36';
        $stores[$id]['address2'] = '';
        $stores[$id]['zip'] = '49808';
        $stores[$id]['city'] = 'Lingen';
        $stores[$id]['region'] = 'Niedersachsen';
        $stores[$id]['country'] = 'DE';
        $stores[$id]['telephone'] = '0591 - 48482';
        $stores[$id]['mobile'] = '01511 - 48482';
        $stores[$id]['fax'] = '';
        $stores[$id]['email'] = 'support@welaunch.io';
        $stores[$id]['website'] = 'https://welaunch.io';
        $stores[$id]['premium'] = '0';
        $stores[$id]['ranking'] = '10';
        $stores[$id]['icon'] = 'https://welaunch.io/plugins/wp-content/uploads/2017/05/marker-grey-black-border.png';
        $stores[$id]['lat'] = '56.4545';
        $stores[$id]['lng'] = '12.35345';

        $customFields = $this->get_option('showCustomFields');
        if(!empty($customFields)) {
            foreach ($customFields as $customFieldKey => $customFieldName) {
                $stores[$id][$customFieldKey] = 'Custom Value X';
            }
        }

        $stores[$id]['description'] = 'Web Agency';

        foreach ($possibleFilters as $possibleFilter) {
            $filter = $possibleFilter->slug;
            $stores[$id][$filter] = 0;
        }

        foreach ($possibleCategories as $possibleCategory) {
            $category = $possibleCategory->slug;
            $stores[$id][$category] = 0;
        }

        $weekdays = array(
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        );
        foreach ($weekdays as $weekday) {
            $stores[$id][$weekday . '_open'] = '08:00';
            $stores[$id][$weekday . '_close'] = '17:00';
        }

        foreach ($weekdays as $weekday) {
            $stores[$id][$weekday . '_open2'] = '08:00';
            $stores[$id][$weekday . '_close2'] = '17:00';
        }

        if($this->build_export($stores)) {
            $this->notice .= "Demo Store Export is ready. The Download should start automatically.";
        } else {
            $this->notice .= "Something was wrong with the export generation ...";
        };
    }

    public function notice()
    {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo $this->notice ?></p>
        </div>
        <?php
    }
}