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
class TEG_TA_Widget_Twitter_Trends extends TEG_TA_Widget
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        $this->widget_cssclass = 'teg_twitter_api_widget_twitter_trends';
        $this->widget_description = __("Display the user's TRENDS in the sidebar.", 'teg-twitter-api');
        $this->widget_id = 'teg_twitter_api_widget_twitter_trends';
        $this->widget_name = __('Twitter Trends', 'teg-twitter-api');
        $this->settings = array(
            'title' => array(
                'type' => 'text',
                'std' => __('Twitter Trends', 'teg-twitter-api'),
                'label' => __('Title', 'teg-twitter-api'),
            ),
            'number_of_trends' => array(
                'type' => 'number',
                'std' => 5,
                'label' => __('Number of trends to show', 'teg-twitter-api'),
            ), 'trends_WOEID' => array(
                'type' => 'number',
                'std' => 1,
                'label' => __('Trends WOEID', 'teg-twitter-api'),
            ),
            'teg_ta_twitter_trend_widget_layout' => array(
                'label' => __('Templates', 'teg-twitter-api'),
                'type' => 'select',
                'std' => 'teg-trend-tmpl1',
                'class' => 'teg-select',
                'options' => teg_ta_twitter_trend_templates(),
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

        if (apply_filters('teg_twitter_api_widget_twitter_trends_is_hidden', false)) {
            return;
        }


        $this->widget_start($args, $instance);

        $twitterObj = new TEG_TA_Api_Twitter_Trends();

        if (is_numeric($instance['trends_WOEID'])) {

            $twitterObj->setGetField($instance['trends_WOEID']);
        }
        $twitterTrends = (array)$twitterObj->getTrends();


        $data = array(

            'trends' => $twitterTrends,

            'number_of_trends' => $instance['number_of_trends'],

            'instance' => $instance

        );

        teg_ta_get_template('widgets/content-widget-twitter-trends.php', $data);


        $this->widget_end($args);
    }
}
