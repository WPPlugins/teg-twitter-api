<?php
/**
 * The template for displaying Twitter trends
 *
 * This template can be overridden by copying it to yourtheme/teg-twitter-api/content-widget-twitter-trends.php
 *
 * HOWEVER, on occasion TEG Twitter API will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see    https://docs.themeegg.com/document/template-structure/
 * @author  ThemeEgg
 * @package TEGTwitterApi/Templates
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


?>
<div class="teg-ta-feeds-shortcode">
    <div class="teg-ta-template <?php echo get_option('teg_ta_twitter_feed_shortcode_layout', '') ?>">

        <?php do_action('teg_ta_twitter_feed_shortcode_layout_before', 10, 0) ?>

        <h2><?php echo esc_attr($title); ?></h2>
        <ul>
            <?php

            foreach ($twitter_feeds_array as $feed_index => $feed) {

                ?>

                <li>
                    <div class="teg-ta-user-logo">
                        <a href="https://twitter.com/<?php echo esc_attr($twitter_username); ?>" target="_blank">
                            <img src="<?php
                            echo esc_attr($feed['user']['profile_image_url_https']);
                            ?>"/></a></div>
                    <div class="teg-ta-single-feeds">
                        <h5><a href="https://twitter.com/<?php echo esc_attr($twitter_username); ?>"
                               target="_blank"><span
                                        class="teg-ta-account-name"><?php echo esc_attr($feed['user']['name']) ?></span>
                                <span class="teg-ta-user-name">@<?php echo esc_attr($twitter_username) ?></span></a>
                        </h5>
                        <p><?php echo(teg_ta_twitter_feed_text_render($feed)); ?></p>
                        <div class="teg-ta-feeds-actions">

                            <span class="teg-ta-feed-like"><a target="_blank"
                                                              href="https://twitter.com/intent/like?tweet_id=<?php echo esc_attr($feed['id_str']) ?>"
                                                              title="<?php __('Like', 'teg-twitter-api') ?>"><?php __('Like', 'teg-twitter-api') ?></a></span>
                            <span class="teg-ta-feed-share"><a target="_blank"
                                                               href="https://twitter.com/intent/retweet?tweet_id=<?php echo esc_attr($feed['id_str']) ?>"
                                                               title="<?php __('Share', 'teg-twitter-api') ?>"><?php __('Share', 'teg-twitter-api') ?></a></span>

                            <span class="teg-ta-feed-post-time"
                                  title="<?php echo esc_attr($feed['created_at']); ?>"><?php echo date('M-d', strtotime(esc_attr($feed['created_at']))) ?></span>
                        </div>
                    </div>

                </li>

                <?php

            }
            ?>
        </ul>

        <?php do_action('teg_ta_twitter_feed_shortcode_layout_after', 10, 0) ?>

    </div>
</div>

