<?php
/*
Plugin Name: WSUWP University Taxonomies
Version: 0.3.0
Plugin URI: https://web.wsu.edu/
Description: Provides Washington State University taxonomies to WordPress
Author: washingtonstateuniversity, jeremyfelt
Author URI: https://web.wsu.edu/
*/

class WSUWP_University_Taxonomies {

	/**
	 * Maintain a record of the taxonomy schema. This should be changed whenever
	 * a schema change should be initiated on any site using the taxonomy.
	 *
	 * @var string Current version of the taxonomy schema.
	 */
	var $taxonomy_schema_version = '20150722-001';

	/**
	 * @var string Taxonomy slug for the WSU University Category taxonomy.
	 */
	var $university_category = 'wsuwp_university_category';

	/**
	 * @var string Taxonomy slug for the University Location taxonomy.
	 */
	var $university_location = 'wsuwp_university_location';

	/**
	 * @var string Taxonomy slug for the University Organization taxonomy.
	 */
	var $university_organization = 'wsuwp_university_org';

	/**
	 * Fire necessary hooks when instantiated.
	 */
	function __construct() {
		add_action( 'wpmu_new_blog',         array( $this, 'pre_load_taxonomies' ), 10 );
		add_action( 'admin_init',            array( $this, 'check_schema' ), 10 );
		add_action( 'wsu_taxonomy_update_schema', array( $this, 'update_schema' ) );
		add_action( 'init',                  array( $this, 'modify_default_taxonomy_labels' ), 10 );
		add_action( 'init',                  array( $this, 'register_taxonomies'            ), 11 );
		add_action( 'load-edit-tags.php',    array( $this, 'compare_schema'                 ), 10 );
		add_action( 'load-edit-tags.php',    array( $this, 'display_terms'                  ), 11 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts'          )     );
		add_filter( 'pre_insert_term',       array( $this, 'prevent_term_creation'          ), 10, 2 );
	}

	/**
	 * Pre-load University wide taxonomies whenever a new site is created on the network.
	 *
	 * @param int $site_id The ID of the new site.
	 */
	public function pre_load_taxonomies( $site_id ) {
		switch_to_blog( $site_id );
		$this->update_schema();
		restore_current_blog();
	}

	/**
	 * Check the current version of the taxonomy schema on every admin page load. If it is
	 * out of date, fire a single wp-cron event to process the changes.
	 */
	public function check_schema() {
		if ( $this->taxonomy_schema_version !== get_option( 'wsu_taxonomy_schema', false ) ) {
			wp_schedule_single_event( time() + 60, 'wsu_taxonomy_update_schema' );
		}
	}

	/**
	 * Update the taxonomy schema and version.
	 */
	public function update_schema() {
		$this->load_terms( $this->university_category );
		$this->load_terms( $this->university_location );
		$this->load_terms( $this->university_organization );
		update_option( 'wsu_taxonomy_schema', $this->taxonomy_schema_version );
	}

	/**
	 * Modify the default labels assigned by WordPress to built in taxonomies.
	 */
	public function modify_default_taxonomy_labels() {
		global $wp_taxonomies;

		$wp_taxonomies['category']->labels->name          = 'Site Categories';
		$wp_taxonomies['category']->labels->singular_name = 'Site Category';
		$wp_taxonomies['category']->labels->menu_name     = 'Site Categories';

		$wp_taxonomies['post_tag']->labels->name          = 'University Tags';
		$wp_taxonomies['post_tag']->labels->singular_name = 'University Tag';
		$wp_taxonomies['post_tag']->labels->menu_name     = 'University Tags';
	}

	/**
	 * In normal term entry situations, we prevent new terms being created for the
	 * taxonomies that we statically maintain.
	 *
	 * @param string $term     Term being added.
	 * @param string $taxonomy Taxonomy of the term being added.
	 *
	 * @return string|WP_Error Pass on the term untouched if not one of our taxonomies. WP_Error otherwise.
	 */
	public function prevent_term_creation( $term, $taxonomy ) {
		if ( in_array( $taxonomy, array( $this->university_location, $this->university_organization, $this->university_category ) ) ) {
			$term = new WP_Error( 'invalid_term', 'These terms cannot be modified.' );
		}

		return $term;
	}

	/**
	 * Register the central University taxonomies provided.
	 *
	 * Taxonomies are registered to core post types by default. To take advantage of these
	 * custom taxonomies in your custom post types, use register_taxonomy_for_object_type().
	 */
	public function register_taxonomies() {
		$labels = array(
			'name'          => 'University Categories',
			'singular_name' => 'University Category',
			'search_items'  => 'Search Categories',
			'all_items'     => 'All Categories',
			'edit_item'     => 'Edit Category',
			'update_item'   => 'Update Category',
			'add_new_item'  => 'Add New Category',
			'new_item_name' => 'New Category Name',
			'menu_name'     => 'University Categories',
		);
		$args = array(
			'labels'            => $labels,
			'description'       => 'The central taxonomy for Washington State University',
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'rewrite'           => false,
			'query_var'         => $this->university_category,
		);
		register_taxonomy( $this->university_category, array( 'post', 'page', 'attachment' ), $args );

		$labels = array(
			'name'          => 'University Location',
			'search_items'  => 'Search Locations',
			'all_items'     => 'All Locations',
			'edit_item'     => 'Edit Location',
			'update_item'   => 'Update Location',
			'add_new_item'  => 'Add New Location',
			'new_item_name' => 'New Location Name',
			'menu_name'     => 'University Locations',
		);
		$args = array(
			'labels'            => $labels,
			'description'       => 'The central location taxonomy for Washington State University',
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'rewrite'           => false,
			'query_var'         => $this->university_location,
		);
		register_taxonomy( $this->university_location, array( 'post', 'page', 'attachment' ), $args );

		$labels = array(
			'name' => 'University Organization',
			'search_items'  => 'Search Organizations',
			'all_items'     => 'All Organizations',
			'edit_item'     => 'Edit Organization',
			'update_item'   => 'Update Organization',
			'add_new_item'  => 'Add New Organization',
			'new_item_name' => 'New Organization Name',
			'menu_name'     => 'University Organizations',
		);
		$args = array(
			'labels'            => $labels,
			'description'       => 'The central organization taxonomy for Washington State University',
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'rewrite'           => false,
			'query_var'         => $this->university_organization,
		);
		register_taxonomy( $this->university_organization, array( 'post', 'page' ), $args );
	}

	/**
	 * Clear all cache for a given taxonomy.
	 *
	 * @param string $taxonomy A taxonomy slug.
	 */
	private function clear_taxonomy_cache( $taxonomy ) {
		wp_cache_delete( 'all_ids', $taxonomy );
		wp_cache_delete( 'get',     $taxonomy );
		delete_option( $taxonomy . '_children' );
		_get_term_hierarchy( $taxonomy );
	}

	/**
	 * Compare the existing schema version on taxonomy page loads and run update
	 * process if a mismatch is present.
	 */
	public function compare_schema() {
		if ( ! in_array( get_current_screen()->taxonomy, array( $this->university_location, $this->university_organization, $this->university_category ) ) ) {
			return;
		}

		if ( $this->taxonomy_schema_version !== get_option( 'wsu_taxonomy_schema', false ) ) {
			$this->update_schema();
		}
	}

	/**
	 * Ensure all of the pre-configured terms for a given taxonomy are loaded with
	 * the proper parent -> child relationships.
	 *
	 * @param string $taxonomy Taxonomy being loaded.
	 */
	public function load_terms( $taxonomy ) {
		$this->clear_taxonomy_cache( $taxonomy );

		// Get a master list of terms used to populate this taxonomy.
		if ( $this->university_category === $taxonomy ) {
			$master_list = $this->get_university_categories();
		} elseif ( $this->university_location === $taxonomy ) {
			$master_list = $this->get_university_locations();
		} elseif ( $this->university_organization === $taxonomy ) {
			$master_list = $this->get_university_organizations();
		} else {
			return;
		}

		// Get our current list of top level parents.
		$level1_exist  = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => '0' ) );
		$level1_assign = array();
		foreach( $level1_exist as $level1 ) {
			$level1_assign[ $level1->name ] = array( 'term_id' => $level1->term_id );
		}

