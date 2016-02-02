<?php

/**
 * BP_Project_Framework class.
 */
class BPPF_Loader {

		protected $plugin = null;

    /**
     * __construct function.
     *
     * @access public
     * @return \BP_Project_Framework
     */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
		$this->actions();

	}


	/**
	 * actions function.
	 *
	 * @access private
	 * @return void
	 */
	public function actions() {


		add_action( 'bp_include', array( $this, 'includes' ) );

        // these are for template file overrides.
		add_action( 'bp_register_theme_packages', array( $this, 'bp_custom_templatepack_work' ) );
		add_filter( 'pre_option__bp_theme_package_id', array( $this, 'bp_custom_templatepack_package_id' ) );
		add_action( 'wp', array( $this, 'bp_templatepack_kill_legacy_js_and_css' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	/**
	 * inc function.
	 *
	 * @access public
	 * @return void
	 */
	public function includes() {
        // to include a file place it in the inc directory
        foreach( glob(  plugin_dir_path(__FILE__) . '/core/*.php' ) as $filename ) {
            include $filename;
        }
	}


	/**
	 * enqueue_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_style( 'font-awesome', plugins_url( 'assets/bower/font-awesome/css/font-awesome.min.css', __FILE__ ), array(), '4.4.0' );
		wp_enqueue_style( 'font-awesome' );
	}


	/**
	 * templatepack_work function.
	 *
	 * @access public
	 * @return void
	 */
	public function bp_custom_templatepack_work() {

		bp_register_theme_package( array(
				'id'      => 'templates',
				'name'    => __( 'BuddyPress Templates', 'buddypress' ),
				'version' => bp_get_version(),
				'dir'     => bpf()->path . 'templates',
				'url'     => bpf()->url . 'templates'
			)
		);
	}


	/**
	 * templatepack_package_id function.
	 *
	 * @access public
	 * @param mixed $package_id
	 * @return void
	 */
	public function bp_custom_templatepack_package_id( $package_id ) {
		return 'templates';
	}


	// Proposed BP core change: see http://buddypress.trac.wordpress.org/ticket/3741#comment:43
	/**
	 * templatepack_kill_legacy_js_and_css function.
	 *
	 * @access public
	 * @return void
	 */
	public function bp_templatepack_kill_legacy_js_and_css() {
		wp_dequeue_script( 'groups_widget_groups_list-js' );
		wp_dequeue_script( 'bp_core_widget_members-js' );

	}


}