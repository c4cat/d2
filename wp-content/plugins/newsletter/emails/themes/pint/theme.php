<?php
/*
 * Some variables are already defined:
 *
 * - $theme_options An array with all theme options
 * - $theme_url Is the absolute URL to the theme folder used to reference images
 * - $theme_subject Will be the email subject if set by this theme
 *
 */

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

global $newsletter, $post;

$filters = array();

// if (!empty($theme_options['theme_categories'])) {
//     $filters['category__in'] = $theme_options['theme_categories'];
// }

if (empty($theme_options['theme_max_posts']))
    $filters['showposts'] = 9;
else
    $filters['showposts'] = (int) $theme_options['theme_max_posts'];

// $args = array(
//     'numberposts'       => $filters['showposts'],
//     'posts_per_page'   => $filters['showposts'],
//     'offset'           => 0,
//     'category'         => '',
//     'orderby'          => 'post_date',
//     'order'            => 'DESC',
//     'include'          => '',
//     'exclude'          => '',
//     'meta_key'         => '',
//     'meta_value'       => '',
//     'post_type'        => 'product',
//     'post_mime_type'   => '',
//     'post_parent'      => '',
//     'post_status'      => 'published',
//     'suppress_filters' => true,
//     'taxonomies' => 'product_category-email'
//      );

    $args = array( 'post_type' => 'product', 'numberposts' => -1 );
    $taxonomies = 'product_category-4email,';
    $taxonomies = explode(',', $taxonomies);
    echo($taxonomies[1]);
    foreach($taxonomies as $k => $v) {
        if ($v != '') {
        list($key, $value) = explode('-', $v, 2);
        if (isset($result[$key])) {
            $result[$key] .= ', ' . $value;
        } else {
            $result[$key] = $value;
        }
        }
    }

    if (isset($result)){
    foreach($result as $k => $v) {
        $args[$k] = $v;
    }
    }

$posts = get_posts($args);


// var_dump($posts);


?>
<!DOCTYPE html>
<html>
    <head>
        </style>
    </head>
    <body style="background-color: #eee; background-image: url('<?php echo $theme_url; ?>/images/bg.jpg'); font-family: Helvetica Neue, Helvetica, Arial, sans-serif; font-size: 14px; color: #666; margin: 0 auto; padding: 0;">
        <br>
        <table align="center">
            <tr>
                <td align="center">
                    <small><a target="_tab"  href="{email_url}" style="color: #666; text-decoration: none">View this email online123</a></small>
                    <br>
                        <div style="color: #b00; font-size: 50px; font-family: serif; font-style: italic;">
                            <?php echo get_option('blogname'); ?>
                        </div>
                    <br>
                    <br>

                            <table cellpadding="5">
                                    <tr>
                                <?php for ($i=0; $i<3; $i++) { $post = $posts[$i]; setup_postdata($post); ?>
                                        <td align="center" valign="top">
                                        <a target="_tab" href="<?php echo get_permalink(); ?>" style="font-size: 14px; line-height: 26px; font-weight: bold; color: #000; text-decoration: none"><?php echo substr(get_the_title(), 0, 25); ?>...</a><br>
                                        <a target="_tab" href="<?php echo get_permalink(); ?>" style="display: block; width: 200px; height: 170px; overflow: hidden"><img width="200" src="<?php echo newsletter_get_post_image($post->ID, 'medium'); ?>" alt=""></a>
                                        </td>
                                <?php } ?>
                                    </tr>
                                    <tr>
                                <?php for ($i=3; $i<6; $i++) { $post = $posts[$i]; setup_postdata($post); ?>
                                        <td align="center" valign="top">
                                        <a target="_tab"  href="<?php echo get_permalink(); ?>" style="font-size: 14px; line-height: 26px; font-weight: bold; color: #000; text-decoration: none"><?php echo substr(get_the_title(), 0, 25); ?>...</a><br>
                                        <a target="_tab"  href="<?php echo get_permalink(); ?>" style="display: block; width: 200px; height: 170px; overflow: hidden"><img width="200" src="<?php echo newsletter_get_post_image($post->ID, 'medium'); ?>" alt=""></a>
                                        </td>
                                <?php } ?>
                                    </tr>
                                    <tr>
                                <?php for ($i=6; $i<9; $i++) { $post = $posts[$i]; setup_postdata($post); ?>
                                        <td align="center" valign="top">
                                        <a target="_tab"  href="<?php echo get_permalink(); ?>" style="font-size: 14px; line-height: 26px; font-weight: bold; color: #000; text-decoration: none"><?php echo substr(get_the_title(), 0, 25); ?>...</a><br>
                                        <a target="_tab"  href="<?php echo get_permalink(); ?>" style="display: block; width: 200px; height: 170px; overflow: hidden"><img width="200" src="<?php echo newsletter_get_post_image($post->ID, 'medium'); ?>" alt=""></a>
                                        </td>
                                <?php } ?>
                                    </tr>
                            </table>

                    <br><br>
                    
                            <?php 
                            if (!isset($theme_options['theme_social_disable'])) { 
                                include WP_PLUGIN_DIR . '/newsletter/emails/themes/default/social.php';                             
                            }
                            ?>

                            <small>To change your subscription, <a target="_tab"  href="{profile_url}" style="color: #666; text-decoration: none">click here</a></small>

                </td>
            </tr>
        </table>
    </body>
</html>