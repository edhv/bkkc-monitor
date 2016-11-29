<?

class mailer_admin
{
	
	var $settings;
	
	function __construct()
	{

		add_action( 'add_meta_boxes', array($this, 'register_metabox_content'),1 );  
		//add_action( 'edhv/mailer', array($this, 'test'));
		add_action( 'admin_init', array($this, 'admin_init'),1 );
		add_action( 'admin_head', array($this, 'admin_head') );

		        add_action( 'save_post', array($this, 'save_post'),1 );


		// Mail hooks
		add_action('wp_ajax_prepare_mail',array($this,'mail_ajax_prepare_mail'));
		add_action('wp_ajax_get_organisations',array($this,'mail_ajax_get_organisations'));


	}


	function admin_init() {
		if ($this->get_post_type() == 'mailer') {

			wp_enqueue_style( 'jquery-ui', plugins_url( '/css/jquery-ui.css', dirname(__FILE__)) );
			wp_enqueue_style( 'cab-admin-css', plugins_url( '/css/cab-admin.css', dirname(__FILE__)) );
	    	wp_enqueue_script( 'cab-admin-main', plugins_url( '/js/admin-main.js', dirname(__FILE__)), array( 'jquery' ) );

	    	// Localize the script with new data
	    	$wordpress_vars_array = array(
	    		'wpurl' => get_bloginfo('wpurl')
	    	);
	    	wp_localize_script( 'cab-admin-main', 'wordpress_vars', $wordpress_vars_array );

	    }


	}



	function admin_head() {
		if (isset($_GET['post'])) {
			echo '<script type="text/javascript">var post_id = '.$_GET['post'].';</script>';
		}

	}

	function register_metabox_content()  
	{  
		global $post;
		
		add_meta_box( 'mailer-metabox_recipients', 'Recipients', array($this, 'metabox_recipients_data'), 'mailer', 'normal', 'low' );  
		add_meta_box( 'mailer-metabox_send-box', 'Send mail', array($this, 'metabox_send_box_data'), 'mailer', 'normal', 'low' );  
		add_meta_box( 'mailer-metabox_test-box', 'Test mail', array($this, 'metabox_test_box_data'), 'mailer', 'normal', 'low' );  

		add_meta_box( 'mailer-metabox_send-log', 'Send log', array($this, 'metabox_log_data'), 'mailer', 'normal', 'low' );  

	}  


	function save_post() {

	}


	function metabox_log_data() {
		global $post;


		/*
    	[0] => Array
        (
            [status] => success
            [organisation_id] => 677
            [organisation_name] => Edhv
            [details] => 
            [data] => 08/21/2013 12:21:40 pm
        )

		*/
		$mail_log = unserialize(get_post_meta( $post->ID, "mail_log", true));
		if (is_array($mail_log)) {
		$mail_log = array_reverse($mail_log);
		echo '<div class="send_log">';
	 	echo '<ul >';
	 	echo '<li class="clearfix"><span class="icon"></span><span class="date"><strong>Date</strong></span><span class="name"><strong>Organisation</strong></span><span class="status"><strong>Status</strong></span></li>';

	 	foreach ($mail_log as $key => $mail) {

	 		if (isset($mail['details'])) {
	 			$mail_details = $mail['details'];
	 		} else {
	 			$mail_details = '';
	 		}

	 		echo '<li class="clearfix '.$mail['status'].'"><span class="icon"></span><span class="date">'.$mail['date'].'</span><span class="name">'.$mail['organisation_name'].'</span><span class="status">'.$mail_details.'</span></li>';
	 	}

	 	echo '</ul>';
	 	echo '</div>';
	 	}
	}

	function metabox_send_box_data() {

		
		wp_nonce_field( 'metabox_send_box_nonce', 'meta_box_nonce' ); 
	 	echo '<div class="metabox_section">';
	 	echo '<ul >';
	 	echo '<li class="clearfix"><span><span><input name="send_mail" type="button" class="button button-primary button-large" id="send_mail" value="Send mail"></span></span><span class="loader"></span></li>';
	 	echo '</ul>';
	 	echo '</div>';
	 	echo '<div class="metabox_section send_status">';
	 	echo '<h4>Send mail:</h4>';
	 	echo '<ul class="send_mail_log">';
	 	echo '<li><span class="status send"></span><span class="name">Edhv</span></li>';
	 	echo '<li><span class="status failed"></span><span class="name">Kunstenaarsinitiatief Willem II Ateliers s-Hertogenbosch</span></li>';
	 	echo '</ul>';
	 	echo '</div>';


	}


