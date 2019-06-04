<?php
// Database related functionality
namespace WatsonConv;

class Storage{
    // Plugin table name prefix
    // IT IS NOT WORDPRESS PREFIX
    // full table name is:
    // wordpress prefix + plugin prefix + table name
    public static $storage_prefix = "watsonconv_";
    // List of tables that exist in database
    public static $tables_list;
    // Description of all the fields in schema, associated properties
    // and their types
    public static $schema_description = array(
        "sessions" => array(
            "id" => array("property" => "id", "type" => "uuid"),
            "s_created" => array("property" => "_db_created", "type" => "timestamp")
        ),
        "requests" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "a_session_id" => array("property" => "session_id", "type" => "uuid"),
            "o_user_input_id" => array("property" => "user_input_id", "type" => "integer"),
            "o_input_context_id" => array("property" => "input_context_id", "type" => "integer"),
            "o_output_context_id" => array("property" => "output_context_id", "type" => "integer"),
            "p_debug_output" => array("property" => "debug_output", "type" => "json"),
            "p_user_defined" => array("property" => "user_defined", "type" => "json"),
            "s_created" => array("property" => "_db_created", "type" => "timestamp")
        ),
        "user_inputs" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "p_message_type" => array("property" => "message_type", "type" => "string"),
            "p_text" => array("property" => "text", "type" => "string"),
            "p_debug" => array("property" => "debug", "type" => "boolean"),
            "p_restart" => array("property" => "restart", "type" => "boolean"),
            "p_alternate_intents" => array("property" => "alternate_intents", "type" => "boolean"),
            "p_return_context" => array("property" => "return_context", "type" => "boolean")
        ),
        "watson_outputs" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "a_request_id" => array("property" => "request_id", "type" => "integer"),
            "p_response_type" => array("property" => "response_type", "type" => "string"),
            "p_text" => array("property" => "text", "type" => "string"),
            "p_time" => array("property" => "time", "type" => "integer"),
            "p_typing" => array("property" => "typing", "type" => "boolean"),
            "p_source" => array("property" => "source", "type" => "string"),
            "p_title" => array("property" => "title", "type" => "string"),
            "p_description" => array("property" => "description", "type" => "string"),
            "p_preference" => array("property" => "preference", "type" => "string"),
            "p_options" => array("property" => "options", "type" => "json"),
            "p_message_to_human_agent" => array("property" => "message_to_human_agent", "type" => "string"),
            "p_topic" => array("property" => "topic", "type" => "string"),
            "p_suggestions" => array("property" => "suggestions", "type" => "json")
        ),
        "contexts" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "p_global" => array("property" => "global", "type" => "json"),
            "p_skills" => array("property" => "skills", "type" => "json")
        ),
        "intents" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "p_intent" => array("property" => "intent", "type" => "string"),
            "p_confidence" => array("property" => "confidence", "type" => "float")
        ),
        "entities" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "p_entity" => array("property" => "entity", "type" => "string"),
            "p_location" => array("property" => "location", "type" => "json"),
            "p_value" => array("property" => "value", "type" => "string"),
            "p_confidence" => array("property" => "confidence", "type" => "float"),
            "p_metadata" => array("property" => "metadata", "type" => "json"),
            "p_groups" => array("property" => "groups", "type" => "json")
        ),
        "actions" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "a_request_id" => array("property" => "request_id", "type" => "integer"),
            "p_name" => array("property" => "name", "type" => "string"),
            "p_result_variable" => array("property" => "result_variable", "type" => "string"),
            "p_parameters" => array("property" => "parameters", "type" => "json"),
            "p_credentials" => array("property" => "credentials", "type" => "string")
        ),
        "input_entities" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "a_request_id" => array("property" => "request_id", "type" => "integer"),
            "o_entity_id" => array("property" => "entity_id", "type" => "integer")
        ),
        "output_entities" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "a_request_id" => array("property" => "request_id", "type" => "integer"),
            "o_entity_id" => array("property" => "entity_id", "type" => "integer")
        ),
        "input_intents" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "a_request_id" => array("property" => "request_id", "type" => "integer"),
            "o_intent_id" => array("property" => "intent_id", "type" => "integer")
        ),
        "output_intents" => array(
            "id" => array("property" => "_db_id", "type" => "integer"),
            "a_request_id" => array("property" => "request_id", "type" => "integer"),
            "o_intent_id" => array("property" => "intent_id", "type" => "integer")
        )
    );

    public static $callbacks = array(
        "sessions" => array(
            "pre_delete" => "sessions_pre_delete"
        ),
        "requests" => array(
            "pre_insert" => "requests_pre_insert",
            "post_insert" => "requests_post_insert",
            "pre_delete" => "requests_pre_delete"
        ),
        "user_inputs" => array(
            "pre_insert" => "user_inputs_pre_insert"
        ),
        "input_intents" => array(
            "pre_insert" => "intents_junction_pre_insert",
            "pre_delete" => "intents_junction_pre_delete"
        ),
        "output_intents" => array(
            "pre_insert" => "intents_junction_pre_insert",
            "pre_delete" => "intents_junction_pre_delete"
        ),
        "input_entities" => array(
            "pre_insert" => "entities_junction_pre_insert",
            "pre_delete" => "entities_junction_pre_delete"
        ),
        "output_entities" => array(
            "pre_insert" => "entities_junction_pre_insert",
            "pre_delete" => "entities_junction_pre_delete"
        )
    );

    // Property for storing MySQL version
    private static $mysql_version = "";

    // Initialize Storage functionality
    public static function init() {
        // Global Wordpress database object
        global $wpdb;
        // Getting list of all existing tables
        Storage::$tables_list = $wpdb->get_col("SHOW TABLES", 0);
    }

    // Getting MySQL version
    public static function get_mysql_version() {
        // Getting version from class static property in case it was previously
        // retrieved. Doing this to avoid excess requests for getting constant
        // value.
        $result = self::$mysql_version;
        // If $result is an empty string
        if(empty($result)) {
            global $wpdb;
            $result = $wpdb->get_var("SELECT VERSION()", 0, 0);
        }
        // If error happened while retrieving MySQL version from database,
        // logging it and saving database version as an empty string
        if($result === NULL) {
            Logger::log_message("Error retrieving MySQL version");
            $result = "";
        }
        self::$mysql_version = $result;
        return $result;
    }

    // Checking if table exists
    public static function table_exists($table_name) {
        // Getting full table name
        $full_table_name = Storage::get_full_table_name($table_name);
        // Iterating through all the names to find matching one
        foreach(Storage::$tables_list as $existing_table) {
            if($existing_table == $full_table_name) {
                return true;
            }
        }
        // If we didn't find one, returning false
        return false;
    }

    // Getting field type
    public static function get_field_type($table_name, $field_name) {
        $field_type = Storage::$schema_description[$table_name][$field_name]["type"];
        return $field_type;
    }

    // Getting data property associated with field
    public static function get_field_property($table_name, $field_name) {
        $field_property = Storage::$schema_description[$table_name][$field_name]["property"];
        return $field_property;
    }

    // Getting value from associative array based on property path
    // $data - associative array with needed value
    // $path - array with "path" parts in $data array
    // Example:
    // array("path", "to", "property") in $path
    // means that our value is in
    // $data["path"]["to"]["property"]
    public static function dig_value($data, $path) {
        // Iterating through path parts and getting property
        foreach($path as $path_part) {
            // Checking if array element at this path part exists
            // If yes, assigning it to our value and checking next path part
            // If no, returning NULL
            if( isset($data[$path_part]) ) {
                $data = $data[$path_part];
            }
            else {
                return NULL;
            }
        }
        // If loop ended, it means that we've found our target value.
        // Returning it
        return $data;
    }

    // Rearranging values
    private static function rearrange_values($data, $rearrangements) {
        // Iterating through rearrangements array
        foreach($rearrangements as $target_path => $source_path) {
            // Digging values from source path and assigning them to
            // corresponding indices in $data
            $data[$target_path] = self::dig_value($data, $source_path);
        }
        // Returning processed data
        return $data;
    }

    // Adding intent to database
    public static function insert($table_name, $data) {
        // Getting description for the fields of the table
        $fields_description = self::$schema_description[$table_name];
        // Calling pre-INSERT callback
        $data = self::processing_callback("pre_insert", $table_name, $data);
        // Processing data for passing to database
        $fields_data = self::fill_fields($fields_description, $data);
        // Performing query and getting returned id from database
        $returned_id = self::perform_insert($table_name, $fields_data);
        // Writing real id into associated property
        // Getting id type
        $id_type = $fields_description["id"]["type"];
        // Getting id property
        $id_property = $fields_description["id"]["property"];
        // If id type is "uuid", leaving property that already was here
        // If id type is integer, assigning $returned_id to id's property
        if($id_type == "integer") {
            $data[$id_property] = $returned_id;
        }
        // Calling post-INSERT callback
        $data = self::processing_callback("post_insert", $table_name, $data);
        // Returning record id
        return $data[$id_property];
    }

    // Processing callbacks
    private static function processing_callback($callback_type, $table_name, $data) {
        // Constructing path to callback function name in Storage::$callbacks
        // Example:
        // For table "requests" and "pre_insert" callback type it will be
        // array("requests", "pre_insert")
        // It will refer to 
        // Storage::$callbacks["requests"]["pre_insert"]
        $callback_path = array($table_name, $callback_type);
        // Getting function name
        $function_name = self::dig_value(self::$callbacks, $callback_path);
        // If callback doesn't exist, exiting and returning unchanged data
        if(!isset($function_name)) {
            return $data;
        }
        $class_name = __NAMESPACE__ . "\\Storage";
        // Callable "pointer" to function
        $callable = array($class_name, $function_name);
        // Processing $data through function
        $data = call_user_func($callable, $data);

        // Returning processed $data back
        return $data;
    }

    // Pre-INSERT processing of data for user_inputs table
    private static function user_inputs_pre_insert($data) {
        // List of values to rearrange
        // Keys: where to put them in the "root" array
        // Values: paths where to find them
        // This array is created for additional eloquence and to avoid processing
        // values we don't need in case they will exist
        $rearrangements = array(
            "debug" => array("options", "debug"),
            "restart" => array("options", "restart"),
            "alternate_intents" => array("options", "alternate_intents"),
            "return_context" => array("options", "return_context")
        );
        // Rearranging them
        $data = self::rearrange_values($data, $rearrangements);
        // Returning modified $data
        return $data;
    }

    // Pre-INSERT processing of data for "input_intents" and "output_intents"
    private static function intents_junction_pre_insert($data) {
        // Writing intent to database and getting its record id
        $data["intent_id"] = Storage::insert("intents", $data);
        // Returning data back
        return $data;
    }

    // Pre-INSERT processing of data for "input_entities" and "output_entities"
    private static function entities_junction_pre_insert($data) {
        // Writing entity to database and getting its record id
        $data["entity_id"] = Storage::insert("entities", $data);
        // Returning data back
        return $data;
    }

    // Pre-INSERT processing of data for "requests" table
    private static function requests_pre_insert($data) {
        // List of values to rearrange
        // Keys: where to put them in the "root" array
        // Values: paths where to find them
        $rearrangements = array(
            "debug_output" => array("watson_response", "output", "debug"),
            "user_defined" => array("watson_response", "output", "user_defined")
        );
        // Rearranging them
        $data = self::rearrange_values($data, $rearrangements);
        // List of child objects
        // Keys: where to store that object's id in "root" array
        // Values: path to source value and its table name
        $child_objects = array(
            "user_input_id" => array(
                "path" => array("user_request", "input"),
                "table" => "user_inputs"
            ),
            "input_context_id" => array(
                "path" => array("user_request", "context"),
                "table" => "contexts"
            ),
            "output_context_id" => array(
                "path" => array("watson_response", "context"),
                "table" => "contexts"
            )
        );
        // Writing them to database
        foreach($child_objects as $target_path => $child_details) {
            // Path to child object's data ib $data array
            $child_path = $child_details["path"];
            // Child table
            $child_table = $child_details["table"];
            // Getting child object value
            $child_data = self::dig_value($data, $child_path);
            // If child value is NULL, continue
            if(!isset($child_data)) {
                continue;
            }
            // If child value exists, write it into database
            $data[$target_path] = Storage::insert($child_table, $child_data);
        }
        // Returning processed data
        return $data;
    }

    // Post-INSERT processing of data for "requests" table
    private static function requests_post_insert($data) {
        // Property in objects in child arrays to store request id
        $child_property = "request_id";
        // List of child arrays
        // Key: child table
        // Value: path to child array value in $data
        $child_arrays = array(
            "watson_outputs" => array("watson_response", "output", "generic"),
            "actions" => array("watson_response", "output", "actions"),
            "input_entities" => array("user_request", "input", "entities"),
            "input_intents" => array("user_request", "input", "intents"),
            "output_entities" => array("watson_response", "output", "entities"),
            "output_intents" => array("watson_response", "output", "intents")
        );
        // Processing every child array
        foreach($child_arrays as $child_table => $child_path) {
            // Getting child data based on path
            $child_data = self::dig_value($data, $child_path);
            // If there's no child data, processing next array
            if(!isset($child_data)) {
                continue;
            }
            // Writing every element of child array to database
            foreach($child_data as $element_data) {
                // Supplying every child array element with parent id
                $element_data[$child_property] = $data["_db_id"];
                // Writing it to database
                $element_id = Storage::insert($child_table, $element_data);
            }
        }
        // Returning processed data
        return $data;
    }

    // Filling fields with data and formats
    public static function fill_fields($fields_description, $data) {
        $result = array();
        // Iterating through fields and adding them to field data
        foreach($fields_description as $field_name => $field_description) {
            // If property value is empty, skipping field
            if(!isset($data[$field_description['property']])) {
                continue;
            }
            else {
                // Getting field type
                $field_type = $field_description["type"];
                // Getting property value
                $property_value = $data[$field_description["property"]];
                // Adding value with format to result
                $result[$field_name] = self::format_field_for_db($property_value, $field_type);
            }
        }
        return $result;
    }

    // Perform INSERT
    public static function perform_insert($table, $fields_data) {
        // Checking if table exists
        if(!Storage::table_exists($table)) {
            Logger::error_with_args("INSERT into nonexistent table", $table);
            Install::reapply_all_updates();
            return NULL;
        }

        // WPDB global object
        global $wpdb;
        // Full table name (with wpdb and plugin prefixes)
        $full_table_name = self::get_full_table_name($table);

        // Values to write to database
        $values = array();
        // Query placeholders
        $formats = array();
        // Fields
        $fields = array();

        // Iterating through fields and filling arrays
        foreach($fields_data as $field_name => $field_data) {
            // If there isn't any value for field, skipping that field
            if(!isset($field_data)) {
                continue;
            }
            // Filling fields, formats and values arrays
            array_push($fields, $field_name);
            array_push($formats, $field_data["format"]);
            array_push($values, $field_data["value"]);
        }

        // Constructing unprepared query
        $fields_string = "( \n\t" . implode(",\n\t", $fields) . "\n)\n";
        $formats_string = "VALUES (\n\t" . implode(",\n\t", $formats) . "\n)\n";
        $intermediate_sql = "INSERT INTO {$full_table_name}{$fields_string}{$formats_string}";
        // Preparing query
        $prepared_sql = $wpdb->prepare($intermediate_sql, $values);
        // Executing request
        $query_result = $wpdb->query($prepared_sql);

        // Possible error handling
        if($query_result === false) {
            Logger::handle_wpdb_error("INSERT operation failure");
        }

        // Getting last inserted id
        $record_id = $wpdb->insert_id;
        return $record_id;
    }

    // Getting full table name
    public static function get_full_table_name($short_table_name) {
        // Wordpress database object
        global $wpdb;
        return $wpdb->prefix . Storage::$storage_prefix . $short_table_name;
    }

    // Prepare variable before serialization to JSON in DB
    public static function prepare_var_for_json_extract($data) {
        // Getting variable type
        $type = gettype($data);

        // If type is object, serializing it to json, then deserializing to 
        // array
        if($type == "object") {
            $data = json_decode(json_encode($data), true);
            $type = "array";
        }

        // If type is array, then sorting it by keys in ascending order
        // Then processing its values
        if($type == "array") {
            ksort($data);
            $class_name = __NAMESPACE__ . "\\Storage";
            $function_name = "prepare_var_for_json_extract";
            $callable = array($class_name, $function_name);
            $mapped_data = array_map($callable, $data);
            $data = $mapped_data;
        }

        return $data;
    }

    // Returning "format" and converted value for database request
    public static function format_field_for_db($src_value, $type) {
        // if value is not set, exiting;
        if(!isset($src_value)) {
            return NULL;
        }
        // Format dictionary for unprepared queries
        $format_dictionary = array(
            "integer" => "%d",
            "float" => "%f",
            "string" => "%s",
            "boolean" => "%d",
            "json" => "%s",
            "uuid" => "UNHEX(REPLACE(%s,'-',''))",
            "timestamp" => "FROM_UNIXTIME(%d)"
        );
        $format = $format_dictionary[$type];
        $target_value = NULL;

        // Converting values to target types
        if($type == "integer") {
            $target_value = (integer)$src_value;
        }
        else if($type == "float") {
            $target_value = (float)$src_value;
        }
        else if($type == "string") {
            $target_value = (string)$src_value;
        }
        else if($type == "boolean") {
            $target_value = (integer)$src_value;
        }
        else if($type == "json") {
            $target_value = json_encode(self::prepare_var_for_json_extract($src_value));
        }
        else if($type == "uuid") {
            $target_value = (string)$src_value;
        }
        else if($type == "timestamp") {
            $target_value = (integer)$src_value;
        }
        // Array with result
        $result = array(
            "format" => $format,
            "value" => $target_value
        );

        return $result;
    }

    // Select records by their id
    public static function select_by_id($table_name, $id_value, $fields_to_get = NULL) {
        // Passing arguments to more generic SELECT function
        $result = Storage::select_by_field($table_name, "id", $id_value, $fields_to_get);
        return $result;
    }

    // Get array of ids by field value
    public static function get_id_array($table_name, $field_name, $field_value) {
        // Array that contains names of fields we need
        $fields_to_get = array("id");
        // Passing arguments to more generic SELECT function
        // and getting result as associative array
        $select_result = Storage::select_by_field($table_name, $field_name, $field_value, $fields_to_get);
        // Array to store ids
        $id_array = array();
        // Iterating through result records and writing ids to array
        foreach($select_result as $record => $fields) {
            array_push($id_array, $fields["id"]);
        }
        // Returning array with ids
        return $id_array;
    }

    // Select records by their field value
    public static function select_by_field($table_name, $field_name, $field_value, $fields_to_get = NULL, $limit = NULL) {
        // Constructing array with formatted values for WHERE clause
        $where_array = array(
            Storage::where($table_name, $field_name, "=", $field_value)
        );
        // Data for SELECT
        $select_data = array(
            "where" => $where_array,
            "fields" => $fields_to_get,
            "limit" => $limit
        );
        // Passing arguments to more generic SELECT function
        $result = Storage::select($table_name, $select_data);
        return $result;
    }

    // Generic SELECT
    public static function select($table_name, $data = array()) {
        // Fields list
        // If $fields_to_get isn't set getting all of them
        if(!isset($data["fields"])) {
            $data["fields"] = array_keys(Storage::$schema_description[$table_name]);
        }
        // Getting prepared fields array
        $prepared_fields = Storage::prepare_select_fields($table_name, $data["fields"]);
        // Getting data for constructing WHERE clause
        if(!isset($data["where"])) {
            $data["where"] = NULL;
        }
        $prepared_where = Storage::prepare_where_clause($data["where"]);
        // Getting ordering
        if(!isset($data["order"])) {
            $data["order"] = NULL;
        }
        $prepared_order = Storage::prepare_order_clause($data["order"]);
        // Getting limit
        $prepared_limit = NULL;
        if(isset($data["limit"])) {
            $prepared_limit = (integer)$data["limit"];
        }
        // Getting offset
        $prepared_offset = NULL;
        if(isset($data["offset"])) {
            $prepared_offset = (integer)$data["offset"];
        }

        // Performing SELECT query based on prepared values
        $result = Storage::perform_select($table_name, $prepared_where, $prepared_fields, $prepared_order, $prepared_limit, $prepared_offset);
        return $result;
    }

    // Performing SELECT
    public static function perform_select($table, $where, $fields, $order, $limit, $offset) {
        // Checking if table exists
        if(!Storage::table_exists($table)) {
            Logger::error_with_args("SELECT from nonexistent table", $table);
            Install::reapply_all_updates();
            return NULL;
        }

        // Query construction
        // Flag that determines if query needs $wpdb->prepare
        $needs_wpdb_prepare = false;
        // Expression with fields list
        $fields_expression = "SELECT\n\t" . implode(",\n\t", $fields) . "\n";
        // Getting full table name with all prefixes applied
        $full_table_name = Storage::get_full_table_name($table);
        $from_expression = "FROM {$full_table_name}\n";
        // Empty WHERE expression and WHERE values array
        $where_expression = "";
        $where_values = array();
        // If there is WHERE expression, using it and associated values
        if(isset($where["expression"])) {
            $where_expression = $where["expression"];
            $where_values = $where["values"];
            // Marking that we need $wpdb->prepare
            $needs_wpdb_prepare = true;
        }

        // Empty ORDER expression
        $order_expression = "";
        if(isset($order)) {
            $order_expression = $order;
        }

        // Empty LIMIT expression
        $limit_expression = "";
        // If $limit isn't empty, building expression
        if(!empty($limit)) {
            $limit = (integer)$limit;
            $limit_expression = "\nLIMIT {$limit}";
        }


        // Empty OFFSET expression
        $offset_expression = "";
        if(!empty($offset)) {
            $offset = (integer)$offset;
            $offset_expression = "\nOFFSET {$offset}";
        }

        // Full SELECT query
        $full_expression = "{$fields_expression}{$from_expression}{$where_expression}{$order_expression}{$limit_expression}{$offset_expression}";

        // Wordpress wpdb object
        global $wpdb;
        // Running $wpdb->prepare if needed
        if($needs_wpdb_prepare) {
            $full_expression = $wpdb->prepare($full_expression, $where_values);
        }


        // Performing request and getting result
        $raw_result = $wpdb->get_results($full_expression, ARRAY_A);

        // Possible error handling
        if($raw_result === false) {
            Logger::handle_wpdb_error("SELECT operation failure");
        }

        // Processing result
        $result = Storage::process_select_result($table, $raw_result);

        return $result;
    }

    // Preparing single ORDER BY condition
    public static function order($table_name, $field_name, $order) {
        // TODO: matching table's fields description
        $result = array(
            "field" => $field_name,
            "order" => $order
        );

        return $result;
    }

    // Preparing full ORDER BY condition
    public static function prepare_order_clause($order_array) {
        // If there's no elements in $order_array, returning NULL
        if(empty($order_array)) {
            return NULL;
        }
        // Array for single conditions, parts of complete one
        $expression_parts = array();
        // Iterating through the array of unprepared conditions
        foreach($order_array as $condition) {
            $field = $condition["field"];
            $order = $condition["order"];
            $prepared_condition = "{$field} {$order}";
            array_push($expression_parts, $prepared_condition);
        }

        // Constructing full ORDER BY expression
        $order_expression = "ORDER BY\n\t" . implode(",\n\t", $expression_parts) . "\n";
        return $order_expression;
    }

    // Prepares WHERE condition for single field
    public static function where($table_name, $field_name, $operator, $value) {
        // Field type
        $field_type = Storage::get_field_type($table_name, $field_name);
        // Variable for WHERE expression with placeholder instead of value
        $expression = NULL;
        // Getting preliminary values - formatted field
        $formatted_value = Storage::format_field_for_db($value, $field_type);
        // Getting placeholder and converted value
        $placeholder = $formatted_value["format"];
        $value = $formatted_value["value"];

        // Building WHERE expression for single field
        $where_expression = "{$field_name} {$operator} {$placeholder}";
        // Result array
        $result = array(
            "expression" => $where_expression,
            "value" => $value
        );

        return $result;
    }

    // Prepare WHERE clause
    public static function prepare_where_clause($where_array) {
        // If there's no elements in $where_array, returning NULL
        if(empty($where_array)) {
            return NULL;
        }
        // Array for single conditions, parts of complete one
        $expression_parts = array();
        // Array for values
        $values = array();
        // If there are vealues to process, iterating through them
        if(!empty($where_array)) {
            foreach($where_array as $condition) {
                array_push($expression_parts, $condition["expression"]);
                array_push($values, $condition["value"]);
            }
        }
        // Constructing full WHERE expression
        $where_expression = "WHERE\n\t" . implode("\n\tAND\n\t", $expression_parts) . "\n";
        // Array with result. 
        // In case of absence of input values, it will contain two empty arrays
        $result = array(
            // "Parts" of WHERE expression string
            "expression" => $where_expression,
            // Values array
            "values" => $values
        );
        return $result;
    }

    // Prepare fields for SELECT query
    private static function prepare_select_fields($table_name, $fields_to_get) {
        // Empty array for prepared fields
        $prepared_fields = array();
        // Iterating through fields and prepairing them according to their type
        foreach($fields_to_get as $field_name) {
            // Field type
            $field_type = Storage::get_field_type($table_name, $field_name);
            // By default, field expression is same as field name
            $field_expression = $field_name;
            // Special treatment for "uuid" and "timestamp" types
            if($field_type == "uuid") {
                $field_expression = "HEX({$field_name}) AS {$field_name}";
            }
            else if($field_type == "timestamp") {
                $field_expression = "UNIX_TIMESTAMP({$field_name}) AS {$field_name}";
            }

            array_push($prepared_fields, $field_expression);
        }
        // Returning an array of prepared fields
        return $prepared_fields;
    }

    // Processing raw result from SELECT query
    public static function process_select_result($table_name, $raw_result) {
        // Array for processed result elements
        $result = array();
        // Iterating through result records to format them
        foreach($raw_result as $raw_record) {
            // Empty array for record
            $processed_record = array();
            // Iterating through fields and formatting them
            foreach($raw_record as $field_name => $field_value) {
                // If field has no value, skipping it
                if(!isset($field_value)) {
                    continue;
                }
                // Getting field type
                $field_type = Storage::get_field_type($table_name, $field_name);
                // Variable for storing processed value
                $target_value = NULL;

                if($field_type == "integer") {
                    $target_value = (integer)$field_value;
                }
                else if($field_type == "float") {
                    $target_value = (float)$field_value;
                }
                else if($field_type == "boolean") {
                    $integer_representation = (integer)$field_value;
                    $target_value = (boolean)$field_value;
                }
                else if($field_type == "string") {
                    $target_value = (string)$field_value;
                }
                else if($field_type == "uuid") {
                    $target_value = Storage::format_uuid($field_value);
                }
                else if($field_type == "json") {
                    $target_value = json_decode($field_value, true);
                }
                else if($field_type == "timestamp") {
                    $target_value = (integer)$field_value;
                }

                // Writing converted value to record array
                $processed_record[$field_name] = $target_value;
            }
            // Adding processed record to result array
            array_push($result, $processed_record); 
        }
        // Returning array with processed records
        return $result;
    }

    // Formatting hexadecimal string as UUID
    public static function format_uuid($raw_uuid) {
        // Converting to lowercase
        $lowercase_uuid = strtolower($raw_uuid);
        // Splitting into chunks as UUID is divided by dashes
        // Sizes: 8-4-4-4-12
        $uuid_chunks = array(
            // Positions 0-7
            substr($lowercase_uuid, 0, 8),
            // Positions 8-11
            substr($lowercase_uuid, 8, 4),
            // Positions 12-15
            substr($lowercase_uuid, 12, 4),
            // Positions 16-19
            substr($lowercase_uuid, 16, 4),
            // Positions 20-31
            substr($lowercase_uuid, 20, 12)
        );
        // Join chunks with added dashes
        $result = implode("-", $uuid_chunks);
        return $result;
    }

    // Delete records by their id
    public static function delete_by_id($table_name, $id_value) {
        // Passing arguments to more generic DELETE function
        $result = Storage::delete_by_field($table_name, "id", $id_value);
        return $result;
    }

    // Delete records by their field value
    public static function delete_by_field($table_name, $field_name, $field_value) {
        // Constructing array with formatted values for WHERE clause
        $where_array = array(
            Storage::where($table_name, $field_name, "=", $field_value)
        );
        // Passing arguments to more generic DELETE function
        $result = Storage::delete($table_name, array("where" => $where_array));
        return $result;
    }

    // Generic DELETE
    public static function delete($table_name, $data = array()) {
        // Getting data for constructing WHERE clause
        if(!isset($data["where"])) {
            $data["where"] = NULL;
        }
        $prepared_where = Storage::prepare_where_clause($data["where"]);

        // Getting ordering
        if(!isset($data["order"])) {
            $data["order"] = NULL;
        }
        $prepared_order = Storage::prepare_order_clause($data["order"]);

        // Getting limit
        $prepared_limit = NULL;
        if(isset($data["limit"])) {
            $prepared_limit = (integer)$data["limit"];
        }

        // Performing DELETE query based on prepared values
        $result = Storage::perform_delete($table_name, $prepared_where, $prepared_order, $prepared_limit);
        return $result;
    }

    // Performing DELETE
    public static function perform_delete($table, $where, $order, $limit = NULL) {
        // Checking if table exists
        if(!Storage::table_exists($table)) {
            Logger::error_with_args("DELETE from nonexistent table", $table);
            Install::reapply_all_updates();
            return NULL;
        }

        // Query construction
        // Getting full table name based on short table name
        $full_table_name = Storage::get_full_table_name($table);
        $delete_from_expression = "DELETE FROM {$full_table_name}\n";
        // Empty WHERE expression and WHERE values array
        $where_expression = "";
        $where_values = array();
        // If there is WHERE expression, using it and associated values
        if(isset($where["expression"])) {
            $where_expression = $where["expression"];
            $where_values = $where["values"];
        }

        // If there's nor limit, neither WHERE clause, returning 0
        if(empty($where) && !isset($limit) && $limit <= 0) {
            return 0;
        }

        // Empty ORDER expression
        $order_expression = "";
        if(isset($order)) {
            $order_expression = $order;
        }

        // LIMIT expression
        $limit_expression = "";
        if(isset($limit) && $limit > 0) {
            $limit_expression = "\nLIMIT {$limit}";
        }

        // Full DELETE query
        $full_expression = "{$delete_from_expression}{$where_expression}{$order_expression}{$limit_expression}";

        // Wordpress wpdb object
        global $wpdb;
        // Running $wpdb->prepare on query unless WHERE clause is empty
        if(!empty($where_expression)) {
            $full_expression = $wpdb->prepare($full_expression, $where_values);
        }

        // Performing request and getting number of affected rows
        $result = $wpdb->query($full_expression);

        // Possible error handling
        if($result === false) {
            Logger::handle_wpdb_error("DELETE operation failure");
        }

        return $result;
    }

    // Cascade deletion of _single_ record and its child records by id
    public static function cascade_deletion($table_name, $id) {
        // Variable for record data
        $data = NULL;
        // Getting record from database to make sure that record exists
        // and pass its data to processing callback
        $result_array = Storage::select_by_id($table_name, $id);
        // If there's exactly 1 record found, passing it to processing callback
        // If there's more than 1 or none, returning 0
        if(count($result_array) == 1) {
            $data = $result_array[0];
        }
        else {
            return 0;
        }
        // Pre-deletion callback
        $data = Storage::processing_callback("pre_delete", $table_name, $data);
        // Deletion of record. Returning number of affected rows
        $result = Storage::delete_by_id($table_name, $id);
        return $result;
    }

    // Pre-DELETE processing of data in "sessions" table
    public static function sessions_pre_delete($data) {
        // Getting session id from $data
        $session_id = $data["id"];
        // Getting ids of all child requests
        $requests_ids = Storage::get_id_array("requests", "a_session_id", $session_id);
        // Iterating through request ids and
        // performing cascade deletion on every request
        foreach($requests_ids as $request_id) {
            $rows_deleted = Storage::cascade_deletion("requests", $request_id);
        }
        // Returning data back
        return $data;
    }

    // Pre-DELETE processing of data in "requests" table
    public static function requests_pre_delete($data) {
        // List of child objects
        // Key: field in "requests" table
        // Value: name of table field refers to
        $child_objects = array(
            "o_user_input_id" => "user_inputs",
            "o_input_context_id" => "contexts",
            "o_output_context_id" => "contexts"
        );
        // Iterating through child object references and deleting them
        foreach($child_objects as $parent_field => $child_table) {
            // If there's no value in parent field, continue
            if(!isset($data[$parent_field])) {
                continue;
            }
            // Getting id of child object
            $child_id = $data[$parent_field];
            // Deleting child object
            $rows_deleted = Storage::delete_by_id($child_table, $child_id);
        }

        // Getting request id from $data
        $request_id = $data["id"];
        // Child field with reference to parent table
        $child_field = "a_request_id";
        
        // List of "simple" child arrays.
        // Each array element in it is a table with reference to "requests".
        // Each table has no child objects or arrays
        $simple_child_arrays = array(
            "watson_outputs",
            "actions"
        );
        // Iterating through simple child arrays and deleting records
        foreach($simple_child_arrays as $child_table) {
            $rows_deleted = Storage::delete_by_field($child_table, $child_field, $request_id);
        }

        // List of junctions
        // Each array element is an intermediate table between "requests" and
        // other table for many-to-many relationships
        $junctions = array(
            "input_intents",
            "output_intents",
            "input_entities",
            "output_entities"
        );
        // Iterating through junctions and getting lists of their ids
        foreach($junctions as $child_table) {
            // Getting array of ids from current junction
            $junction_ids = Storage::get_id_array($child_table, $child_field, $request_id);
            // Iterating through ids and deleting corresponding rows
            // with their child elements
            foreach($junction_ids as $junction_id) {
                $rows_deleted = Storage::cascade_deletion($child_table, $junction_id);
            }
        }

        // Returning $data back
        return $data;
    }

    // Pre-DELETE processing for "intents" junctions
    public static function intents_junction_pre_delete($data) {
        // Getting intent id
        $intent_id = $data["o_intent_id"];
        // Deleting intent
        $rows_deleted = Storage::delete_by_id("intents", $intent_id);
        // Returning $data back
        return $data;
    }

    // Pre-DELETE processing for "enitites" junctions
    public static function entities_junction_pre_delete($data) {
        // Getting entity id
        $entity_id = $data["o_entity_id"];
        // Deleting entity
        $rows_deleted = Storage::delete_by_id("entities", $entity_id);
        // Returning data back
        return $data;
    }

    // Count rows in table
    public static function count_rows($table_name) {
        // Checking if table exists
        if(!Storage::table_exists($table_name)) {
            Logger::error_with_args("Counting rows in nonexistent table", $table);
            Install::reapply_all_updates();
        }

        // Wordpress database object
        global $wpdb;
        // Full table name with all prefixes
        $full_table_name = Storage::get_full_table_name($table_name);
        // Counting query
        $count_query = "SELECT COUNT(id) FROM {$full_table_name}";
        // Executing query
        $raw_result = $wpdb->get_var($count_query, 0, 0);
        // Converting result from string to integer
        $result = (integer)$raw_result;
        // Returning number of rows
        return $result;
    }

    // Getting data retention settings and executing cleanup if needed
    public static function session_storage_monitor() {
        // Getting session storage limits
        // Storage limit enabled or disabled
        $limit_enabled = (get_option("watsonconv_history_limit_enabled") == "yes");
        // Maximum number of stored sessions
        $limit_number = (integer)get_option("watsonconv_history_limit");

        // If history limit disabled, exiting
        if(!$limit_enabled) {
            return false;
        }

        // Getting current amount of stored sessions
        $current_sessions_number = Storage::count_rows("sessions");

        // If current amount is less than or equal to limit, exiting
        // If current amount is greater than limit, calling cleanup function
        if($current_sessions_number <= $limit_number) {
            return false;
        }
        else {
            Storage::session_storage_cleanup($current_sessions_number, $limit_number);
            return true;
        }
    }

    // Removing obsolete sessions
    public static function session_storage_cleanup($current_number, $limit_number) {
        // Amount of session already added to queue
        $offset = (integer)get_option("watsonconv_cleanup_offset", 0);
        // Excess amount of stored sessions
        $excess_number = $current_number - $limit_number;
        // Hard upper limit on session deletion batch to prevent long execution
        $hard_limit = 500;
        // Setting limit of deletion batch size
        $batch_limit = ($excess_number > $hard_limit) ? $hard_limit : $excess_number;

        // Non-obsolete records deletion prevention
        // If offset is too big, resetting it
        if(($offset + $batch_limit) >= $excess_number) {
            $offset = 0;
        }
        // Data for SELECT query
        $select_data = array(
            "fields" => array("id"),
            "order" => array(
                Storage::order("sessions", "s_created", "ASC")
            ),
            "limit" => $batch_limit,
            "offset" => $offset
        );
        // Performing SELECT
        $result = Storage::select("sessions", $select_data);

        // Updating offset in database
        // Adding size of current batch to offset
        $offset = $offset + $batch_limit;
        // If new offset value is bigger or equal to the number of excess records,
        // resetting it
        if($offset >= $excess_number) {
            $offset = 0;
        }
        
        // If new offset value is smaller than excess value, scheduling one more
        // cleanup as a first task
        if($offset < $excess_number) {
            if(!Background_Task_Runner::task_already_exists("session_storage_check")) {
                Background_Task_Runner::new_task("session_storage_check");
            }
        }
        // Updating offset option
        update_option("watsonconv_cleanup_offset", $offset, "yes");

        // Iterating through result and scheduling their deletion
        foreach($result as $record) {
            if(!Background_Task_Runner::task_already_exists("delete_session", $record["id"])) {
                Background_Task_Runner::new_task("delete_session", $record["id"]);
            }
        }
    }

    // Generic wpdb query wrapper with error handling
    public static function perform_query($query) {
        // Global database object
        global $wpdb;
        // Performing query
        $result = $wpdb->query($query);
        // Poswsible error logging
        if($result === false) {
            Logger::handle_wpdb_error("Generic query failure");
        }
        // Returning result
        return $result;
    }
}

Storage::init();
