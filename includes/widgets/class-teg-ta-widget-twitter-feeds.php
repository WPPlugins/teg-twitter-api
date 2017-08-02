<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Twitter Trends Widget.
 *
 * Displays twitter trends widget.
 *
 * @author   ThemeEgg
 * @category Widgets
 * @package  TEGTwitterAPI/Widgets
 * @version  1.0
 * @extends  TEG_TA_Widget
 */
class TEG_TA_Widget_Twitter_Feeds extends TEG_TA_Widget
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        $this->widget_cssclass = 'teg_twitter_api_widget_twitter_feeds';
        $this->widget_description = __("Display twitter feeds.", 'teg-twitter-api');
        $this->widget_id = 'teg_twitter_api_widget_twitter_feeds';
        $this->widget_name = __('Twitter Feeds', 'teg-twitter-api');
        $this->settings = array(
            'title' => array(
                'type' => 'text',
                'std' => __('Twitter Feeds', 'teg-twitter-api'),
                'label' => __('Title', 'teg-twitter-api'),
            ),
            'number_of_tweets' => array(
                'type' => 'number',
                'std' => 5,
                'label' => __('Number of tweets to show', 'teg-twitter-api'),
            ),
            'teg_ta_twitter_feed_widget_layout' => array(
                'label' => __('Templates', 'teg-twitter-api'),
                'std' => 'teg-feed-tmpl1',
                'type' => 'select',
                'class' => 'teg-select',
                'options' => teg_ta_twitter_feed_templates(),
            )
        );

        parent::__construct();
    }

    /**
     * Output widget.
     *
     * @see WP_Widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {

        if (apply_filters('teg_twitter_api_widget_twitter_feeds_is_hidden', false)) {
            return;
        }

        $this->widget_start($args, $instance);

        $twitterObj = new TEG_TA_Api_Twitter_Tweets();

        $twitter_feed_array = $twitterObj->getTweets($instance['number_of_tweets']);

        if (gettype($twitter_feed_array) !== 'array') {

            $twitter_feed_array = array();
        }

        $data = array(

            'twitter_feeds_array' => $twitter_feed_array,

            'twitter_username' => get_option('teg_twitter_api_twitter_username'),

            'instance' => $instance
        );

        teg_ta_get_template('widgets/content-widget-twitter-feeds.php', $data);


        $this->widget_end($args);
    }
}
