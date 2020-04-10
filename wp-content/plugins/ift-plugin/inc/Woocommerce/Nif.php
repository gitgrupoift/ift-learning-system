<?php

namespace IFT\Woocommerce;

class Nif {

    
    public function __construct() {
        
        add_filter('woocommerce_billing_fields', array($this, 'woocommerce_nif_checkout'));
        add_filter('woocommerce_admin_billing_fields', array($this, 'woocommerce_nif_admin_billing_fields'));
        add_filter('woocommerce_ajax_get_customer_details', array($this, 'ajax_get_customer_details'));
        add_filter('woocommerce_email_customer_details_fields', array($this, 'nif_email_customer_details_fields'));
        
        add_action('woocommerce_customer_meta_fields', array($this, 'nif_customer_meta_fields'));
        add_action('woocommerce_order_details_after_customer_details', array($this, 'nif_thank_you'));
        
    }
    
    /*
     * Inclui o campo do NIF/NIPC no checkout
     * @since 1.1.3
     * @updated 1.1.3
     *
     */
    public function woocommerce_nif_checkout($fields) {

			$fields['billing_nif'] = array(
				'type'			=>	'text',
				'label'			=> apply_filters( 'woocommerce_nif_field_label', __('NIF / NIPC', 'woocommerce_nif') ),
				'placeholder'	=> apply_filters( 'woocommerce_nif_field_placeholder', _x('Número de Contribuinte - Portugal', 'placeholder', 'woocommerce_nif') ),
				'class'			=> apply_filters( 'woocommerce_nif_field_class', array('form-row-first') ),
				'required'		=> apply_filters( 'woocommerce_nif_field_required', false ),
				'clear'			=> apply_filters( 'woocommerce_nif_field_clear', true ),
				'autocomplete'	=> apply_filters( 'woocommerce_nif_field_autocomplete', 'on' ),
				'priority'		=> apply_filters( 'woocommerce_nif_field_priority', 120 ),
				'maxlength'		=> apply_filters( 'woocommerce_nif_field_maxlength', 9 ),
				'validate'		=> ( apply_filters( 'woocommerce_nif_field_validate', false ) ? array('nif_pt') : array() ),
			);
        
		return $fields;
	}

    /*
     * Inclui o campo do NIF/NIPC nas encomendas, no admin
     * @since 1.1.3
     * @updated 1.1.3
     *
     */
    public function woocommerce_nif_admin_billing_fields( $billing_fields ) {
        
		global $post;
        
		if ($post->post_type=='shop_order') {

				$billing_fields['nif']=array(
					'label' => apply_filters( 'woocommerce_nif_field_label', __( 'NIF / NIPC', 'woocommerce_nif' ) ),
				);
                
        }
        
		return $billing_fields;
	}
    

    public function ajax_get_customer_details($customer_data, $customer, $user_id ) {
        
		$customer_data['billing']['nif'] = $customer->get_meta('billing_nif');
		        
		return $customer_data;
        
	}
    
    public function nif_customer_meta_fields($show_fields) {
        
		if (isset($show_fields['billing']) && is_array($show_fields['billing']['fields'])) {
			$show_fields['billing']['fields']['billing_nif']=array(
				'label' => apply_filters( 'woocommerce_nif_field_label', __( 'NIF / NIPC', 'woocommerce_nif' ) ),
				'description' => '',
			);
		}
        
		return $show_fields;
	}
    
    public function nif_thank_you($order) {
        
        $billing_nif = version_compare( WC_VERSION, '3.0', '>=' ) ? $order->get_meta( '_billing_nif' ) : $order->billing_nif;
        
        ?>
			<tr>
				<th><?php echo apply_filters( 'woocommerce_nif_field_label', __( 'NIF / NIPC', 'woocommerce_nif' ) ); ?>:</th>
				<td><?php echo esc_html( $billing_nif ); ?></td>
			</tr>
        <?php
	}
    
    public function nif_email_customer_details_fields($fields, $sent_to_admin, $order) {
        
		$billing_nif = version_compare( WC_VERSION, '3.0', '>=' ) ? $order->get_meta( '_billing_nif' ) : $order->billing_nif;
        
		if ($billing_nif) {
			$fields['billing_nif'] = array(
				'label' => apply_filters( 'woocommerce_nif_field_label', __('NIF / NIPC', 'woocommerce_nif') ),
				'value' => wptexturize( $billing_nif )
			);
		}
        
		return $fields;
	}
    
}