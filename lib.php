<?php

function theme_cleanhc_process_css($css, $theme) {
    global $USER;

    $colourscheme = 4;

    // COLOUR SCHEMES CSS DECLARATIONS
    // ================================================
    /**
     * So far, selector * is used. This might cause some problems.
     * Idea: Maybe better solution is to apply backgrounds to specific elements like body, .header, ...
     */
    if (!empty($colourscheme)) {
        // $colourscheme == 1 is reset, so don't output any styles
        if($colourscheme > 1 && $colourscheme < 5){ // this is how many declarations we defined in edit_form.php

            if ($block_instance->config !== NULL) {

                $fg_colour = $block_instance->config->{'fg'.$colourscheme};
                $bg_colour = $block_instance->config->{'bg'.$colourscheme};

            } else { // block has never been configured, load default colours

                $defaults = array(
                        // fg1 and bg1 would be reset/default colour - do not define it
                        'bg2' => '#FFFFCC',
                        'fg2' => '', // default theme colours will be unchanged
                        'bg3' => '#99CCFF',
                        'fg3' => '',
                        'bg4' => '#000000',
                        'fg4' => '#FFFF00',
                );	
                $fg_colour = $defaults['fg'.$colourscheme];
                $bg_colour = $defaults['bg'.$colourscheme];
            }
                
        }

        // keep in mind that :not selector cannot work properly with IE <= 8 so this will not be included
        $not_selector_for_gteIE8 = '';
        if (!preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT'])) {
            $not_selector_for_gteIE8 = ':not([class*="mce"]):not([id*="mce"]):not([id*="editor"])';
        }

        // if no colours defined, no output, it will remain as default
        if (!empty($bg_colour)) {
            $css .= '
                forumpost .topic {
                        background-image: none !important;
                }
                *'. $not_selector_for_gteIE8 .'{
                        /* it works well only with * selector but mce editor gets unusable */
                        background-color: '.$bg_colour.' !important;
                        background-image: none !important;
                        text-shadow:none !important;
                }
                ';
        }

        // it is recommended not to change forground colour
        if (!empty($fg_colour)) {
            $css .= '
                *'. $not_selector_for_gteIE8 .'{
                        /* it works well only with * selector but mce editor gets unusable */
                        color: '.$fg_colour.' !important;
                }
                #content a, .tabrow0 span {
                        color: '.$fg_colour.' !important;
                }
                .tabrow0 span:hover {
                        text-decoration: underline;
                }
                ';
        }
    }

    return $css;
}

/**
 * Returns an object containing HTML for the areas affected by settings.
 *
 * Do not add Clean specific logic in here, child themes should be able to
 * rely on that function just by declaring settings with similar names.
 *
 * @param renderer_base $output Pass in $OUTPUT.
 * @param moodle_page $page Pass in $PAGE.
 * @return stdClass An object with the following properties:
 *      - navbarclass A CSS class to use on the navbar. By default ''.
 *      - heading HTML to use for the heading. A logo if one is selected or the default heading.
 *      - footnote HTML to use as a footnote. By default ''.
 */
function theme_cleanhc_get_html_for_settings(renderer_base $output, moodle_page $page) {
    global $CFG;
    $return = new stdClass;

    $return->navbarclass = '';
    if (!empty($page->theme->settings->invert)) {
        $return->navbarclass .= ' navbar-inverse';
    }

    if (!empty($page->theme->settings->logo)) {
        $return->heading = html_writer::tag('div', '', array('class' => 'logo'));
    } else {
        $return->heading = $output->page_heading();
    }

    $return->footnote = '';
    if (!empty($page->theme->settings->footnote)) {
        $return->footnote = '<div class="footnote text-center">'.format_text($page->theme->settings->footnote).'</div>';
    }

    return $return;
}
