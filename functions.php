<?php

/**
 * Notes
 *
 * Optiing to use Modernizr.... but just load shim, that way if you do want additional functionality you can just replace the file with a custom build...
 * WP Screenshot PNG size changed http://codex.wordpress.org/Theme_Development#Screenshot
 *
 */


/**
 * Remove Parent Styling
 *
 * Removes the default Thematic CSS styling completely.
 *
 */

function childtheme_remove_parent_styling( $dependencies ) {
    return array();
}
add_filter( 'thematic_childtheme_style_dependencies', 'childtheme_remove_parent_styling' );

/**
 * Remove Thematic Menu JavaScript
 *
 * Removes the default Thematic JS scripts (Superfish) completely.
 *
 * Reference http://thematictheme.com/forums/topic/correct-way-to-prevent-loading-thematic-scripts/
 *
 */

function childtheme_remove_superfish(){
    remove_theme_support('thematic_superfish');
 }
add_action('wp_enqueue_scripts','childtheme_remove_superfish', 9);

/**
 * Script Manager
 *
 * Setup for adding and removing scripts and styling.
 *
 * Reference http://wpcandy.com/teaches/how-to-load-scripts-in-wordpress-themes/
 *
 */

function childtheme_script_manager() {
    // wp_register_script template ( $handle, $src, $deps, $ver, $in_footer );

    // registers modernizr script, childtheme path, no dependency, no version, loads in header
    wp_register_script('modernizr-js', get_stylesheet_directory_uri() . '/js/modernizr.js', false, false, false);
    // registers misc custom script, childtheme path, yes dependency is jquery, no version, loads in footer
    wp_register_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), false, true);

    // enqueue the scripts for use in theme
    wp_enqueue_script ('modernizr-js');
    wp_enqueue_script('custom-js');
}
add_action('wp_enqueue_scripts', 'childtheme_script_manager');

// deregister styles
function childtheme_deregister_styles() {
}
add_action('wp_print_styles', 'childtheme_deregister_styles', 100);

// deregister scripts
function childtheme_deregister_scripts() {
    wp_dequeue_script('thematic-js'); // removes themaitc-js which has more Superfish scripts
}
add_action( 'wp_print_scripts', 'childtheme_deregister_scripts', 100 );

/**
 * Modernizr add 'no-js'.
 *
 * This filter adds the 'no-js' class to the HTML tag, which Modernizr will remove (if Javascript is enabled) and replace it with a "js" class. This is super useful for providing CSS fallbacks, but Modernizr does a ton more.
 *
 * Reference http://modernizr.com/docs/
 *
 */

function childtheme_html_class( $class_att ) {
    $class_att = "no-js";
    return $class_att;
}
add_filter( 'thematic_html_class', 'childtheme_html_class' );

/**
 * Add Favicon
 *
 * The Favicon is actually really complicated, but a quick and dirty method is to at least add a 32x32 ico file (at the least).
 *
 * Reference http://www.jonathantneal.com/blog/understand-the-favicon/
 * Photoshop Plugin to save ICO files http://www.telegraphics.com.au/sw/
 */

function childtheme_add_favicon() { ?>
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
<?php }
add_action('wp_head', 'childtheme_add_favicon');

/**
 * Clean up <head> of Site
 *
 * Wordpress by default throws in all kinds of relational links, for SEO purposes, sometimes they work, and sometimes they don't. A Plugin like WordPress SEO can handle some of these also, but others are not included.
 *
 * Reference http://scottnix.com/polishing-thematics-head/
 *
 */

// remove really simple discovery
remove_action('wp_head', 'rsd_link');
// remove windows live writer xml
remove_action('wp_head', 'wlwmanifest_link');
// remove index relational link
remove_action('wp_head', 'index_rel_link');
// remove parent relational link
remove_action('wp_head', 'parent_post_rel_link');
// remove start relational link
remove_action('wp_head', 'start_post_rel_link');
// remove prev/next relational link
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
// remove short link
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Register Two Additional Menus
 *
 * Not always needed, but a lifesaver if you need more custom menus for widget areas.
 *
 */

// register two additional custom menu slots
function childtheme_register_menus() {
    if ( function_exists( 'register_nav_menu' )) {
        register_nav_menu( 'secondary-menu', 'Secondary Menu' );
        register_nav_menu( 'tertiary-menu', 'Tertiary Menu' );
    }
}
add_action('thematic_child_init', 'childtheme_register_menus');

/**
 * Responsive Menu Structure
 *
 * Modified to add toggle to label in link format instead of <h3> that is defaulted from the parent theme. This basic structure comes from a
 *
 * Reference http://codepen.io/bradfrost/pen/vljdx
 *
 */

function childtheme_override_access() {
    ?>
    <div id="access" role="navigation">
        <a class="menu-toggle" href="#">Menu</a>
        <?php
        if ( ( function_exists( 'has_nav_menu' ) ) && ( has_nav_menu( apply_filters( 'thematic_primary_menu_id', 'primary-menu' ) ) ) ) {
            echo  wp_nav_menu(thematic_nav_menu_args());
        } else {
            echo  thematic_add_menuclass( wp_page_menu( thematic_page_menu_args () ) );
        }
        ?>
    </div><!-- #access -->
    <?php
}

/**
 * Modify Navigational Elements
 *
 * The Navigation Above feature of Thematic is pretty silly (and ugly), so that is
 * completely removed. For the Navigation Below, we remove it on single pages since it
 * can link to totally unrelated content which doesn't make much sense in most cases.
 *
 */