		function metabox_test_box_data() {

		
		wp_nonce_field( 'metabox_test_box_nonce', 'meta_box_nonce' ); 
	 	echo '<div class="metabox_section">';
	 	echo '<ul >';
	 	echo '<li class="clearfix"><p>Test email adres:</p><input type="text" id="test_mail_recipient" name="test_mail_recipient"/><br/></li><li class="clearfix"><input name="test_mail" type="button" class="button button-large" id="test_mail" value="Test mail"><span class="loader"></span></li>';
	 	echo '</ul>';
	 	echo '</div>';
	 	echo '<div class="metabox_section test_send_status">';
	 	echo '<h4>Send mail:</h4>';
	 	echo '<ul class="send_mail_log">';
	 	echo '<li><span class="status send"></span><span class="name">Edhv</span></li>';
	 	echo '<li><span class="status failed"></span><span class="name">Kunstenaarsinitiatief Willem II Ateliers s-Hertogenbosch</span></li>';
	 	echo '</ul>';
	 	echo '</div>';

	}


	function metabox_recipients_data() {
		wp_nonce_field( 'metabox_recipients_nonce', 'meta_box_nonce' ); 
	 	echo '<div class="metabox_section clearfix">';
	 	echo '<ul>';
	 	echo '<li><h4>Select organisations:</h4>';
	 	echo '<li class="clearfix"><span><select id="select_organisations" name="select_organisations">
  <option value="none">-</option>
  <option value="test">Test organisations</option>
  <option value="all_organisations">All organisations</option>
  <!--<option value="bloementuin_organisations">Bloementuin organisations</option>-->
  <option value="vragenlijst_organisations">Vragenlijst organisations</option>
  <option value="bloementuin_organisations">Bloementuin organisations</option>
  <option value="vragenlijst_organisations_open_forms">- Openstaande vragenlijsten</option>
</select></span><span class="float_right"><input name="get_organisations" type="button" class="button button-primary button-large" id="get_organisations" value="Get organisations"></span></li>';
	 	echo '</ul>';
	 	echo '</div>';
	 	echo '<div class="metabox_section clearfix">';
	 	echo '<h4>Recipients:</h4>';
	 	echo '<ul class="recipients_list">';
	 	echo '</ul>';
	 	echo '</div>';
	}



	// Checks if the admin is in a section of this plugin
	function admin_plugin_edit_page() {
		global $post;
		global $pagenow;

		if (get_post_type( $post ) == 'mailer' && $pagenow == 'post.php') {
			return true;
		} else {
			return false;
		}
	}








	// Mailer functions


	function mail_ajax_get_organisations() {
				global $cab_functions;

		switch ($_GET['query_category']) {
			case 'all_organisations':

				$organisations = $cab_functions->get_all_organisations_list('post_title', 'ASC');
				//echo "a";
				echo json_encode($organisations);
			break;

			case 'vragenlijst_organisations':
				$organisations = $cab_functions->get_all_vragenlijst_organisations_list();
				echo json_encode($organisations);
			break;

			case 'bloementuin_organisations':
				$organisations = $cab_functions->get_all_bloementuin_organisations_list();
				echo json_encode($organisations);
			break;

			case 'vragenlijst_organisations_open_forms':
				$organisations = $cab_functions->get_all_vragenlijst_organisations_open_forms_list();
				echo json_encode($organisations);

			break;

			case 'test':
				$organisations = array(array("id"=>786,"name"=>"test podiumkunsten"),array("id"=>785,"name"=>"test filmproducenten"));
				echo json_encode($organisations);
			break;
	
		}
		die();


		// return 4;
	}



	function get_mail_tag($tag, $organisation) {
		global $cab_functions;

		$owner_id = $cab_functions->get_user_id_by_organisation_id($organisation->ID);
		$user_meta = get_user_meta($owner_id);
		$user_data = get_userdata($owner_id);
		$post_data = get_post_custom($organisation->ID);

		switch ($tag) {

			case 'organisation_id':
				return $organisation->ID;
			break;
			case 'administration_name':
			
				if (isset($user_meta['user-cab_organisatie-administratief-naam'][0])) {
					return $user_meta['user-cab_organisatie-administratief-naam'][0];
				} else {
					return false;
				}

			break;

			case 'director_name':
			
				if (isset($user_meta['user-cab_organisatie-directeur-naam'][0])) {
					return $user_meta['user-cab_organisatie-directeur-naam'][0];
				} else {
					return false;
				}

			break;

			case 'organisation_name':

				if (isset($user_meta['user-cab_organisatie-naam'][0])) {
					return $user_meta['user-cab_organisatie-naam'][0];
				} else {
					return false;
				}
			
			break;

			case 'user_login':

				if (isset($user_data->user_login )) {
					return $user_data->user_login;
				} else {
					return false;
				}

			break;
				
			case 'user_password':

				if (isset($user_meta['user-cab_organisatie-password'][0])) {
					return $user_meta['user-cab_organisatie-password'][0];
				} else {
					return "error";
				}

			break;

			case 'user_notities':
					
				if (isset($post_data['vragenlijst_notities'][0])) {
					return $post_data['vragenlijst_notities'][0];
				} else {
					return false;
				}

			break;
			default:
				return "abort";
			break;
		 }
	
	}


