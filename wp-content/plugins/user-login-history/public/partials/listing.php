<?php
/**
 * Template file to render listing table for public.
 *
 * @link       https://github.com/faiyazalam
 *
 * @package    User_Login_History
 * @subpackage User_Login_History/public/partials
 */
?>
<div class="<?php echo $this->plugin_name . '-wrapper'; ?>">
   <?php do_action('faulh_public_before_search_form') ?>
<?php echo !empty($attributes['title']) ?  "<div class='".$this->plugin_name."-listing_title'>".$attributes['title']."</div>": ""?>
<form name="<?php echo $this->plugin_name . '-search-form'; ?>" method="get" action="" id="<?php echo $this->plugin_name . '-search-form'; ?>">

    <?php do_action('faulh_public_listing_search_form'); ?>
</form>
<?php do_action('faulh_public_after_search_form') ?>
    <hr>
<div>
    <?php
    if (!empty($attributes['show_timezone_selector']) && "true" == $attributes['show_timezone_selector']) {
        ?>
      

        <?php
    }
    ?>
</div>
<?php do_action('faulh_public_before_listing_table') ?>
<?php
$Public_List_Table->display();
?>
<?php do_action('faulh_public_after_listing_table') ?>
</div>