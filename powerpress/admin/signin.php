<div class="container">
    <h1 class="pageTitle"><?php echo esc_html(__('Signin Blubrry Account', 'powerpress-network'));?></h1><br>
     <form method ="POST" action="" id="signinForm"> <!-- Make sure to keep back slash there for WordPress -->
        <button name ="signinRequest" type="submit" class="primaryButton" id="signinButton"><?php echo esc_html(__('Login', 'powerpress-network'));?></button>
		<input type="hidden" name="ppn-action" value="link-account" />
     </form>
</div>
