<?php
namespace WatsonConv;
// Update functions for Watson Assistant Plugin
// Functions should be reappliable. Every function should be able to be applied 
// two or more times with the same effect without breaking anything. If
// something is broken, function should fix it. If something works okay, it
// shouldn't be touched, just checked.

class UpdateFunctions {
	// Initialize update functionality options
	public static function watsonconv_000_initialize() {
		// Adding options for storing last applied and last failed updates
		add_option('watsonconv_last_applied_update', 'none');
		// Checking options existence
		// Setting flag for options existence
		$options_exist = true;
		// Creting event id for logging
		$event_id = uniqid();
		// Checking if option for last applied update was created
		if(!get_option('watsonconv_last_applied_update')) {
			$options_exist = false;
			$error_msg = "Failed to create an option for update process";
			$error_details = "Option name: watsonconv_last_applied_update";
			Logger::log_message($error_msg, $error_details, $event_id);
		}
		return $options_exist;
	}

	// Build database for 0.8.4
	public static function watsonconv_084_create_database() {
		// Global Wordpress database object
		global $wpdb;
		// Wordpress collation
		$collate = '';
		if ( $wpdb->has_cap('collation') ) {
			$collate = $wpdb->get_charset_collate();
		}
		// Wordpress prefix
		$prefix = $wpdb->prefix;
		// Plugin storage prefix
		$plugin_prefix = Storage::$storage_prefix;
		// Full prefix (wordpress + plugin)
		$full_prefix = $prefix . $plugin_prefix;
		// Array with table names and fields
		$tables_array = array(
			'sessions' => array(
				'id binary(16) NOT NULL',
				's_created timestamp DEFAULT CURRENT_TIMESTAMP',
				'PRIMARY KEY  (id)'
			),
			'requests' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'a_session_id binary(16) NOT NULL',
				's_created timestamp DEFAULT CURRENT_TIMESTAMP',
				'o_user_input_id integer(64)',
				'o_input_context_id integer(64)',
				'o_output_context_id integer(64)',
				'p_debug_output text',
				'p_user_defined text',
				'PRIMARY KEY  (id)'
			),
			'user_inputs' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'p_message_type enum("text")',
				'p_text text NOT NULL',
				'p_debug boolean',
				'p_restart boolean',
				'p_alternate_intents boolean',
				'p_return_context boolean',
				'PRIMARY KEY  (id)'
			),
			'watson_outputs' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'a_request_id integer(64) UNSIGNED NOT NULL',
				'p_response_type enum("text","pause","image","option","connect_to_agent","suggestion") NOT NULL',
				'p_text varchar(2048)',
				'p_time integer(16)',
				'p_typing boolean',
				'p_source varchar(2048)',
				'p_title varchar(2048)',
				'p_description varchar(2048)',
				'p_preference enum("dropdown", "button")',
				'p_options text',
				'p_message_to_human_agent varchar(2048)',
				'p_topic varchar(2048)',
				'p_suggestions text',
				'PRIMARY KEY  (id)'
			),
			'contexts' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'p_global text',
				'p_skills text',
				'PRIMARY KEY  (id)'
			),
			'intents' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'p_intent varchar(512) NOT NULL',
				'p_confidence double(64, 30) NOT NULL',
				'PRIMARY KEY  (id)'
			),
			'entities' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'p_entity varchar(512) NOT NULL',
				'p_location text NOT NULL',
				'p_value varchar(1024) NOT NULL',
				'p_confidence double(64, 30)',
				'p_metadata text',
				'p_groups text',
				'PRIMARY KEY  (id)'
			),
			'input_intents' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'a_request_id integer(64) UNSIGNED NOT NULL',
				'o_intent_id integer(64) UNSIGNED NOT NULL',
				'PRIMARY KEY  (id)'
			),
			'output_intents' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'a_request_id integer(64) UNSIGNED NOT NULL',
				'o_intent_id integer(64) UNSIGNED NOT NULL',
				'PRIMARY KEY  (id)'
			),
			'input_entities' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'a_request_id integer(64) UNSIGNED NOT NULL',
				'o_entity_id integer(64) UNSIGNED NOT NULL',
				'PRIMARY KEY  (id)'
			),
			'output_entities' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'a_request_id integer(64) UNSIGNED NOT NULL',
				'o_entity_id integer(64) UNSIGNED NOT NULL',
				'PRIMARY KEY  (id)'
			),
			'actions' => array(
				'id integer(64) UNSIGNED NOT NULL AUTO_INCREMENT',
				'a_request_id integer(64) UNSIGNED NOT NULL',
				'p_name varchar(512) NOT NULL',
				'p_result_variable varchar(512) NOT NULL',
				'p_type enum("client", "server", "web-action", "cloud-function")',
				'p_parameters text',
				'p_credentials varchar(5120)',
				'PRIMARY KEY  (id)'
			)
		);
		// Empty array for sql expressions
		$sql_expressions = array();
		// Generating sql expressions
		foreach($tables_array as $table_name => $table_fields) {
			// Table name
			$table_start = "CREATE TABLE {$full_prefix}{$table_name} (\n";
			// All the fields together
			$fields = implode(",\n\t", $table_fields);
			// Table end with collation
			$table_end = "\n) {$collate};";
			// Full expression for table creation
			$sql_expression = $table_start . $fields . $table_end;
			// Adding it to the array pf sql expressions
			array_push($sql_expressions, $sql_expression);
		}

		// Wordpress file with dbDelta
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		// Iterating through all the generated expressions and calling db
		foreach($sql_expressions as $sql_expression) {
			dbDelta($sql_expression);
		}
		// Registering tables in Storage class
		Storage::init();
		// Getting the list of added tables as an array
		$table_names = array_keys($tables_array);
		// Flag for tables existence
		$tables_present = true;
		// Generating event id for logging
		$event_id = uniqid();
		// Iterating through tables list and checking their existence
		foreach ($table_names as $table_name) {
			$current_table_exists = Storage::table_exists($table_name);
			if(!$current_table_exists) {
				// Logging error
				$error_msg = "Chat history table creation failure";
				$error_details = "Creation of {$table_name} was unsuccessful";
				Logger:log_message($error_msg, $error_details, $event_id);
				// Setting success flag to false
				$tables_present = false;
			}
		}
		// Returning status
		return $tables_present;
	}

	// Updating tables to use TEXT data type instead of JSON
	//  //  //  //  //  //  //  //  //  //
	// TASK_RUNNER_QUEUE
	// task_runner_queue.p_data
	public static function watsonconv_0810_dejsonify_task_runner_queue_p_data() {
		return \WatsonConv\UpdateFunctions::dejsonify("task_runner_queue", "p_data");
	}
	// REQUESTS
	// requests.p_debug_output
	public static function watsonconv_0810_dejsonify_requests_p_debug_output() {
		return \WatsonConv\UpdateFunctions::dejsonify("requests", "p_debug_output");
	}
	// requests.p_user_defined
	public static function watsonconv_0810_dejsonify_requests_p_user_defined() {
		return \WatsonConv\UpdateFunctions::dejsonify("requests", "p_user_defined");
	}
	// WATSON_OUPUTS
	// watson_outputs.p_options
	public static function watsonconv_0810_dejsonify_watson_outputs_p_options() {
		return \WatsonConv\UpdateFunctions::dejsonify("watson_outputs", "p_options");
	}
	// watson_outputs.p_suggestions
	public static function watsonconv_0810_dejsonify_watson_outputs_p_suggestions() {
		return \WatsonConv\UpdateFunctions::dejsonify("watson_outputs", "p_suggestions");
	}
	// CONTEXTS
	// contexts.p_global
	public static function watsonconv_0810_dejsonify_contexts_p_global() {
		return \WatsonConv\UpdateFunctions::dejsonify("contexts", "p_global");
	}
	// contexts.p_skills
	public static function watsonconv_0810_dejsonify_contexts_p_skills() {
		return \WatsonConv\UpdateFunctions::dejsonify("contexts", "p_skills");
	}
	// ENTITIES
	// entities.p_location
	public static function watsonconv_0810_dejsonify_entities_p_location() {
		return \WatsonConv\UpdateFunctions::dejsonify("entities", "p_location");
	}
	// entities.p_metadata
	public static function watsonconv_0810_dejsonify_entities_p_metadata() {
		return \WatsonConv\UpdateFunctions::dejsonify("entities", "p_metadata");
	}
	// entities.p_groups
	public static function watsonconv_0810_dejsonify_entities_p_groups() {
		return \WatsonConv\UpdateFunctions::dejsonify("entities", "p_groups");
	}
	// ACTIONS
	// actions.p_parameters
	public static function watsonconv_0810_dejsonify_actions_p_parameters() {
		return \WatsonConv\UpdateFunctions::dejsonify("actions", "p_parameters");
	}


	// Converting specific field of a specific table to TEXT data type
	private static function dejsonify($table_name, $field_name) {
		// Global Wordpress database object
		global $wpdb;
		// Getting full table name
		$table_name = \WatsonConv\Storage::get_full_table_name($table_name);
		// Query to change JSON data type to TEXT
		$expression = "ALTER TABLE {$table_name} MODIFY COLUMN {$field_name} TEXT";
		// Executing query and converting tables
		$result = $wpdb->query($expression);

		if($result === false) {
			Logger::log_message("JSON to TEXT table conversion failure", "Field: {$table_name}.{$field_name}");
			return false;
		}
		else {
			return true;
		}
	}
}
