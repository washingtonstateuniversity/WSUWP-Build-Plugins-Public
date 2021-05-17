<?php
/**
 * Created by PhpStorm.
 * User: Atlas_Gondal
 * Date: 4/9/2016
 * Time: 9:01 AM
 */

function eau_get_selected_post_type($post_type, $custom_posts_names)
{

    switch ($post_type) {

        case "any":

            $type = "any";
            break;

        case "page":

            $type = "page";
            break;

        case "post":

            $type = "post";
            break;

        default:

            for ($i = 0; $i < count($custom_posts_names); $i++) {

                if ($post_type == $custom_posts_names[$i]) {

                    $type = $custom_posts_names[$i];

                }

            }

    }

    return $type;


}

function eau_extract_relative_url ($url)
{
    return preg_replace ('/^(http)?s?:?\/\/[^\/]*(\/?.*)$/i', '$2', '' . $url);
}

function eau_is_checked($name, $value)
{
    foreach ($name as $data) {
        if ($data == $value) {
            return true;
        }
    }

    return false;
}


/**
 * @param $selected_post_type
 * @param $post_status
 * @param $post_author
 * @param $post_per_page
 * @param $offset
 * @param $export_type
 * @param $additional_data
 * @param $csv_path
 * @param $csv_name
 * @param $posts_from
 * @param $posts_upto
 */
function eau_generate_output($selected_post_type, $post_status, $post_author, $remove_woo_attributes, $exclude_domain, $post_per_page, $offset, $export_type, $additional_data, $csv_path, $csv_name, $posts_from, $posts_upto)
{

    $html = array();
    $counter = 0;

    if ($export_type == "here") {
        $line_break = "<br/>";
    } else {
        $line_break = "";
    }

    if ($post_author == "all") {
        $post_author = "";
    }

    if ($post_per_page == "all" && $offset == "all") {
        $post_per_page = -1;
        $offset = "";
    }

    switch ($post_status) {
        case "all":
            $post_status = array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'trash');
            break;
        case 'publish':
            $post_status = 'publish';
            break;
        case 'pending':
            $post_status = 'pending';
            break;
        case 'draft':
            $post_status = 'draft';
            break;
        case 'future':
            $post_status = 'future';
            break;
        case 'private':
            $post_status = 'private';
            break;
        case 'trash':
            $post_status = 'trash';
            break;
        default:
            $post_status = 'publish';
            break;
    }

    $posts_query = new WP_Query(array(
        'post_type' => $selected_post_type,
        'post_status' => $post_status,
        'author' => $post_author,
        'posts_per_page' => $post_per_page,
        'offset' => $offset,
        'orderby' => 'title',
        'order' => 'ASC',
        'date_query' => array(
            array(
                'after' => $posts_from,
                'before' => $posts_upto,
                'inclusive' => true,
            ),
        )
    ));

    if (!$posts_query->have_posts()) {
        echo "no result found in that range, please <strong>reselect and try again</strong>!";
        return;
    }

    if (eau_is_checked($additional_data, 'postIDs')) {

        while ($posts_query->have_posts()):

            $html['post_id'][$counter] = (isset($html['post_id'][$counter]) ? "" : null);

            $posts_query->the_post();
            $html['post_id'][$counter] .= get_the_ID() . $line_break;
            $counter++;

        endwhile;

        $counter = 0;

    }

    if (eau_is_checked($additional_data, 'url')) {

        while ($posts_query->have_posts()):

            $html['url'][$counter] = (isset($html['url'][$counter]) ? "" : null);

            $posts_query->the_post();
            $html['url'][$counter] .= $exclude_domain == 'yes' ? eau_extract_relative_url(get_permalink()) : get_permalink() . $line_break;
            $counter++;

        endwhile;

        $counter = 0;

    }

    if (eau_is_checked($additional_data, 'title')) {

        while ($posts_query->have_posts()):

            $html['title'][$counter] = (isset($html['title'][$counter]) ? "" : null);

            $posts_query->the_post();
            $html['title'][$counter] .= get_the_title() . $line_break;
            $counter++;

        endwhile;

        $counter = 0;

    }

    if (eau_is_checked($additional_data, 'category')) {

        while ($posts_query->have_posts()):

            $html['category'][$counter] = (isset($html['category'][$counter]) ? "" : null);
            $html['taxonomy'][$counter] = (isset($html['taxonomy'][$counter]) ? "" : null);

            $categories = '';
            $taxonomies_list = '';
            $posts_query->the_post();
            $cats = get_the_category();
            $post_type = get_post_type(get_the_ID());
            $taxonomies = get_object_taxonomies($post_type);
            $taxonomy_names = wp_get_object_terms(get_the_ID(), $taxonomies, array("fields" => "names"));
            if (!empty($cats)) :
                foreach ($cats as $index => $cat) :
                    $categories .= !empty($cat) ? $index == 0 ? $cat->name : ", " . $cat->name : '';
                endforeach;
            endif;

            if ($remove_woo_attributes == 'yes' && $post_type == 'product') {
                $terms = get_the_terms( get_the_ID(), 'product_cat' );
                if(isset($terms) && !is_wp_error($terms)) {
                    foreach ($terms as $index => $term) {
                        $taxonomies_list .= !empty($term->name) ? $index == 0 ? $term->name : ", " . $term->name : '';
                    }
                }
            }else{
                if (!empty($taxonomy_names)) {
                    foreach ($taxonomy_names as $index => $tax_name) :
                        $taxonomies_list .= !empty($tax_name) ? $index == 0 ? $tax_name : ", " . $tax_name : '';
                    endforeach;
                }
            }

            $html['category'][$counter] .= !empty($categories) ? $categories . $line_break : '';
            $html['taxonomy'][$counter] .= !empty($taxonomies_list) ? $taxonomies_list . $line_break : '';

            $counter++;

        endwhile;

        $counter = 0;

    }
    eau_export_data($html, $export_type, $csv_path, $csv_name);

    wp_reset_postdata();
}

