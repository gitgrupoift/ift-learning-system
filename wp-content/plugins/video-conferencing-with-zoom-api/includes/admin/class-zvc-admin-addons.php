<?php

/**
 * Class for displaying addons page
 *
 * @since 3.1.1
 */
class Zoom_Video_Conferencing_Admin_Addons {

	public function __construct() {
	}

	public static function render() {
		?>
        <div class="wrap video-conferencing-addons">
            <h3 class="border-padd">Get more features to your Meetings !</h3>
            <div class="video-conferencing-addons-flex">
                <div class="video-conferencing-addons-box">
                    <div class="image">
                        <img width="100" src="<?php echo ZVC_PLUGIN_DIR_URL; ?>assets/images/bookings-icon.png" alt="WooCommerce Booking">
                    </div>
                    <div class="content">
                        <h1 style="line-height: 1.6;">WooCommerce and WooCommerce Booking Integration</h1>
                        <p>Integrate your Zoom Meetings directly to WooCommerce products. This integration allows you to seamlessly integrate your WooCommerce product into a Zoom Meeting post created from your WordPress dashboard. To make zoom meeting a purchasable product. You'll find an option in your <strong>Zoom Meeting > Add New page</strong>. Checking the option would convert your normal meeting to purchasable meeting page.</p>
                        <p>Also, you can integrate your Zoom Meetings directly to WooCommerce booking products. Zoom Integration for WooCommerce Booking allows you
                            to automate your zoom meetings directly from your WordPress dashboard by linking zoom meetings to your WooCommerce Booking
                            products automatically when a Booking Product is created. Users will receive join links in their booking confirmation
                            emails.</p>
                        <a href="https://www.codemanas.com/downloads/zoom-integration-for-woocommerce-booking/" class="button button-primary">More Details</a>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

}