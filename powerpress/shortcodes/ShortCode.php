<?php

class PowerPressNetworkShortCode
{
    function __construct($tag)
    {
        $this->tag = $tag;
        add_action('init', [$this, 'ppn_shortcode_init']);
    }

    function ppn_shortcode_init()
    {
        add_shortcode($this->tag, [$this, 'ppn_shortcode']);
    }
}