// override completely removes nav above functionality
function childtheme_override_nav_above() {
    // silence
}

// override the nav below, removed on single posts
function childtheme_override_nav_below() {
    if ( ! is_single() ) { ?>
        <div id="nav-below" class="navigation"> <?php
            if ( function_exists( 'wp_pagenavi' ) ) {
                wp_pagenavi();
             } else { ?>
            <div class="nav-previous"><?php next_posts_link(sprintf('<span class="meta-nav">&laquo;</span> %s', __('Older posts', 'thematic') ) ) ?></div>
            <div class="nav-next"><?php previous_posts_link(sprintf('%s <span class="meta-nav">&raquo;</span>',__( 'Newer posts', 'thematic') ) ) ?></div>
            <?php } ?>
        </div>  <?php
    }
}

/**
 * Thematic Featured Image Size
 *
 * Appears on anything with an excerpt set, the default is 100x100 which is ridiculously small, this swaps it out for a more manageable 300x300, but can be easily changed by modifying the sizes.
 *
 */

function childtheme_post_thumb_size($size) {
    $size = array(300,300);
    return $size;
}
add_filter('thematic_post_thumb_size', 'childtheme_post_thumb_size');

/**
 * Modify the Post Header and Post Footer
 *
 * Moves the elemenets and changes structure of the postheader and postfooter to rearrange things for a cleaner look, moves date below post and adds icon fonts in a list style format instead of the default which is just a single line.
 *
 * Location
 */

// kill the post header information, loading this below in the post footer
function childtheme_override_postheader_postmeta() {
    // silence!
}

// example of changing up the display of the entry-utility for a different look
function childtheme_override_postfooter() {
    $post_type = get_post_type();
    $post_type_obj = get_post_type_object($post_type);
    $tagsection = get_the_tags();

    // Display nothing for "Page" post-type
    if ( $post_type == 'page' ) {
        $postfooter = '';
    // For post-types other than "Pages" press on
    } else {
        $postfooter = '<footer class="entry-utility">';
        $postfooter .= '<ul class="main-utilities">';
        $postfooter .= '<li class="icon-calendar">' . thematic_postmeta_entrydate() . '</li>';
        $postfooter .= '<li class="icon-folder">' . thematic_postfooter_postcategory() . '</li>';
        $postfooter .= '<li class="icon-comment">' . thematic_postfooter_postcomments() . '</li>';
        if ( $tagsection ) {
            $postfooter .= '<li class="icon-tag">' . thematic_postfooter_posttags() . '</li>';
        }
        if ( is_user_logged_in() ) {
                $postfooter .= '<li class="icon-pencil">' . thematic_postfooter_posteditlink() . '</li>';
        }
        $postfooter .= '</ul>';
        $postfooter .= "\n\n\t\t\t\t\t</footer><!-- .entry-utility -->\n";
    }
    // Put it on the screen
    echo apply_filters( 'thematic_postfooter', $postfooter ); // Filter to override default post footer
}

// removes the "Published" from the date in the postfooter, also removes seperator " | "
function childtheme_override_postmeta_entrydate() {
    $entrydate = '<span class="entry-date">';
    $entrydate .= get_the_time(thematic_time_display());
    $entrydate .= '</span>';
    return apply_filters('thematic_postmeta_entrydate', $entrydate);
}

// remove unneeded code from postcategory
function childtheme_override_postfooter_postcategory() {
    $postcategory = "\n\n\t\t\t\t\t\t" . '<span class="cat-links">';
    if (is_single()) {
        $postcategory .= __('Categories ', 'thematic') . get_the_category_list(', ');
        $postcategory .= '</span>';
        $posttags = get_the_tags();
        if ( !$posttags ) {
            $postcategory .= '';
        }
    } elseif ( is_category() && $cats_meow = thematic_cats_meow(', ') ) {
        $postcategory .= __('Also posted in ', 'thematic') . $cats_meow;
        $postcategory .= '</span>' . "\n\n\t\t\t\t\t\t";
    } else {
        $postcategory .= __('Posted in ', 'thematic') . get_the_category_list(', ');
        $postcategory .= '</span>' . "\n\n\t\t\t\t\t\t";
    }
    return apply_filters('thematic_postfooter_postcategory',$postcategory);
}

// remove unneeded code from posttags
function childtheme_override_postfooter_posttags() {
    if ( is_single() && !is_object_in_taxonomy( get_post_type(), 'category' ) ) {
        $tagtext = __('','thematic');
        $posttags = get_the_tag_list("<span class=\"tag-links\">$tagtext",', ','</span> ');
    } elseif ( is_single() ) {
        $tagtext = __('','thematic');
        $posttags = get_the_tag_list("<span class=\"tag-links\">$tagtext",', ','</span> ');
    } elseif ( is_tag() && $tag_ur_it = thematic_tag_ur_it(', ') ) {
        $posttags = '<span class="tag-links">' . __('Also tagged ', 'thematic') . $tag_ur_it . '</span>' . "\n\n\t\t\t\t\t\t";
    } else {
        $tagtext = __('','thematic');
        $posttags = get_the_tag_list("<span class=\"tag-links\">$tagtext",', ','</span>' . "\n\n\t\t\t\t\t\t");
    }
    return apply_filters('thematic_postfooter_posttags',$posttags);
}