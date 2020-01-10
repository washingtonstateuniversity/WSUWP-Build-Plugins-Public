<?php


class PowerPressWebSub
{
    const DEFAULT_HUB = "https://pubsubhubbub.appspot.com";
    private $hub;
    private $wp_remote_options = false;


    /**
     * Websub constructor.
     * @param string $hub URL to the hub to use, defaults to <code>$DEFAULT_HUB</code>
     */
    public function __construct($hub = self::DEFAULT_HUB){
        $this->hub = $hub;
        $this->wp_remote_options['user_agent'] = 'Blubrry PowerPress/'.POWERPRESS_VERSION;
		$this->wp_remote_options['httpversion'] = '1.1';
		$this->wp_remote_options['headers'] = array('Content-Type'=>'application/x-www-form-urlencoded');
		$this->wp_remote_options['body'] = array('hub.mode'=>'publish');
		$this->wp_remote_options['timeout'] = 3; // Do not allow this to block more than 3 seconds
    }


    /**
     * Publishes update to the hub
     * @param string $feedUrl URL of feed to update
     * @throws Exception Throws exception if HTTP response is not 20x and returns HTTP body
     */
    public function publish($feedUrl) {

        $this->wp_remote_options['body']['hub.url'] = $feedUrl;
        $response = wp_remote_post($this->hub, $this->wp_remote_options);

        if (is_wp_error($response)) // Handle system level errors
        {
            if ($response->get_error_message())
                throw new Exception($response->get_error_message());
            else
                throw new Exception("HTTP error response " . $response->get_error_code());
            return false;
        }

        if ($response['response']['code'] > 299) {
            if (!empty($response['body'])) // Handle service level errors
                throw new Exception($response['body']);
            else
                throw new Exception("HTTP response " . $response['response']['code']);
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getHub()
    {
        return $this->hub;
    }
}