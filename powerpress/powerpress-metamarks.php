<?php

function powerpress_metabox_save($post_ID)
{
    $MetaMarks = ( !empty($_POST['MetaMarks']) ? $_POST['MetaMarks'] : false);
    $Episodes = ( !empty($_POST['Powerpress']) ? $_POST['Powerpress'] : false);
    if( $Episodes )
    {
        foreach( $Episodes as $feed_slug => $Powerpress )
        {
            $field = '_'.$feed_slug.':metamarks';
            delete_post_meta( $post_ID, $field);

            if( !empty($Powerpress['change_podcast']) || !empty($Powerpress['new_podcast']) )
            {
                // No URL specified, then it's not really a podcast to save
                if( $Powerpress['url'] == '' )
                    continue; // go to the next media file

                if( !empty($MetaMarks[ $feed_slug ]) )
                {
                    $MetaMarkData = $MetaMarks[ $feed_slug ];
                    // Loop through, and convert position and duration to seconds, if specified with 00:00:00
                    foreach( $MetaMarkData as $index => $row )
                    {
                        $MetaMarkData[ $index ]['position'] = powerpress_raw_duration( $row['position'] );
                        $MetaMarkData[ $index ]['duration'] = powerpress_raw_duration( $row['duration'] );
                    }
                    reset($MetaMarkData);

                    foreach( $MetaMarkData as $index => $row )
                    {
                        if( empty($MetaMarkData[ $index ]['type']) && empty($MetaMarkData[ $index ]['position']) && empty($MetaMarkData[ $index ]['duration']) && empty($MetaMarkData[ $index ]['link']) && empty($MetaMarkData[ $index ]['value']) )
                        {
                            unset($MetaMarkData[ $index ]);
                        }
                    }
                    reset($MetaMarkData);

                    if( count($MetaMarkData) > 0 )
                    {
                        if( !empty($Powerpress['new_podcast']) )
                        {
                            add_post_meta($post_ID, $field, $MetaMarkData, true);
                        }
                        else
                        {
                            update_post_meta($post_ID, $field, $MetaMarkData);
                        }
                    }
                    else // Delete them from the database...
                    {
                        delete_post_meta($post_ID, $field );
                    }
                }
            }
        } // Loop through posted episodes...
    }
    return $post_ID;
}

function powerpress_metamarks_addrow() // Called by AJAX call
{
    $feed_slug = $_POST['feed_slug'];
    $next_row = $_POST['next_row'];
    $html = powerpress_metamarks_editrow_html($feed_slug, $next_row, null, true);
    echo $html;
    exit;
}

