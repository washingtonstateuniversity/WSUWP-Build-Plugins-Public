<?php
$error = '';
$success = '';
if (!empty($_POST)) {
    $url = parse_url($_POST['feedUrl']);
    $feedUrl = (isset($url['host']) ? $url['host'] : "ERROR") .  $url['path'];
    if (!empty($url['query'])){
        $feedUrl.='?'.$url['query'];
    }
    $feedUrl = urlencode($feedUrl);

    $post = false;
    $requestUrl = '/2/powerpress/network/' . $props['powerpress_network']['network_id'] . '/applicant/findshow?feedUrl='.$feedUrl ;
    $results = $GLOBALS['ppn_object']->requestAPI($requestUrl, true, $post);
    if (isset($results['program_id'])) {
        $requestUrl = '/2/powerpress/network/' . $props['powerpress_network']['network_id'] . '/applicant/submit';
        $requestUrl .= '?feedUrl=' . $_POST['feedUrl'] . '&programId=' . $results['program_id'];
        $requestUrl .= '&webName=' . $results['program_keyword'];
        $requestUrl .= '&listId=' . $_POST['list_id'];
        $submit = $GLOBALS['ppn_object']->requestAPI($requestUrl, true, $post);
        if(isset($submit->danger)){
            $error = 'Application could not be submitted. If you have not already submitted an application, please contact the network administrator.';
        } else {
            $success = 'Application successfully submitted!';
        }
    } else {
        $error = "Show could not be found in Blubrry directory. Please double check your URL or contact Blubrry support.";
    }
}
?>
<link href="<?php echo PowerPressNetwork::powerpress_network_plugin_url(). "css/style.css";?>" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
<meta charset="utf-8">
<html>

<div class="ppn-form-div">
<?php if ($error != '') {
    ?>
    <div class="sub-error">
        <div class="sub-error-icon">
            <div class="sub-error-icon-check"></div>
        </div>
        <div class="sub-alert-text"><?php echo $error; ?></div>
    </div>
    <?php
    $error = '';
} ?>
<?php if ($success != '') {
    ?>
    <div class="sub-success">
        <div class="sub-success-icon">
            <div class="sub-success-icon-check"></div>
        </div>
        <div class="sub-alert-text"><?php echo $success; ?></div>
    </div>
    <?php
    $error = '';
} ?>
    <div class="sub-form">
        <form method="POST">
            <fieldset class="app-form-border">
                <div class="form-group">
                    <label for="feedUrl" class="sr-only">RSS Feed URL:</label>
                    <p class="app-paragraphs">RSS Feed URL: </p>
                    <input id="feedUrl" name="feedUrl" class="form-control"
                           placeholder="www.example.com/rss/feed" required autofocus>
                    <br/>
                    <label for="list_select" class="sr-only">List: </label>
                    <p class="app-paragraphs">List: </p>
                    <select class="form-control" id="list_select" name="list_id">
                        <?php foreach ($props['lists'] as $pos => $info) {
                            echo '<option value="' . esc_html($info['list_id']) . '">' . esc_html(($info['list_title'])) . '</option>';
                        } ?>
                    </select>

                    <label for="terms" class="sr-only">Terms Agreement</label>
                    <br>
                    <input id="sub-checkbox" class="sub-checkbox-cl" type="checkbox" name="terms" value="agree" required/>
                    <label for="sub-checkbox" class="sub-checkbox-cl-label">I agree to network <a target="_blank" href="<?php if (isset($props['terms-url']))echo  esc_url(($props['terms-url'])); else echo '#';?>">terms and
                        conditions</a>.
                    </label>
                    <br><br>
                    <button class="sub-btn" type="submit">Submit
                    </button>
                </div>
            </fieldset>

        </form>
    </div>

</div>
</html>
