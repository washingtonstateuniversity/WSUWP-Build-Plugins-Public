<?php

class Watsonconv_Process extends WP_Background_Process {
	/**
	 * @var string
	 */
	protected $action = 'watsonconv_service_process';

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task($task) {
		// Processing incoming task
		// Callback name
		$callback = $task["callback"];
		// Empty variable for data
		$data = NULL;
		if(isset($task["data"])) {
			$data = $task["data"];
		}

		self::$callback($data);

		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		// Deleting processed tasks
		\WatsonConv\Storage::delete_by_field("task_runner_queue", "p_status", "processing");
		// Repoting that runner is now free
		update_option("watsonconv_runner_state", "free", "yes");
		// Ending queue
		parent::complete();
	}

	// Function to call for session storage limits check task
	public static function session_storage_check($data) {
		\WatsonConv\Storage::session_storage_monitor();
	}

	// Function to call for single session deletion
	public static function delete_session($data) {
		\WatsonConv\Storage::cascade_deletion("sessions", $data);
	}

	// Function for applying updates
	public static function apply_update($data) {
		\WatsonConv\Install::apply_update($data);
	}

	// Function for sending email notifications
	public static function send_email_notification($data) {
		\WatsonConv\Email_Notificator::send_summary_notification();
	}
}