function powerpress_metamarks_editrow_html($feed_slug, $next_row, $data = null, $new = false)
{
    $feed_slug = esc_attr($feed_slug);
    $MarkTypes = powerpress_metamarks_get_types();
    if( !is_array($data) )
    {
        $data = array();
        $data['type'] = '';
        $data['position'] = '';
        $data['duration'] = '';
        $data['link'] = '';
        $data['value'] = '';
    }
    $data['position'] = powerpress_readable_duration($data['position']);
    $data['duration'] = powerpress_readable_duration($data['duration']);
    if( $data['position'] == '0:00' )
        $data['position'] = '';
    if( $data['duration'] == '0:00' )
        $data['duration'] = '';

    if ($data['position']) {
        $pos = $data['position'];
    } else {
        $pos = "Time";
    }
    if ($data['type']) {
        $type = ucfirst($data['type']);
    } else {
        $type = "Type";
    }
    if (!$new) {
        $class = ' class="pp-hidden-settings"';
        $option = 'Edit';
    } else {
        $class = '';
        $option = 'Save';
    }
    $html = '<div class="pp-metamarks-row" id="powerpress_metamarks_row_'. esc_attr($feed_slug) .'_'. esc_attr($next_row) .'">';
    $html .= '<div class="metamark-top-section id="metamark-top-section-' . esc_attr($feed_slug) .'-'. esc_attr($next_row) . '">';
    $html .= '<div id="pp-metamark-preview-pos-' . esc_attr($feed_slug) .'-'. esc_attr($next_row) . '" class="pp-metamark-preview-pos-">' . esc_html($pos) . '</div>';
    $html .= '<div id="pp-metamark-preview-type--' . esc_attr($feed_slug) .'-'. esc_attr($next_row) . '" class="pp-metamark-preview-type-">' . esc_html($type) . '</div>';
    $html .= '<div class="pp-metamark-delete"><a href="" onclick="return powerpress_metamarks_deleterow(\'powerpress_metamarks_row_'. esc_js($feed_slug) .'_'. esc_js($next_row) .'\');" title="'. __('Delete', 'powerpress') .'">';
    $html .= __('Delete', 'powerpress') . '</a></div><div class="pp-metamark-edit"><a href="" id="pp-toggle-metamark-'. esc_attr($next_row) . '-' . esc_attr($feed_slug) . '" title="'. __($option, 'powerpress') .'" onclick="powerpress_toggleMetamarksSettings(this); return false;">' . __($option, 'powerpress') . '</a></div>';
    $html .= '</div><div id="pp-hide-metamark-' . esc_attr($feed_slug) .'-'. esc_attr($next_row) . '"' . $class . '>';
    $html .= '<div class="pp-section-container"><div class="powerpress-label-container" id="pp-type-label' . esc_attr($feed_slug) .'-'. esc_attr($next_row) . '"><label class="pp-ep-box-label" style="width: 100%;" for="pp-metamark-type-'. esc_attr($feed_slug) .'_'. esc_attr($next_row) .'">' . __('Type', 'powerpress') . '</label><select id="pp-metamark-type-'. esc_attr($feed_slug) .'-'. esc_attr($next_row) .'" class="pp-ep-box-input" style="width: 100%;" type="text" title="'. __('Type', 'powerpress') .'" name="MetaMarks['.esc_attr($feed_slug).']['.esc_attr($next_row).'][type]">';
    $html .= powerpress_print_options( array(''=>'Select Type')+ $MarkTypes, esc_html($data['type']), true);
    $html .= '</select></div><div class="powerpress-label-container" id="pp-pos-label' .  esc_attr($feed_slug) .'-'. esc_attr($next_row) . '"><label class="pp-ep-box-label" for="pp-metamark-pos-'.  esc_attr($feed_slug) .'-'. esc_attr($next_row) .'">' . __('Position', 'powerpress') . '</label>';
    $html .= '<input id="pp-metamark-pos-'. esc_attr($feed_slug) .'-'. esc_attr($next_row) .'" class="pp-ep-box-input" style="width: 100%;" type="text" title="'. __('Position', 'powerpress') .'" name="MetaMarks['.esc_attr($feed_slug).']['.esc_attr($next_row).'][position]" value="' .htmlspecialchars($data['position']) .'" placeholder="'. htmlspecialchars(__('Position', 'powerpress'))  .'" /></div>';
    $html .= '<div class="powerpress-label-container" id="pp-dur-label' .  esc_attr($feed_slug) .'-'. esc_attr($next_row) . '"><label class="pp-ep-box-label" for=\"pp-metamark-dur-'.  esc_attr($feed_slug) .'-'. esc_attr($next_row) .'\">' . __('Duration', 'powerpress') . '</label>';
    $html .= '<input id="pp-metamark-dur-'. esc_attr($feed_slug) .'-'. esc_attr($next_row) .'" class="pp-ep-box-input" style="width: 100%;" type="text" title="'. __('Duration', 'powerpress') .'" name="MetaMarks['.esc_attr($feed_slug).']['.esc_attr($next_row).'][duration]" value="' .htmlspecialchars($data['duration']) .'" placeholder="'. htmlspecialchars(__('Duration', 'powerpress'))  .'" /></div></div>';
    $html .= '<div class="pp-section-container"><div class="powerpress-label-container" style="width: 100%;"><label class="pp-ep-box-label" for="pp-metamark-link-' . esc_attr($feed_slug) .'-'. esc_attr($next_row) .'">' . __('Link', 'powerpress') . '</label><input id="pp-metamark-link-'. esc_attr($feed_slug) .'-'. esc_attr($next_row) .'" class="pp-ep-box-input" style="width: 100%;" type="text" title="'. __('Link', 'powerpress') .'" name="MetaMarks['.esc_attr($feed_slug).']['.esc_attr($next_row).'][link]" value="' .htmlspecialchars($data['link']) .'" placeholder="'. htmlspecialchars(__('Link', 'powerpress'))  .'" /></div></div>';
    $html .= '<div class="pp-section-container" id="pp-value-container-' . esc_attr($feed_slug) .'-'. esc_attr($next_row) . '"><div class="powerpress-label-container" style="width: 100%;"><label class="pp-ep-box-label" for="pp-metamark-val-' . esc_attr($next_row) .'">' . __('Value', 'powerpress') . '</label><textarea id="pp-metamark-val-'. esc_attr($feed_slug) .'-'. esc_attr($next_row) .'" class="pp-ep-box-input" style="width: 100%;" name="MetaMarks['.esc_attr($feed_slug).']['.esc_attr($next_row).'][value]" title="'. __('Value', 'powerpress') .'" placeholder="'. htmlspecialchars(__('Value', 'powerpress'))  .'">' .htmlspecialchars($data['value']) .'</textarea></div></div>';


    $html .= '</div></div>';
    $html .= "\n";
    return $html;
}

function powerpress_metamarks_print_rss2($episode_data)
{
    $MetaRecords = powerpress_metamarks_get($episode_data['id'], $episode_data['feed'] );
    foreach( $MetaRecords as $index => $MetaMark )
    {
        echo "\t\t";
        echo '<rawvoice:metamark type="'. esc_attr($MetaMark['type']) .'"';
        if( !empty($MetaMark['duration']) )
            echo ' duration="'. esc_attr($MetaMark['duration']) .'"';
        if( !empty($MetaMark['position']) )
            echo ' position="'. esc_attr($MetaMark['position']) .'"';
        if( !empty($MetaMark['link']) )
            echo ' link="'. esc_attr($MetaMark['link']) .'"';

        $value = trim($MetaMark['value']);
        if( $value == '' ) {
            echo ' />';
        } else {
            echo '>';
            echo htmlspecialchars($value);
            echo '</rawvoice:metamark>';
        }
        echo PHP_EOL;
    }
}

function powerpress_metamarks_get_types()
{
    $types = array();
    $types['audio'] = 'Audio';
    $types['video'] = 'Video';
    $types['image'] = 'Image';
    $types['comment'] = 'Comment';
    $types['tag'] = 'Tag';
    $types['ad'] = 'Advertisement';
    $types['lowerthird'] = 'Lower Third';
    return $types;
}


function powerpress_metamarks_get($post_id, $feed_slug)
{
    $return = array();
    if( $post_id )
    {
        $return = get_post_meta($post_id, '_'. $feed_slug .':metamarks', true);
        if( $return == false )
            $return  = array();
    }

    return $return;
}