<?php
/*
Plugin Name: PayU India
Plugin URI: https://payu.in/
Description: Extends WooCommerce with PayU.
Version: 3.8.2
Author: PayU
Author URI: https://payu.in/
Copyright: Â© 2020, PayU. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) )
{
    exit; // Exit if accessed directly
}

add_action('plugins_loaded', 'woocommerce_payubiz_init', 0);

function woocommerce_payubiz_init() {

  if ( !class_exists( 'WC_Payment_Gateway' ) ) return;  
 
  /**
   * Localisation
   */
   
  if(isset($_GET['msg'])){
	if(sanitize_text_field($_GET['msg'])!='')
		add_action('the_content', 'showpayubizMessage');
  }
  
  function showpayubizMessage($content){
    return '<div class="box '.sanitize_text_field($_GET['type']).'-box">'.esc_html__(sanitize_text_field($_GET['msg']),'payubiz').'</div>'.$content;
  }
  /**
   * Gateway class
   */
  class WC_Payubiz extends WC_Payment_Gateway {
    protected $msg = array();
	
	protected $logger;
	
    public function __construct(){
		global $wpdb;
      // Go wild in here	  
      $this -> id = 'payubiz';
      $this -> method_title = __('PayUBiz', 'payubiz');	  
      $this -> icon = plugins_url('images/payubizlogo.png',__FILE__);
      $this -> has_fields = false;
      $this -> init_form_fields();
      $this -> init_settings();
      $this -> title = 'PayUBiz'; //$this -> settings['title'];
      $this -> description = sanitize_text_field($this -> settings['description']);
      $this -> gateway_module = sanitize_text_field($this -> settings['gateway_module']);
      $this -> redirect_page_id = sanitize_text_field($this -> settings['redirect_page_id']);
	  
	  $this -> currency1 = sanitize_text_field($this -> settings['currency1']);	
	  $this -> currency1_payu_key = sanitize_text_field($this -> settings['currency1_payu_key']);
	  $this -> currency1_payu_salt = sanitize_text_field($this -> settings['currency1_payu_salt']);	  

	  $this -> currency2 = sanitize_text_field($this -> settings['currency2']);	
	  $this -> currency2_payu_key = sanitize_text_field($this -> settings['currency2_payu_key']);
	  $this -> currency2_payu_salt = sanitize_text_field($this -> settings['currency2_payu_salt']);	  

	  $this -> currency3 = sanitize_text_field($this -> settings['currency3']);	
	  $this -> currency3_payu_key = sanitize_text_field($this -> settings['currency3_payu_key']);
	  $this -> currency3_payu_salt = sanitize_text_field($this -> settings['currency3_payu_salt']);	  
	  
	  $this -> currency4 = sanitize_text_field($this -> settings['currency4']);	
	  $this -> currency4_payu_key = sanitize_text_field($this -> settings['currency4_payu_key']);
	  $this -> currency4_payu_salt = sanitize_text_field($this -> settings['currency4_payu_salt']);	  
	  
	  $this -> currency5 = sanitize_text_field($this -> settings['currency5']);	
	  $this -> currency5_payu_key = sanitize_text_field($this -> settings['currency5_payu_key']);
	  $this -> currency5_payu_salt = sanitize_text_field($this -> settings['currency5_payu_salt']);

	  $this -> currency6 = sanitize_text_field($this -> settings['currency6']);	
	  $this -> currency6_payu_key = sanitize_text_field($this -> settings['currency6_payu_key']);
	  $this -> currency6_payu_salt = sanitize_text_field($this -> settings['currency6_payu_salt']);
	  
	  $this -> currency7 = sanitize_text_field($this -> settings['currency7']);	
	  $this -> currency7_payu_key = sanitize_text_field($this -> settings['currency7_payu_key']);
	  $this -> currency7_payu_salt = sanitize_text_field($this -> settings['currency7_payu_salt']);
	  
	  $this -> currency8 = sanitize_text_field($this -> settings['currency8']);	
	  $this -> currency8_payu_key = sanitize_text_field($this -> settings['currency8_payu_key']);
	  $this -> currency8_payu_salt = sanitize_text_field($this -> settings['currency8_payu_salt']);
	  
	  $this -> currency9 = sanitize_text_field($this -> settings['currency9']);	
	  $this -> currency9_payu_key = sanitize_text_field($this -> settings['currency9_payu_key']);
	  $this -> currency9_payu_salt = sanitize_text_field($this -> settings['currency9_payu_salt']);
	  
	  $this -> currency10 = sanitize_text_field($this -> settings['currency10']);	
	  $this -> currency10_payu_key = sanitize_text_field($this -> settings['currency10_payu_key']);
	  $this -> currency10_payu_salt = sanitize_text_field($this -> settings['currency10_payu_salt']);
	  
	  $this->bypass_verify_payment=false;
	  
	  if(sanitize_text_field($this -> settings['verify_payment'])!="yes")
		$this->bypass_verify_payment=true;
	
	  $this -> msg['message'] = "";
      $this -> msg['class'] = "";
	
		
      add_action('init', array(&$this, 'check_payubiz_response'));
      //update for woocommerce >2.0
      add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_payubiz_response' ) );

      add_action('valid-payubiz-request', array(&$this, 'SUCCESS'));
	  add_action('woocommerce_receipt_payubiz', array(&$this, 'receipt_page'));
	  //add_action('woocommerce_thankyou_payubiz',array($this, 'thankyou')); 	  
  
      if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
      } else {
        add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
      }
		
	  $this->logger = wc_get_logger();
    }
    
	/**
	* Session patch CSRF Samesite=None; Secure
	**/
	function manage_session()
	{
		$context = array( 'source' => $this->id );
		try
		{
			if(PHP_VERSION_ID >= 70300)
			{
				$options = session_get_cookie_params();  
				$options['samesite'] = 'None';
				$options['secure'] = true;
				unset($options['lifetime']); 
				$cookies = $_COOKIE;  	
				foreach ($cookies as $key => $value)
				{
					if (!preg_match('/cart/', sanitize_key($key)))
						setcookie(sanitize_key($key), sanitize_text_field($value), $options);
				}
			}
			else {
				$this->logger->error( "PayU payment plugin does not support this PHP version for cookie management. 
				Required PHP v7.3 or higher.", $context );
			}
		}
		catch(Exception $e) {
			$this->logger->error( $e->getMessage(), $context );
		}
	}
	
	
    function init_form_fields(){

      $this -> form_fields = array(
        'enabled' => array(
            'title' => __('Enable/Disable', 'payubiz'),
            'type' => 'checkbox',
						'label' => __('Enable PayU', 'payubiz'),
            'default' => 'no'),
		  'description' => array(
			'title' => __('Description:', 'payubiz'),
			'type' => 'textarea',
			'description' => __('This controls the description which the user sees during checkout.', 'payubiz'),
			'default' => __('Pay securely by Credit or Debit card or net banking through PayU.', 'payubiz')),
          'gateway_module' => array(
            'title' => __('Gateway Mode', 'payubiz'),
            'type' => 'select',
            'options' => array("0"=>"Select","sandbox"=>"Sandbox","production"=>"Production"),
            'description' => __('Mode of gateway subscription.','payubiz')
            ),
		  'currency1' => array(
            'title' => __('Currency 1', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 1 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency1_payu_key' => array(
            'title' => __('PayU Key for Currency 1', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency1_payu_salt' => array(
            'title' => __('PayU Salt for Currency 1', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		  'currency2' => array(
            'title' => __('Currency 2', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 2 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency2_payu_key' => array(
            'title' => __('PayU Key for Currency 2', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency2_payu_salt' => array(
            'title' => __('PayU Salt for Currency 2', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		   'currency3' => array(
            'title' => __('Currency 3', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 3 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency3_payu_key' => array(
            'title' => __('PayU Key for Currency 3', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency3_payu_salt' => array(
            'title' => __('PayU Salt for Currency 3', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		  'currency4' => array(
            'title' => __('Currency 4', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 4 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency4_payu_key' => array(
            'title' => __('PayU Key for Currency 4', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency4_payu_salt' => array(
            'title' => __('PayU Salt for Currency 4', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		  'currency5' => array(
            'title' => __('Currency 5', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 5 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency5_payu_key' => array(
            'title' => __('PayU Key for Currency 5', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency5_payu_salt' => array(
            'title' => __('PayU Salt for Currency 5', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		  'currency6' => array(
            'title' => __('Currency 6', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 6 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency6_payu_key' => array(
            'title' => __('PayU Key for Currency 6', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency6_payu_salt' => array(
            'title' => __('PayU Salt for Currency 6', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		  'currency7' => array(
            'title' => __('Currency 7', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 7 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency7_payu_key' => array(
            'title' => __('PayU Key for Currency 7', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency7_payu_salt' => array(
            'title' => __('PayU Salt for Currency 7', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		   'currency8' => array(
            'title' => __('Currency 8', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 8 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency8_payu_key' => array(
            'title' => __('PayU Key for Currency 8', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency8_payu_salt' => array(
            'title' => __('PayU Salt for Currency 8', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		  'currency9' => array(
            'title' => __('Currency 9', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 9 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency9_payu_key' => array(
            'title' => __('PayU Key for Currency 9', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency9_payu_salt' => array(
            'title' => __('PayU Salt for Currency 9', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		  'currency10' => array(
            'title' => __('Currency 10', 'payubiz'),
            'type' => 'text',
            'description' =>  __('Currency Code 10 as configured in multi-currency plugin.', 'payubiz')
            ),
		  'currency10_payu_key' => array(
            'title' => __('PayU Key for Currency 10', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant key.', 'payubiz')
            ),
		  'currency10_payu_salt' => array(
            'title' => __('PayU Salt for Currency 10', 'payubiz'),
            'type' => 'text',
            'description' =>  __('PayU merchant salt.', 'payubiz')
            ),
		  'verify_payment' => array(
            'title' => __('Verify Payment', 'payubiz'),
            'type' => 'select',
            'options' => array("0"=>"Select","yes"=>"Yes","no"=>"No"),
            'description' => __('Verify Payment at server.','payubiz')
            ),
          'redirect_page_id' => array(
            'title' => __('Return Page'),
            'type' => 'select',
            'options' => $this -> get_pages('Select Page'),
            'description' => "Post payment redirect URL for which payment is not successful."
            )
		  );
    }
    
    /**
     * Admin Panel Options
     * - Options for bits like 'title' and availability on a country-by-country basis
     **/
    public function admin_options(){
      echo '<h3>'.esc_html__('PayUBiz payment', 'payubiz').'</h3>';
      echo '<p>'.__('PayU most popular payment gateways for online shopping. <input type="button" class="button-primary" value="Create PayU account" style="float:right" onclick="window.open(`https://onboarding.payu.in/app/account?partner_name=WooCommerce&partner_source=Affiliate+Links&partner_uuid=11eb-3a29-70592552-8c2b-0a696b110fde&source=Partner`, `_blank`)">').'</p>';  
	  if(PHP_VERSION_ID < 70300)
		  echo "<h1 style=\"color:red;\">".esc_html__('**Notice: PayU payment plugin requires PHP v7.3 or higher.<br />
		  Plugin will not work properly below PHP v7.3 due to SameSite cookie restriction.','payubiz')."</h1>";
      echo '<table class="form-table">';
      $this -> generate_settings_html();
      echo '</table>';
	  
    }
		
    /**
     *  There are no payment fields for Citrus, but we want to show the description if set.
     **/
    function payment_fields(){
		if($this -> description) echo wpautop(wptexturize($this -> description));
    }
		
    /**
     * Receipt Page
     **/
    function receipt_page($order){
		$this->manage_session(); //Update cookies with samesite 
		echo '<p>'.esc_html__('Thank you for your order, please wait as you will be automatically redirected to PayUBiz.', 'payubiz').'</p>';
		echo $this -> generate_payubiz_form($order);
    }
    
    /**
     * Process the payment and return the result
     **/   
     function process_payment($order_id){
            $order = new WC_Order($order_id);

            if ( version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
                return array(
                    'result' => 'success',
                    'redirect' => add_query_arg('order', $order->id,
                        add_query_arg('key', $order->get_order_key(), $order->get_checkout_payment_url(true)))
                );
            }
            else {
                return array(
                    'result' => 'success',
                    'redirect' => add_query_arg('order', $order->id,
                        add_query_arg('key', $order->get_order_key(), get_permalink(get_option('woocommerce_pay_page_id'))))
                );
            }
        }
    /**
     * Check for valid Citrus server callback
     **/    
    function check_payubiz_response()
	{
		global $woocommerce;
		
		$payu_key = '';
		$payu_salt = '';
		$currency = '';
				
		if ( !isset( $_GET['wc-api'] ) ) {
			//invalid response	
			$this -> msg['class'] = 'error';
			$this -> msg['message'] = esc_html__( 'Invalid payment gateway response...','payubiz' );
			
			wc_add_notice( $this->msg['message'], $this->msg['class'] );
			
			$redirect_url = add_query_arg( array('msg'=> urlencode($this -> msg['message']), 'type'=>$this -> msg['class']), $redirect_url );

			wp_redirect( $redirect_url );
			exit;
		}
		
		if( sanitize_text_field( $_GET['wc-api'] ) == get_class( $this ) )
		{
			$postdata = array();
			//sanitize entire response
			foreach( $_POST as $key=>$val )
			{
				$postdata[$key] = sanitize_text_field( $val );
			}
			if( isset( $postdata['key'] ) )
			{
				switch($postdata['key'])
				{
					case $this->currency1_payu_key:
						$currency= $this->currency1;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency1_payu_salt;
						break;
					case $this->currency2_payu_key:
						$currency= $this->currency2;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency2_payu_salt;
						break;
					case $this->currency3_payu_key:
						$currency= $this->currency3;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency3_payu_salt;
						break;
					case $this->currency4_payu_key:
						$currency= $this->currency4;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency4_payu_salt;
						break;
					case $this->currency5_payu_key:
						$currency= $this->currency5;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency5_payu_salt;
						break;
					case $this->currency6_payu_key:
						$currency= $this->currency6;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency6_payu_salt;
						break;
					case $this->currency7_payu_key:
						$currency= $this->currency7;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency7_payu_salt;
						break;
					case $this->currency8_payu_key:
						$currency= $this->currency8;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency8_payu_salt;
						break;
					case $this->currency9_payu_key:
						$currency= $this->currency9;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency9_payu_salt;
						break;
					case $this->currency10_payu_key:
						$currency= $this->currency10;
						$payu_key = $postdata['key'];
						$payu_salt = $this->currency10_payu_salt;
						break;
					default:
						break;
				}	
				
				$txnid = $postdata['txnid'];
    	    	$order_id = explode('_', $txnid);
				$order_id = (int)$order_id[0];    //get rid of time part
				
				$order = new WC_Order($order_id);
				
				$order_currency = sanitize_text_field($order->get_currency());
								
				if ($postdata['key'] == $payu_key && $currency == $order_currency && $order_id == WC()->session->get('orderid_awaiting_payubiz')) {
					$amount      		= 	$postdata['amount'];
					$productInfo  		= 	$postdata['productinfo'];
					$firstname    		= 	$postdata['firstname'];
					$email        		=	$postdata['email'];
					$udf5				=   $postdata['udf5'];
					$additionalCharges 	= 	0; 
					If (isset($postdata["additionalCharges"])) $additionalCharges = $postdata['additionalCharges'];
								
					$keyString 	  		=  	$payu_key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
					$keyArray 	  		= 	explode("|",$keyString);
					$reverseKeyArray 	= 	array_reverse($keyArray);
					$reverseKeyString	=	implode("|",$reverseKeyArray);
						
					if (isset($postdata['status']) && $postdata['status'] == 'success') {
						$saltString     = $payu_salt.'|'.$postdata['status'].'|'.$reverseKeyString;					
						if($additionalCharges > 0)
							$saltString     = $additionalCharges.'|'.$payu_salt.'|'.$postdata['status'].'|'.$reverseKeyString;
					
						$sentHashString = strtolower(hash('sha512', $saltString));
						$responseHashString=$postdata['hash'];
				
						$this -> msg['class'] = 'error';
						$this -> msg['message'] = esc_html__('Thank you for shopping with us. However, the transaction has been declined.','payubiz');

						if($sentHashString==$responseHashString && $this->verify_payment($order,$txnid,$payu_key,$payu_salt,$this->bypass_verify_payment))
						{
							$this -> msg['message'] = esc_html__("Thank you for shopping with us. Your account has been charged and your transaction is successful with following order details:",'payubiz');
							$this -> msg['message'] .='<br>'.esc_html__('Order Id: $order_id','payubiz').'<br/>'.esc_html__('Amount: $amount','payubiz').'<br />'.esc_html__('We will be shipping your order to you soon.','payubiz');
						
							if($additionalCharges > 0)
								$this -> msg['message'] .= "<br /><br />".esc_html__('Additional amount charged by PayUBiz - $additionalCharges','payubiz');
										
							$this -> msg['class'] = 'success';
								
							if($order -> status == 'processing' || $order -> status == 'completed' )
							{
								//do nothing
							}
							else
							{								
								//complete the order
								$order -> payment_complete();								
								$order -> add_order_note(esc_html__('PayUBiz has processed the payment. Ref Number: '.$postdata['mihpayid'],'payubiz'));
								$order -> add_order_note($this->msg['message']);
								$order -> add_order_note('Paid by PayUBiz');
								$woocommerce -> cart -> empty_cart();
							}
						
						}
						else {
							//tampered
							$this->msg['class'] = 'error';
							$this->msg['message'] = esc_html__( 'Thank you for shopping with us. However, the payment failed' );
							$order -> update_status('failed');
							$order -> add_order_note('Failed');
							$order -> add_order_note($this->msg['message']);						
						}
					} else {
						$this -> msg['class'] = 'error';
						$this -> msg['message'] = esc_html__( 'Thank you for shopping with us. However, the transaction has been declined.','payubiz' );
						
						//Here you need to put in the routines for a failed
						//transaction such as sending an email to customer
						//setting database status etc etc			
					} 
				}
			}
		
		}
		
		//manage msessages
		if (function_exists('wc_add_notice')) {
			wc_clear_notices();			
			if($this->msg['class']!='success'){
				wc_add_notice( $this->msg['message'], $this->msg['class'] );
			}
		}
		else {
			if($this->msg['class']!='success'){
				$woocommerce->add_error($this->msg['message']);				
			}
			else{
				//$woocommerce->add_message($this->msg['message']);
			}
			$woocommerce->set_messages();
		}
			
		$redirect_url = ($this ->redirect_page_id=='' || $this -> redirect_page_id==0)?get_site_url() . '/':get_permalink($this -> redirect_page_id);
		if($order && $this->msg['class'] == 'success') 
			$redirect_url = $order->get_checkout_order_received_url();
		
		//For wooCoomerce 2.0
		//$redirect_url = add_query_arg( array('msg'=> urlencode($this -> msg['message']), 'type'=>$this -> msg['class']), $redirect_url );
		wp_redirect( $redirect_url );
		exit;
    }
    
	// Adding Meta container admin shop_order pages
	private function verify_payment($order,$txnid,$payu_key,$payu_salt,$bypass=false)
    {
        global $woocommerce;
		
		if($bypass) return true; //bypass verification
		
		try
		{
			$datepaid = $order->get_date_paid();
			$fields = array(
				'key' => sanitize_key($payu_key),
				'command' => 'verify_payment',
				'var1' => $txnid,
				'hash' => ''
			);
				
			$hash = hash("sha512", $fields['key'].'|'.$fields['command'].'|'.$fields['var1'].'|'.$payu_salt );
			$fields['hash'] = sanitize_text_field($hash);
			//$fields_string = http_build_query($fields);
			$url = esc_url('https://info.payu.in/merchant/postservice.php?form=2');
			if( $this -> gateway_module == 'sandbox' )
				$url = esc_url("https://test.payu.in/merchant/postservice.php?form=2");	
			
			$args = array(
				'body' => $fields,
				'timeout' => '5',
				'redirection' => '5',
				'httpversion' => '1.1',
				'blocking'    => true,
				'headers'     => array(),
				'cookies'     => array(),
			);
			
			$response = wp_remote_post( $url, $args );
			
			if(!isset($response['body']))			
				return false;			
			else {
				$res = json_decode(sanitize_text_field($response['body']),true);	
				if(!isset($res['status']))
					return false;
				else{
					$res = $res['transaction_details'];
					$res = $res[$txnid];						
					
					if(sanitize_text_field($res['status']) == 'success')	
						return true;					
					elseif(sanitize_text_field($res['status']) == 'pending' || sanitize_text_field($res['status']) == 'failure')
						return false;
				}
			}			
		}
		catch (Exception $e)
		{
			return false;	
		}
    }
    
    
    /*
     //Removed For WooCommerce 2.0
    function showMessage($content){
         return '<div class="box '.$this -> msg['class'].'-box">'.$this -> msg['message'].'</div>'.$content;
     }*/
    
    /**
     * Generate PayUBiz button link
     **/    
    public function generate_payubiz_form($order_id){
      
		global $woocommerce;
		$payu_key="";
		$payu_salt="";
		
		$order = new WC_Order($order_id);
		
		$order_currency = sanitize_text_field($order->get_currency());
		switch($order_currency)
		{
			case $this->currency1:
				$payu_key = $this->currency1_payu_key;
				$payu_salt = $this->currency1_payu_salt;
				break;
			case $this->currency2:
				$payu_key = $this->currency2_payu_key;
				$payu_salt = $this->currency2_payu_salt;
				break;
			case $this->currency3:
				$payu_key = $this->currency3_payu_key;
				$payu_salt = $this->currency3_payu_salt;
				break;
			case $this->currency4:
				$payu_key = $this->currency4_payu_key;
				$payu_salt = $this->currency4_payu_salt;
				break;
			case $this->currency5:
				$payu_key = $this->currency5_payu_key;
				$payu_salt = $this->currency5_payu_salt;
				break;
			case $this->currency6:
				$payu_key = $this->currency6_payu_key;
				$payu_salt = $this->currency6_payu_salt;
				break;
			case $this->currency7:
				$payu_key = $this->currency7_payu_key;
				$payu_salt = $this->currency7_payu_salt;
				break;
			case $this->currency8:
				$payu_key = $this->currency8_payu_key;
				$payu_salt = $this->currency8_payu_salt;
				break;
			case $this->currency9:
				$payu_key = $this->currency9_payu_key;
				$payu_salt = $this->currency9_payu_salt;
				break;
			case $this->currency10:
				$payu_key = $this->currency10_payu_key;
				$payu_salt = $this->currency10_payu_salt;
				break;
			default:
				break;
		}
		$redirect_url = ($this -> redirect_page_id=="" || $this -> redirect_page_id==0)?get_site_url() . "/":get_permalink($this -> redirect_page_id);
      
		//For wooCoomerce 2.0
		$redirect_url = add_query_arg( 'wc-api', get_class( $this ), $redirect_url );	
		WC()->session->set( 'orderid_awaiting_payubiz', $order_id );
		$order_id = $order_id.'_'.date("ymd").':'.rand(1,100);
      
		//do we have a phone number?
		//get currency      
		$address = sanitize_text_field($order -> billing_address_1);
		if ($order -> billing_address_2 != "")
			$address = $address.' '.sanitize_text_field($order -> billing_address_2);
		
		$productInfo='';
		foreach ($order->get_items() as $item ) {
			$product = wc_get_product($item->get_product_id());
			$productInfo .= $product->get_sku().':';
		}
		$productInfo=rtrim($productInfo,':');
		if('' == $productInfo)
			$productInfo = "Product Information";
		elseif(100 < strlen($productInfo))
			$productInfo=substr($productInfo,0,100);
			
		$action = esc_url('https://secure.payu.in/_payment');
			
		if('sandbox' == $this->gateway_module )
			$action = esc_url('https://test.payu.in/_payment');
			
		$amount = sanitize_text_field($order -> order_total);		
		$firstname = sanitize_text_field($order -> billing_first_name);
		$lastname = sanitize_text_field($order -> billing_last_name);
		$zipcode = sanitize_text_field($order -> billing_postcode);
		$email = sanitize_email($order -> billing_email);
		$phone = sanitize_text_field($order -> billing_phone);			
        $state = sanitize_text_field($order -> billing_state);
        $city = sanitize_text_field($order -> billing_city);
        $country = sanitize_text_field($order -> billing_country);
		$Pg = 'CC';
		$udf5 = 'WooCommerce_v_3.8.1';
			
		$hash=hash('sha512', $payu_key.'|'.$order_id.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'||||||'.$payu_salt); 
			
		$html = '<form action="'.$action .'" method="post" id="payu_form" name="payu_form">
				<input type="hidden" name="key" value="'. $payu_key. '" />
				<input type="hidden" name="txnid" value="'.$order_id.'" />
				<input type="hidden" name="amount" value="'.$amount.'" />
				<input type="hidden" name="productinfo" value="'.$productInfo.'" />
				<input type="hidden" name="firstname" value="'. $firstname.'" />
				<input type="hidden" name="Lastname" value="'. $lastname.'" />
				<input type="hidden" name="Zipcode" value="'. $zipcode. '" />
				<input type="hidden" name="email" value="'. $email.'" />
				<input type="hidden" name="phone" value="'.$phone.'" />
				<input type="hidden" name="surl" value="'. esc_url($redirect_url). '" />
				<input type="hidden" name="furl" value="'. esc_url($redirect_url).'" />
				<input type="hidden" name="curl" value="'.esc_url($redirect_url).'" />
				<input type="hidden" name="Hash" value="'.$hash.'" />
				<input type="hidden" name="Pg" value="'. $Pg.'" />
				<input type="hidden" name="address1" value="'.$address .'" />
		        <input type="hidden" name="address2" value="" />
			    <input type="hidden" name="city" value="'. $city.'" />
		        <input type="hidden" name="country" value="'.$country.'" />
		        <input type="hidden" name="state" value="'. $state.'" />
				<input type="hidden" name="udf5" value="'. $udf5.'" />
		        <button style="display:none" id="submit_payubiz_payment_form" name="submit_payubiz_payment_form">Pay Now</button>
				</form>
				<script type="text/javascript">document.getElementById("payu_form").submit();</script>';
		return $html;
    }
    
        
    function get_pages($title = false, $indent = true) {
      $wp_pages = get_pages('sort_column=menu_order');
      $page_list = array();
      if ($title) $page_list[] = $title;
      foreach ($wp_pages as $page) {
        $prefix = '';
        // show indented child pages?
        if ($indent) {
          $has_parent = $page->post_parent;
          while($has_parent) {
            $prefix .=  ' - ';
            $next_page = get_page($has_parent);
            $has_parent = $next_page->post_parent;
          }
        }
        // add to page list array array
        $page_list[$page->ID] = $prefix . $page->post_title;
      }
      return $page_list;
    }

  }
	 	
	

  /**
   * Add the Gateway to WooCommerce
   **/
  function woocommerce_add_payubiz_gateway($methods) {
    $methods[] = 'WC_Payubiz';
    return $methods;
  }

  add_filter('woocommerce_payment_gateways', 'woocommerce_add_payubiz_gateway' );
  
}

?>
