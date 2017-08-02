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

<div class="teg-ta-trends-shortcode">
    <div class="teg-ta-template <?php echo get_option('teg_ta_twitter_trend_shortcode_layout', '') ?>">

        <?php do_action('teg_ta_twitter_trend_shortcode_layout_before', 10, 0) ?>

        <h2><?php echo esc_attr($title); ?></h2>
        <ul>
            <?php

            foreach ($trends as $trend_index => $trend) {

                if ($trend_index >= $number_of_trends) {
                    break;
                }

                ?>

                <li>
                    <a target="_blank" href="<?php echo $trend['url']; ?>">
                        <b><?php echo esc_attr($trend['name']); ?></b>
                    </a>
                    <?php if ($trend['tweet_volume']): ?>
                        <span>(<?php echo esc_attr($trend['tweet_volume']); ?>)</span>
                    <?php endif; ?>
                </li>

                <?php

            }
            ?>
        </ul>

        <?php do_action('teg_ta_twitter_trend_shortcode_layout_after', 10, 0) ?>

    </div>
</div>