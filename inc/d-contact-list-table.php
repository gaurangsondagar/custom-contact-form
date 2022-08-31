<?php
/**
 * Data table class.
 *
 * @since 1.0
 *
 * @author  Gaurang Sondagar
 * @access public
 */

if(is_admin())
{
    new D_Contact_List_Table();
}


class D_Contact_List_Table
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
        add_action( 'admin_menu', array($this, 'd_contact_list_table_page' ));
    }

    /**
     * Menu item will allow us to load the page to display the table
     */
    public function d_contact_list_table_page()
    {
        add_menu_page( __('Custom Contacts Form', 'differenz-contacts'), __('Custom Contacts Form', 'differenz-contacts'), 'manage_options', 'd-contact-list-table.php', array($this, 'd_contact_list_tbl_page') );
    }

    /**
     * Display the list table page
     *
     * @return Void
     */
    public function d_contact_list_tbl_page()
    {
        $differenzListTable = new Differenz_List_Table();
        $differenzListTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2><?php esc_html_e('Custom Contacts List', 'differenz-contacts'); ?></h2>
                <?php $differenzListTable->display(); ?>
            </div>
        <?php
    }
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Differenz_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'name'       => __('Name', 'differenz-contacts'),
            'email' => __('Email', 'differenz-contacts'),
            'subject'        => __('Subject', 'differenz-contacts'),
            'message'    => __('Massage', 'differenz-contacts'),
            'created_date'    => __('Date', 'differenz-contacts'),
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('name' => array('name', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();

        global $wpdb;

        $data = $wpdb->get_results('select * from '.$wpdb->prefix.'differenz_contact', ARRAY_A);

        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'name':
            case 'email':
            case 'subject':
            case 'message':
            case 'created_date':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'name';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
}
?>