	// Prepares the mail based on the ajax request. It fires the function which sents the mail
	function mail_ajax_prepare_mail() {

		global $cab_functions;

		$mail_id = $_POST['mail_id'];

		if (isset($_POST['test_mail'])) {
			$test_mail_recipient = $_POST['test_mail'];
			$test_mail = true;
		} else {
			$test_mail = false;
		}

		// Get organisation data
		$organisation = get_post($_POST['organisations']);
		if (isset($organisation)) {

			$owner_id = $cab_functions->get_user_id_by_organisation_id($organisation->ID);
			$user_meta = get_user_meta($owner_id);
			$user_data = get_userdata($owner_id);
			$post_data = get_post_custom($organisation->ID);

			if (!$test_mail) {
				$email_adresses = array($user_meta['user-cab_organisatie-administratief-email'][0],$user_meta['user-cab_organisatie-email'][0],$user_meta['user-cab_organisatie-directeur-email'][0], $user_data->user_email);
				$email_adresses = array_filter($email_adresses); // remove empty
				$email_adresses = array_unique($email_adresses); // remove duplicates
			} else {
				$email_adresses = explode(",", $test_mail_recipient);
			}


			// Find all tags in the message and subject
			preg_match_all('/{(\w*)}/',$_POST['body'], $found_body_tags);
			preg_match_all('/{(\w*)}/',$_POST['subject'], $found_subject_tags);
			$found_tags = array_merge($found_body_tags, $found_subject_tags);

			$replace_tags = array();

			foreach ($found_tags[1] as $tag) {
				$value = $this->get_mail_tag($tag,$organisation);

				if ($value == "error") {
					$value = "";
					// Do something with this error
				}

				$replace_tags[$tag] = $value;
			}
			//print_r($matches);

			// Declare 
			// $replace_tags = array(
			// 			"organisation_id"=>$organisation->ID,
			// 			"owner_id"=>$owner_id,
			// 			"organisation_name"=>$organisation->post_title,
			// 			"user_name" => $user_meta['nickname'][0],
			// 			"user_password" => $user_meta['user-cab_organisatie-password'][0],
			// 			"user_notities" => $post_data['vragenlijst_notities'][0],
			// 			"email_adresses"=>$email_adresses
			// 		);

			$prepared_body = $this->mail_prepare_content($_POST['body'],$replace_tags);

			$mail_sent = $this->send_single_mail($email_adresses, $_POST['subject'],$prepared_body ,$_POST['from'],$_POST['bcc'],$test_mail);
			$mail_sent['organisation']['name'] = $organisation->post_title;
		    $mail_sent['organisation']['id'] = $organisation->ID;
		    $mail_sent['details'] = implode(", ", $email_adresses);

			// Log sent email
			if (!$test_mail) {
				$this->add_to_log($mail_id, $mail_sent);
			}

			echo json_encode($mail_sent);
			die();
		}
		


	}


	// Adds the sent mail to a log
	function add_to_log($mail_id, $mail_status) {

		// Get current log
		$mail_log = unserialize(get_post_meta( $mail_id, "mail_log", true));

		// Check if the log exists
		if ($mail_log == "") {
			$mail_log = array();
		} 

		$log_entry = array(
			"status"=>$mail_status['status'],
			"organisation_id"=>$mail_status['organisation']['id'],
			"organisation_name"=>$mail_status['organisation']['name'],
			"date"=>date('Y-m-d H:i:s')
		);

		if (isset($mail_status['details'])) {
			$log_entry['details'] = $mail_status['details'];
		}

		array_push($mail_log,$log_entry);


		// Update meta data
		update_post_meta($mail_id, 'mail_log', serialize($mail_log));

	}