		remove_filter( 'pre_insert_term', array( $this, 'prevent_term_creation' ), 10 );

		$level1_names = array_keys( $master_list );
		/**
		 * Look for mismatches between the master list and the existing parent terms list.
		 *
		 * In this loop:
		 *
		 *     * $level1_names    array of top level parent names.
		 *     * $level1_name     string containing a top level category.
		 *     * $level1_children array containing all of the current parent's child arrays.
		 *     * $level1_assign   array of top level parents that exist in the database with term ids.
		 */
		foreach( $level1_names as $level1_name ) {
			if ( ! array_key_exists( $level1_name, $level1_assign ) ) {
				$new_term = wp_insert_term( $level1_name, $taxonomy, array( 'parent' => '0' ) );
				if ( ! is_wp_error( $new_term ) ) {
					$level1_assign[ $level1_name ] = array( 'term_id' => $new_term['term_id'] );
				}
			}
		}

		/**
		 * Process the children of each top level parent.
		 *
		 * In this loop:
		 *
		 *     * $level1_names    array of top level parent names.
		 *     * $level1_name     string containing a top level category.
		 *     * $level1_children array containing all of the current parent's child arrays.
		 *     * $level2_assign   array of this parent's second level categories that exist in the database with term ids.
		 */
		foreach( $level1_names as $level1_name ) {
			$level2_exists = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $level1_assign[ $level1_name ]['term_id'] ) );
			$level2_assign = array();

			foreach( $level2_exists as $level2 ) {
				$level2_assign[ $level2->name ] = array( 'term_id' =>  $level2->term_id );
			}

			$level2_names = array_keys( $master_list[ $level1_name ] );
			/**
			 * Look for mismatches between the expected and real children of the current parent.
			 *
			 * In this loop:
			 *
			 *     * $level2_names    array of the current parent's child level names.
			 *     * $level2_name     string containing a second level category.
			 *     * $level2_children array containing the current second level category's children. Unused in this context.
			 *     * $level2_assign   array of this parent's second level categories that exist in the database with term ids.
			 */
			foreach( $level2_names as $level2_name ) {
				if ( ! array_key_exists( $level2_name, $level2_assign ) ) {
					$new_term = wp_insert_term( $level2_name, $taxonomy, array( 'parent' => $level1_assign[ $level1_name ]['term_id'] ) );
					if ( ! is_wp_error( $new_term ) ) {
						$level2_assign[ $level2_name ] = array( 'term_id' => $new_term['term_id'] );
					}
				}
			}

			/**
			 * Look for mismatches between second and third level category relationships.
			 */
			foreach( $level2_names as $level2_name ) {
				$level3_exists = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $level2_assign[ $level2_name ]['term_id'] ) );
				$level3_exists = wp_list_pluck( $level3_exists, 'name' );

				$level3_names = $master_list[ $level1_name ][ $level2_name ];
				foreach( $level3_names as $level3_name ) {
					if ( ! in_array( $level3_name, $level3_exists ) ) {
						wp_insert_term( $level3_name, $taxonomy, array( 'parent' => $level2_assign[ $level2_name ]['term_id'] ) );
					}
				}
			}
		}

		add_filter( 'pre_insert_term', array( $this, 'prevent_term_creation' ), 10 );

		$this->clear_taxonomy_cache( $taxonomy );
	}

	/**
	 * Enqueue styles to be used for the display of taxonomy terms.
	 *
	 * @param string $hook Hook indicating the current admin page.
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( 'edit-tags.php' !== $hook && 'post.php' !== $hook && 'post-new.php' !== $hook ) {
			return;
		}

		if ( in_array( get_current_screen()->taxonomy, array( $this->university_organization, $this->university_category, $this->university_location ) ) ) {
			wp_enqueue_style( 'wsuwp-taxonomy-admin', plugins_url( 'css/edit-tags-style.css', __FILE__ ) );
		}

		if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
			wp_enqueue_style( 'wsuwp-taxonomy-edit-post', plugins_url( 'css/edit-post.css', __FILE__ ) );
		}

	}

	/**
	 * Display a dashboard for a custom taxonomy rather than the default term
	 * management screen provided by WordPress core.
	 */
	public function display_terms() {
		if ( ! in_array( get_current_screen()->taxonomy, array( $this->university_organization, $this->university_category, $this->university_location ) ) ) {
			return;
		}

		$taxonomy = get_current_screen()->taxonomy;

		// Setup the page.
		global $title;
		$tax = get_taxonomy( $taxonomy );
		$title = $tax->labels->name;
		require_once( ABSPATH . 'wp-admin/admin-header.php' );
		echo '<div class="wrap nosubsub""><h2>' . esc_html( $title ) . '</h2>';

		$parent_terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => '0' ) );

		foreach( $parent_terms as $term ) {
			echo '<h3>' . esc_html( $term->name ) . '</h3>';
			$child_terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $term->term_id ) );

			foreach( $child_terms as $child ) {
				echo '<h4>' . esc_html( $child->name ) . '</h4>';
				$grandchild_terms = get_terms( $taxonomy, array( 'hide_empty' => false, 'parent' => $child->term_id ) );

				echo '<ul>';

				if ( empty( $grandchild_terms ) ) {
					echo '<li><em>No level 3 categories for this term.</em></li>';
				}
				foreach ( $grandchild_terms as $grandchild ) {
					echo '<li>' . esc_html( $grandchild->name ) . '</li>';
				}
				echo '</ul>';
			}

		}

		// Close the page.
		echo '</div>';
		include( ABSPATH . 'wp-admin/admin-footer.php' );
		die();
	}

	/**
	 * Maintain an array of current university organizations.
	 *
	 * @return array University Organizations
	 */
	public function get_university_organizations() {
		$organizations = array(
			'Office' => array(
				'International Programs' => array(),
				'University Communications' => array(),
			),
			'College' => array(
				'Carson College of Business' => array(),
				'CAHNRS' => array(),
				'College of Arts and Sciences' => array(),
				'College of Education' => array(),
				'College of Medical Sciences' => array(),
				'College of Nursing' => array(),
				'College of Pharmacy' => array(),
				'College of Veterinary Medicine' => array(),
				'Edward R. Murrow College of Communication' => array(),
				'Honors College' => array(),
				'Voiland College of Engineering and Architecture' => array(),
			),
		);

		return $organizations;
	}

	/**
	 * Maintain an array of current university locations.
	 *
	 * @return array Current university locations.
	 */
	public function get_university_locations() {
		$locations = array(
			'WSU Pullman'                      => array(),
			'WSU West/Downtown Seattle'        => array(),
			'WSU Spokane'                      => array(),
			'WSU Tri-Cities'                   => array(),
			'WSU Vancouver'                    => array(),
			'WSU Global Campus'                => array(),
			'WSU Extension'                    => array(
				'Asotin County' => array(),
				'Benton County' => array(),
				'Chelan County' => array(),
				'Clallam County' => array(),
				'Clark County' => array(),
				'Columbia County' => array(),
				'Cowlitz County' => array(),
				'Douglas County' => array(),
				'Ferry County' => array(),
				'Franklin County' => array(),
				'Garfield County' => array(),
				'Grant County' => array(),
				'Grays Harbor County' => array(),
				'Island County' => array(),
				'Jefferson County' => array(),
				'King County' => array(),
				'Kitsap County' => array(),
				'Kittitas County' => array(),
				'Klickitat County' => array(),
				'Lewis County' => array(),
				'Lincoln County' => array(),
				'Mason County' => array(),
				'Okanogan County' => array(),
				'Pacific County' => array(),
				'Pend Oreille County' => array(),
				'Pierce County' => array(),
				'San Juan County' => array(),
				'Skagit County' => array(),
				'Skamania County' => array(),
				'Snohomish County' => array(),
				'Spokane County' => array(),
				'Stevens County' => array(),
				'Thurston County' => array(),
				'Wahkiakum County' => array(),
				'Walla Walla County' => array(),
				'Whatcom County' => array(),
				'Whitman County' => array(),
				'Yakima County' => array(),
			),
			'WSU Seattle'                      => array(),
			'WSU North Puget Sound at Everett' => array(),
			'WSU Research Centers'             => array(
				'Lind' => array(),
				'Long Beach' => array(),
				'Mount Vernon' => array(),
				'Othello' => array(),
				'Prosser' => array(),
				'Puyallup' => array(),
				'Wenatchee' => array(),
			)
		);

		return $locations;
	}

	/**
	 * Maintain an array of current university categories.
	 *
	 * @return array Current university categories.
	 */
	public function get_university_categories() {
		$categories = array(
			'Academic Subjects' => array(
				'Agriculture' => array(
					'Agriculture Business',
					'Agriculture Economics',
					'Agriculture Engineering',
					'Animal Sciences',
					'Berries',
					'Crop Sciences',
					'Equipment / Mechanization',
					'Fodder / Silage',
					'Food Science',
					'Forestry',
					'Fruit Trees',
					'Fungus',
					'Horticulture',
					'Irrigation / Water Management',
					'Legumes, Pulse',
					'Mint',
					'Oil Seed',
					'Organic Farming',
					'Pests and Weeds',
					'Plant Pathology',
					'Small Grains',
					'Soil Sciences',
					'Tubers',
					'Vegetables',
					'Viticulture / Enology / Wine',
					'Weather, Climate',
				),
				'Arts' => array(
					'Digital Media',
					'Fine Arts',
					'Performing Arts',
				),
				'Biology' => array(
					'Botany',
					'Entomology',
					'Genomics and Bioinformatics',
					'Molecular Biology',
					'Neuroscience',
					'Zoology',
				),
				'Business' => array(
					'Accounting',
					'Construction Management',
					'Economics',
					'Finance',
					'Hospitality',
					'Information Systems',
					'Investment',
					'Management',
					'Sports Management',
				),
				'Chemistry' => array(),
				'Communication, Academic' => array(
					'Advertising',
					'Broadcasting',
					'Electronic',
					'Journalism',
					'Public Relations',
				),
				'Computer Sciences' => array(
					'Computer Engineering',
					'Computer Science',
					'Power Systems',
					'Smart Environments',
				),
				'Design, Construction' => array(
					'Architecture',
					'Interior Design',
					'Landscape Architecture',
				),
				'Earth Sciences' => array(
					'Environmental Studies',
					'Geology',
					'Natural Resources',
				),
				'Education, Academic' => array(
					'Administration',
					'Special Education',
					'Teaching',
				),
				'Engineering' => array(
					'Atmospheric Research',
					'Catalysis',
					'Energy Conversion',
					'Infrastructure',
					'Structures',
				),
				'Family and Consumer Science' => array(
					'Apparel and Textile Design',
					'Food and Sensory Science',
					'Home Economics',
					'Human Development',
					'Nutrition',
				),
				'Health Sciences' => array(
					'Addictions',
					'Cancer',
					'Childhood Trauma',
					'Chronic Illness',
					'Exercise Physiology',
					'Health Administration',
					'Health Policy',
					'Medical Health',
					'Metabolic Disorders',
					'Nursing',
					'Nutrition, Health',
					'Pharmacy',
					'Physical Performance / Recreation',
					'Sleep',
					'Speech and Hearing',
				),
				'Humanities' => array(
					'English',
					'History',
					'Languages',
					'Literature',
					'Philosophy',
				),
				'Mathematics' => array(),
				'Music' => array(
					'Instrumental',
					'Vocal',
				),
				'Physics' => array(),
				'Social Sciences' => array(
					'Anthropology',
					'Archaeology',
					'Criminology / Criminal Justice',
					'Cultural and Ethnic Studies',
					'Gender and Sexuality Studies',
					'Geography',
					'Military',
					'Political Science',
					'Psychology',
					'Religion',
					'Sociology',
				),
				'Space Sciences' => array(
					'Astronomy',
				),
				'Veterinary Medicine' => array(
					'Companion Animals',
					'Emerging Diseases',
					'Equine',
					'Exotic / Pocket Pets',
					'Food Animal',
					'Foreign Animal Diseases',
					'Pathology',
					'Pharmacology, Animal',
					'Zoonoses',
				),
			),
			'Alumni' => array(
				'Alumni Association' => array(
					'Alumni Centre',
					'Awards',
					'Alumni Benefits',
					'Alumni Events',
					'Membership',
				),
				'Notable Alumni' => array(
					'Athletes',
					'Business Leaders',
					'Government Leaders',
					'Other Notable Alumni',
					'Philanthropists',
					'Scientists',
				),
			),
			'Community and Economic Development' => array(
				'4-H' => array(),
				'Economic Development' => array(
					'Entrepreneurship',
				),
				'Gardening' => array(
					'Master Gardeners',
				),
				'Small Business' => array(),
				'Technology Transfer' => array(),
				'WeatherNet' => array(),
			),
			'Events' => array(
				'Anniversary' => array(),
				'Athletic' => array(),
				'Camp' => array(),
				'Concert' => array(),
				'Conference' => array(),
				'Cultural' => array(),
				'Deadline' => array(),
				'Dedication and Naming' => array(
					'Building',
					'College / School / Program',
				),
				'Exhibit' => array(),
				'Fair and Festival' => array(),
				'Field Day' => array(),
				'Film' => array(),
				'Groundbreaking' => array(),
				'Guest Speaker' => array(),
				'Lecture' => array(),
				'Meeting' => array(),
				'Performance' => array(),
				'Reception' => array(),
				'Recognition' => array(),
				'Recreation / Wellness' => array(),
				'Seminar' => array(),
				'Student Event' => array(),
				'Workshop' => array(),
			),
			'Faculty, Staff' => array(
				'Awards, Employee' => array(),
				'Faculty' => array(
					'Faculty Senate',
				),
				'Obituaries' => array(),
				'Retirement' => array(),
				'Staff' => array(),
			),
			'Philanthropy' => array(
				'Fundraising News' => array(
					'Foundation and Fundraising Events',
					'Fundraising Updates',
					'Gift Announcements',
					'Volunteer News',
				),
				'Impact (Private Support, Volunteers)' => array(
					'Alumni Giving',
					'Faculty and Staff Giving',
					'Friends and Organizations Giving',
					'Gifts for Faculty and Research',
					'Meet Our Donors',
					'Scholarships in Action',
					'Student Philanthropy',
				),
			),
			'Research' => array(
				'Graduate Research' => array(),
				'Grants' => array(
					'Corporate Grants',
					'Federal Grants',
					'State Grants',
				),
				'Intellectual Property' => array(),
				'Postdoctoral Research' => array(),
				'Research Fellowships' => array(),
				'Undergraduate Research' => array(),
			),
			'Resources and Offices' => array(
				'Attorney General' => array(),
				'Board of Regents' => array(),
				'Buildings and Grounds' => array(
					'Campus Planning',
					'Construction',
					'Facilities Management',
				),
				'Business and Finances' => array(
					'Budget',
					'Real Estate',
					'Travel',
				),
				'Communication, University' => array(
					'Marketing',
					'Media Relations',
					'Publishing',
					'Social Media',
					'Web Communication',
				),
				'Community Engagement' => array(),
				'Deans and Executives' => array(),
				'Government Relations' => array(),
				'Health and Wellness Services' => array(),
				'History of University' => array(),
				'Human Resources' => array(
					'Employee Benefits',
					'Jobs',
					'Equal Employment Opportunities',
					'Payroll',
					'Professional Development',
					'Retirees',
					'Training',
				),
				'Information Technology' => array(
					'Faculty and Staff',
					'Help Desk',
					'Maintenance',
					'Network',
					'Security',
					'Services',
					'Students and Parents',
				),
				'Libraries' => array(
					'Archives / Special Collections',
					'Books',
					'Collections',
					'Reference',
				),
				'Museums' => array(
					'Anthropology Museum',
					'Art Museum',
					'Conner Museum',
					'Entomology Museum',
					'Geology Museum',
					'Herbarium Museum',
					'Veterinary Museum',
				),
				'President' => array(),
				'Safety' => array(
					'Campus Police',
					'Emergency Management',
					'Environmental Safety',
				),
				'Transportation' => array(
					'Bicycle',
					'Bus',
					'Parking',
				),
				'University Statistics' => array(
					'Employment',
					'Enrollment Statistics',
					'Funding',
					'Graduation',
					'Private Support',
				),
			),
			'Sports' => array(
				'Sports Administration' => array(),
				'Club' => array(),
				'Intercollegiate' => array(
					'Baseball',
					'Basketball',
					'Cross Country',
					'Football',
					'Golf',
					'Rowing',
					'Soccer',
					'Swimming',
					'Tennis',
					'Track and Field',
					'Volleyball',
				),
				'Intramural' => array(
					'Outdoor Recreation',
				),
			),
			'Students' => array(
				'Admissions' => array(),
				'Advising' => array(
					'Academic',
					'Career',
				),
				'Awards / Honors' => array(),
				'Bookstore' => array(),
				'Career services' => array(),
				'Civic Engagement / Community Outreach' => array(),
				'Clubs / Organizations' => array(),
				'Counseling' => array(),
				'Dining Services' => array(),
				'Diversity' => array(),
				'Enrollment' => array(),
				'Fellowships' => array(),
				'Financial Aid' => array(),
				'Health Wellness' => array(
					'Health and Wellness Services',
					'University Recreation',
				),
				'Honors College' => array(),
				'International' => array(),
				'Internships' => array(),
				'Living Communities' => array(
					'Fraternities',
					'Independent Living',
					'Residence Halls',
					'Sororities',
				),
				'Recruitment / Retention' => array(),
				'Registrar' => array(),
				'Student Ambassadors' => array(),
				'Student Government' => array(),
			),
		);

		return $categories;
	}
}
new WSUWP_University_Taxonomies();