function eau_export_data($urls, $export_type, $csv_path, $csv_name)
{

    $file_path = wp_upload_dir();

    $count = 0;
    foreach ($urls as $item) {
        $count = count($item);
    }


    switch ($export_type) {

        case "text":

            $data = '';
            $headers = array();

            $file = $csv_path . $csv_name . '.CSV';
            $myfile = @fopen($file, "w") or die("Unable to create a file on your server!");
            fprintf($myfile, "\xEF\xBB\xBF");

            $headers[] = 'Post ID';
            $headers[] = 'Title';
            $headers[] = 'URLs';
            $headers[] = 'Categories';

            fputcsv($myfile, $headers);

            for ($i = 0; $i < $count; $i++) {
                $data = array(
                    isset($urls['post_id']) ? $urls['post_id'][$i] : "",
                    isset($urls['title']) ? $urls['title'][$i] : "",
                    isset($urls['url']) ? $urls['url'][$i] : "",
                    isset($urls['category']) ? !empty($urls['category'][$i]) || !empty($urls['taxonomy'][$i]) ? $urls['category'][$i] . $urls['taxonomy'][$i] : "" : ""
                );

                fputcsv($myfile, $data);
            }

            fclose($myfile);

            echo "<div class='updated' style='width: 97%'>Data exported successfully! <a href='" . $file_path['baseurl'] . "/" . $csv_name . ".CSV' target='_blank'><strong>Click here</strong></a> to Download.</div>";
            echo "<div class='notice notice-warning' style='width: 97%'>Once you have downloaded the file, it is recommended to delete file from the server, for security reasons. <a href='".wp_nonce_url(admin_url('tools.php?page=extract-all-urls-settings&del=y&f=').base64_encode($file))."' ><strong>Click Here</strong></a> to delete the file. And don't worry, you can always regenerate anytime. :)</div>";
            echo "<div class='notice notice-info' style='width: 97%'><strong>Total</strong> number of links: <strong>".$count."</strong>.</div>";

            break;

        case "here":

            echo "<h1 align='center' style='padding: 10px 0;'><strong>Below is a list of Exported Data:</strong></h1>";
            echo "<h2 align='center' style='font-weight: normal;'>Total number of links: <strong>".$count."</strong>.</h2>";
            echo "<table class='form-table' id='outputData'>";
            echo "<tr><th>#</th>";
            echo isset($urls['post_id']) ? "<th id='postID'>Post ID</th>" : null;
            echo isset($urls['title']) ? "<th id='postTitle'>Title</th>" : null;
            echo isset($urls['url']) ? "<th id='postURL'>URLs</th>" : null;
            echo isset($urls['category']) ? "<th id='postCategories'>Categories</th>" : null;

            echo "</tr>";

            for ($i = 0; $i < $count; $i++) {

                $id = $i + 1;
                echo "<tr><td>" . $id . "</td>";
                echo isset($urls['post_id']) ? "<td>".$urls['post_id'][$i]."</td>" : null;
                echo isset($urls['title']) ? "<td>" . $urls['title'][$i] . "</td>" : null;
                echo isset($urls['url']) ? "<td>" . $urls['url'][$i] . "</td>" : null;
                echo isset($urls['category']) ?  "<td>".$urls['category'][$i] . $urls['taxonomy'][$i] . "</td>" : null;

                echo "</tr>";
            }

            echo "</table>";

            break;

        default:

            echo "Sorry, you missed export type, Please <strong>Select Export Type</strong> and try again! :)";
            break;


    }


}