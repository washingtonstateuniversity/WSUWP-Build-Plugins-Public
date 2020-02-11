<?php

function powerpress_metabox_save($post_ID)
{
    $MetaMarks = ( !empty($_POST['MetaMarks']) ? $_POST['MetaMarks'] : false);
    $Episodes = ( !empty($_POST['Powerpress']) ? $_POST['Powerpress'] : false);
    $currentSeason = (!empty($_POST['General']['current_season']) ? $_POST['General']['current_season'] : false);
    if ($currentSeason) {
        $General = array('current_season' => $currentSeason);
        powerpress_save_settings($General, 'powerpress_general');
    }
    if( $Episodes )
    {
        foreach( $Episodes as $feed_slug => $Powerpress )
        {
            $field = '_'.$feed_slug.':metamarks';

            if( !empty($Powerpress['remove_podcast']) )
            {
                delete_post_meta( $post_ID, $field);
            }
            else if( !empty($Powerpress['change_podcast']) || !empty($Powerpress['new_podcast']) )
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
    $html = '<div class="pp-metamarks-row" id="powerpress_metamarks_row_'. $feed_slug .'_'. $next_row .'">';
    $html .= '<div class="metamark-top-section id="metamark-top-section-' . $feed_slug .'-'. $next_row . '">';
    $html .= '<div id="metamark-preview-pos-' . $feed_slug .'-'. $next_row . '" class="metamark-preview-pos">' . $pos . '</div>';
    $html .= '<div id="metamark-preview-type-' . $feed_slug .'-'. $next_row . '" class="metamark-preview-type">' . $type . '</div>';
    $html .= '<div class="pp-metamark-delete"><a href="#" onclick="return powerpress_metamarks_deleterow(\'powerpress_metamarks_row_'. $feed_slug .'_'. $next_row .'\');" title="'. __('Delete', 'powerpress') .'">';
    $html .= __('Delete', 'powerpress') . '</a></div><div class="pp-metamark-edit"><a href="#" id="toggle-metamark-'. $next_row . '-' . $feed_slug . '" title="'. __('Save', 'powerpress') .'" onclick="toggleMetamarksSettings(this)">' . __($option, 'powerpress') . '</a></div>';
    $html .= '</div><div id="hide-metamark-' . $feed_slug .'-'. $next_row . '"' . $class . '>';
    $html .= '<div class="metamark-input-container"><div class="pp-metamark-label-container" id="type-label-' . $feed_slug .'-'. $next_row . '"><label class="pp-metamark-label" for="metamark-type-'. $feed_slug .'_'. $next_row .'">' . __('Type', 'powerpress') . '</label><select id="metamark-type-'. $feed_slug .'-'. $next_row .'" class="ep-box-input" type="text" title="'. __('Type', 'powerpress') .'" name="MetaMarks['.$feed_slug.']['.$next_row.'][type]">';
    $html .= powerpress_print_options( array(''=>'Select Type')+ $MarkTypes, $data['type'], true);
    $html .= '</select></div><div class="pp-metamark-label-container" id="pos-label-' .  $feed_slug .'-'. $next_row . '"><label class="pp-metamark-label" for="metamark-pos-'.  $feed_slug .'-'. $next_row .'">' . __('Position', 'powerpress') . '</label>';
    $html .= '<input id="metamark-pos-'. $feed_slug .'-'. $next_row .'" class="ep-box-input" type="text" title="'. __('Position', 'powerpress') .'" name="MetaMarks['.$feed_slug.']['.$next_row.'][position]" value="' .htmlspecialchars($data['position']) .'" placeholder="'. htmlspecialchars(__('Position', 'powerpress'))  .'" /></div>';
    $html .= '<div class="pp-metamark-label-container" id="dur-label-' .  $feed_slug .'-'. $next_row . '"><label class="pp-metamark-label" for=\"metamark-dur-'.  $feed_slug .'-'. $next_row .'\">' . __('Duration', 'powerpress') . '</label>';
    $html .= '<input id="metamark-dur-'. $feed_slug .'-'. $next_row .'" class="ep-box-input" type="text" title="'. __('Duration', 'powerpress') .'" name="MetaMarks['.$feed_slug.']['.$next_row.'][duration]" value="' .htmlspecialchars($data['duration']) .'" placeholder="'. htmlspecialchars(__('Duration', 'powerpress'))  .'" /></div></div>';
    $html .= '<div class="metamark-input-container"><label class="pp-metamark-label" for="metamark-link-'. $feed_slug .'-'. $next_row .'">' . __('Link', 'powerpress') . '</label><input id="metamark-link-'. $feed_slug .'-'. $next_row .'" class="ep-box-input" type="text" title="'. __('Link', 'powerpress') .'" name="MetaMarks['.$feed_slug.']['.$next_row.'][link]" value="' .htmlspecialchars($data['link']) .'" placeholder="'. htmlspecialchars(__('Link', 'powerpress'))  .'" /></div>';
    $html .= '<div class="metamark-input-container" id="value-container-' . $feed_slug .'-'. $next_row . '"><label class="pp-metamark-label" for="metamark-val-'. $next_row .'">' . __('Value', 'powerpress') . '</label><textarea id="metamark-val-'. $feed_slug .'-'. $next_row .'" class="ep-box-input" name="MetaMarks['.$feed_slug.']['.$next_row.'][value]" title="'. __('Value', 'powerpress') .'" placeholder="'. htmlspecialchars(__('Value', 'powerpress'))  .'">' .htmlspecialchars($data['value']) .'</textarea></div>';


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