	// Check to see if there are any regex items
	function mail_prepare_content($content, $replace_tags = false) {

		$mail_message = $content;

		if (isset($replace_tags)) {

			foreach ($replace_tags as $replace_tag => $replace_value) {

				if ($replace_tag == 'user-notities') {
					if ($replace_value != '') {
						$mail_message = str_replace('{user-notities}', '[NOTITIE - '.$mail_session['user-notities'].' ]', $mail_message);
					} else {
						$mail_message = str_replace('{user-notities}', '', $mail_message);
					}
				} else {
					$mail_message = str_replace('{'.$replace_tag.'}', $replace_value, $mail_message);
				}
			}

		}

		return $mail_message;
	}



		function get_mail_header() {
		$html = '<html lang="en">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>
</title>

<!--[if gte mso 9]>
<style type="text/css">
.body{background: #ffffff;}
.case { background:none;}
.content p, .content td {
font-size:12px;  
line-height:16px;
margin-bottom:10px;
}
</style>
<![endif]-->  
</head>  

<body style="color: #000000;" class="body">  
     
<table cellpadding="0" cellspacing="0" border="0" style="font-family: Verdana; line-height: 1px;">
      

    <!-- Intro -->



    <!-- Spacer -->    
    <tr>
        <td>  
            <!-- Begin Spacer -->
            <table cellpadding="0" cellspacing="0" border="0" width="650" style="font-family: Verdana; line-height: 1px;">   
            <tr><td height="0"></td></tr>                                 
            </table> 
            <!-- End Spacer -->
        </td>
    </tr>

    <!-- Content -->

     <tr>
        <td>  
            <!-- Begin Spacer -->
            <table cellpadding="0" cellspacing="0" border="0" width="650" class="content" style="font-family: Verdana; line-height: 1px;">   
            <tr>
                <td width="500" style="font-family: Verdana; font-size: 13px; line-height: 16px; margin-bottom: 25px;">';

                return $html;
	}


	function get_mail_footer() {
		$html = '               </td>
                <td style="font-size: 13px; line-height: 16px; margin-bottom: 25px;">&nbsp;</td>
            </tr>                                 
            </table> 
            <!-- End Spacer -->
        </td>
    </tr>
    
    </table>


<style type="text/css">
body { color: #000000 !important; }
img { display: block !important; }
</style>
</body>
</html>';
return $html;
	}

	function send_single_mail($recipients, $subject, $body, $from, $bcc, $test = false) {
		$mail = new PHPMailer();
		$mail->From = $from;
		$mail->FromName = "bkkc";

		foreach ($recipients as $key => $email_adres) {
		 	$mail->AddAddress($email_adres);
		 }

		if (isset($bcc) && !$test) {
			$mail->AddBCC($bcc); // Eerste BCC
		}
		//$mail->AddBCC("s.swinkels@bkkc.nl"); // Eerste BCC
		//$mail->AddAddress("mail@muhneer.nl");


		$mail->Subject  = $subject;
		if ($test) {
			$mail->Subject = "Test - ".$subject;
		}
		$mail_body_header = $this->get_mail_header();
		$mail_body_footer = $this->get_mail_footer();

		$mail->Body     = stripslashes($mail_body_header.$body.$mail_body_footer);
		$mail->IsHTML(true);



		if(!$mail->Send()) {
			return array("status"=>"failed","details"=>$mail->ErrorInfo);
		 } else {
		 	return array("status"=>"success");
		}

	}












function test() {
	echo "Aaa";
}
// 	function metabox_content_data()
// 	{
//  		wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' ); 
//  		$content = '';
// $editor_id = 'mycustomeditor';

// wp_editor( $content, $editor_id );
// 	}

// }






	// Gets the post type even if the type isn't loaded yet
	function get_post_type() {
		global $pagenow;

		if ( 'edit.php' == $pagenow) 
    	{
        	if ( !isset($_GET['post_type']) )
        	{	
            	//$firephp->log('I am the Posts listings page');  
        	}
        	else
        	{
        		return $_GET['post_type'];
        	}
    	}

	    if ('post.php' == $pagenow && isset($_GET['post']) )
	    {
	        // Will occur only in this kind of screen: /wp-admin/post.php?post=285&action=edit
	        // and it can be a Post, a Page or a CPT
	        $post_type = get_post_type($_GET['post']);
			return $post_type;
	    }

	    if ('post-new.php' == $pagenow )
	    {
	        // Will occur only in this kind of screen: /wp-admin/post-new.php
	        // or: /wp-admin/post-new.php?post_type=page
	        if ( !isset($_GET['post_type']) ) 
	        {
	            //$firephp->log('I am creating a new post');  
	        }
	        else {
	        	return $_GET['post_type'];
	        }
	    }   
	}













}
$mailer_admin = new mailer_admin();