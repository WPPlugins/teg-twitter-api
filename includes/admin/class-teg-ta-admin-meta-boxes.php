<?php
/**
 * TEGTwitterAPI Meta Boxes
 *
 *
 *
 * @author      ThemeEgg
 * @category    Admin
 * @package     TEGTwitterApi/Admin/Meta Boxes
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * TEG_TA_Admin_Meta_Boxes.
 */
class TEG_TA_Admin_Meta_Boxes implements TEG_TA_Meta_Box_Interface
{

    /**
     * Constructor.
     */
    public function __construct()
    {

        if (is_admin()) {

            add_action('load-post.php', array($this, 'init_metabox'));

            add_action('load-post-new.php', array($this, 'init_metabox'));
        }

    }

    /**
     * Add an error message.
     * @param string $text
     */


    /**
     * Meta box initialization.
     */
    public function init_metabox()
    {
        // TODO: Implement init_metabox() method.
        add_action('add_meta_boxes', array($this, 'add_metabox'));

        add_action('save_post', array($this, 'save_metabox'), 10, 2);

        add_action('admin_notices', array($this, 'admin_notices'));
    }

    public function add_metabox()
    {
        add_meta_box(
            'teg-twitter-api-settings-metabox',
            __('Twitter Settings', 'teg-twitter-api'),
            array($this, 'render_metabox'),
            'post',
            'advanced',
            'default'
        );

    }

    /**
     * Renders the meta box.
     */
    public function render_metabox($post)
    {
        // Add nonce for security and authentication.
        wp_nonce_field('twitter_nonce_action', 'twitter_tweet_nonce');

        $post_twitter_settings_metabox = get_post_meta($post->ID, 'post_twitter_settings_metabox');

        if (isset($post_twitter_settings_metabox[0])):
            $post_twitter_settings_metabox = $post_twitter_settings_metabox[0];
        endif;
        ?>
        <div class="twitter-setting-wraper">
            <div class="post-twitter">
                <input
                        id="showTweets"
                        class="dg_checkbox"
                        name="post_twitter_settings_metabox[post_tweet_checkbox]"
                    <?php
                    if (isset($post_twitter_settings_metabox['post_tweet_checkbox'])):
                        echo ($post_twitter_settings_metabox['post_tweet_checkbox']) ? ' checked="checked" ' : ' ';
                    endif;
                    ?>
                        type="checkbox">
                <label for="showTweets" class="selectit">Post to Twitter</label><br>
            </div>
            <div class="post-twitter">
                <input
                        id="tweetsID"
                        class="dg_input"
                        name="post_twitter_settings_metabox[tweets_id]"
                        value="<?php
                        if (isset($post_twitter_settings_metabox['tweets_id'])):
                            echo $post_twitter_settings_metabox['tweets_id'];
                        endif;
                        ?>"
                        type="hidden"/>
            </div>
        </div>
        <?php
    }

    /**
     * Handles saving the meta box.
     *
     * @param int $post_id Post ID.
     * @param WP_Post $post Post object.
     * @return null
     */
    public function save_metabox($post_id, $post)
    {
        // Add nonce for security and authentication.
        $nonce_name = isset($_POST['twitter_tweet_nonce']) ? $_POST['twitter_tweet_nonce'] : '';

        $nonce_action = 'twitter_nonce_action';

        // Check if nonce is set.
        if (empty($nonce_name)) {
            return;
        }

        // Check if nonce is valid.
        if (!wp_verify_nonce($nonce_name, $nonce_action)) {
            return;
        }

        // Check if user has permissions to save data.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check if not an autosave.
        if (wp_is_post_autosave($post_id)) {
            return;
        }

        // Check if not a revision.
        if (wp_is_post_revision($post_id)) {
            return;
        }

        $post_content = wp_html_excerpt($_POST['content'], 100);

        $postFields = array(
            'status' => $post_content,
            'media' => '',
        );
        $post_to_twitter = false;


        if (
            isset($_POST['post_twitter_settings_metabox']['post_tweet_checkbox'])
            &&
            ($_POST['post_twitter_settings_metabox']['post_tweet_checkbox']) === 'on'
        ) {

            $post_to_twitter = true;

        }

        $post_result = array();

        if ($post_to_twitter) {

            $twitterPostTweets = new TEG_TA_Api_Post_Tweets();

            $twitterPostTweets->setPostFields($postFields);

            $post_result = $twitterPostTweets->postTweet();

            $post_result = (Array)json_decode($post_result);
        }

        $getallapi_post_twitter_message = array(

            'post_id' => (isset($_POST['post_ID'])  && is_numeric($_POST['post_ID'])) ? intval($_POST['post_ID']): -1,

            'twitter_update_message' => $post_result,
        );

        if (isset($post_result['id_str'])) {

            $_POST['post_twitter_settings_metabox']['tweets_id'] = $post_result['id_str'];
        }
        $post_data=array(

                'post_tweet_checkbox'=>sanitize_text_field($_POST['post_twitter_settings_metabox']['post_tweet_checkbox']),

                'tweets_id'=>sanitize_text_field($_POST['post_twitter_settings_metabox']['tweets_id']),
        );

        set_transient('getallapi_post_twitter_message', $getallapi_post_twitter_message);

        if (isset($_POST['post_twitter_settings_metabox'])) {
            $post_twitter_settings_metabox_value = $post_data;
            update_post_meta($post_id, 'post_twitter_settings_metabox', $post_twitter_settings_metabox_value);
        }

    }

    public function admin_notices()
    {

        $getallapi_post_twitter_message = get_transient('getallapi_post_twitter_message');

        $getallapi_post_twitter_message = json_decode(json_encode($getallapi_post_twitter_message), true);

        delete_transient('getallapi_post_twitter_message');

        if (isset($getallapi_post_twitter_message['twitter_update_message'])):
            $getallapi_post_twitter_message = (Array)$getallapi_post_twitter_message['twitter_update_message'];
        endif;

        if (isset($getallapi_post_twitter_message['created_at'])):
            $messageType = "updated";
            $message = "Successfully posted on twitter.";
        elseif (isset($getallapi_post_twitter_message['errors'])):
            $messageType = "error";
            $message = "Already tweeted.";
        elseif (isset($getallapi_post_twitter_message['errors'])):
            $messageType = "error";
            $message = (array)$getallapi_post_twitter_message['errors'][0]['message'];
        else:
            return;
        endif;

        ?>

        <div id="messagetiwitter" class="<?php echo esc_attr($messageType) ?> notice notice-success is-dismissible">

            <p><?php echo esc_attr($message); ?></p>

            <button type="button" class="notice-dismiss">

                <span class="screen-reader-text"><?php echo __('Dismiss this notice.', 'teg-twitter-api'); ?></span>

            </button>

        </div>

        <?php

    }

    function __destruct()
    {

    }

}

new TEG_TA_Admin_Meta_Boxes();
