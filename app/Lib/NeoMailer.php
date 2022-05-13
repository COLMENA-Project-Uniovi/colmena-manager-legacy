<?php

/*
NEO MAILER
DEVELOPED BY CARLOS F. MEDINA, PABLO ABELLA & JULIA VALLINA
V.1.2
LAST REVISION : 2015-01-28
*/

require_once('mandrill-api-php/src/Mandrill.php');

class NeoMailer{

	private $mail_host;
	private $mail_port;
	private $mail_encryption;
	private $mail_nickname;
	private $mail_username;
	private $mail_key;

	function __construct(){
		$this->mail_host = "smtp.mandrillapp.com";
		$this->mail_port = 587;
		$this->mail_encryption = "";
		$this->mail_nickname = "El Club de mi Caja";
		$this->mail_username = "mrbox@cajaruraldeasturias.com";
		// NORMAL API KEY
		$this->mail_key = "FwkwbYrDdEF7WLKS2JGHHw";
		// TEST API KEY
		//$this->mail_key = "QDLdVy1IRI2qUJ6K8cNoOQ";
	}

	function get_user_info(){
		$mandrill = new Mandrill($this->mail_key);
		$result = $mandrill->users->info();
		return $result;
	}

	function send_email($to, $subject, $template_name, $template_content, $tags){

		try {
			$mandrill = new Mandrill($this->mail_key);

			/*$template_content = array(
				array(
					'name' => 'main',
					'content' =>$body
				)
			);*/

			$template = $this->get_template($template_name);

			$from_email = $template['from_email'];
			$from_name = $template['from_name'];
			if($template['from_email'] == "" || $template['from_name'] == ""){
				$from_email = $this->mail_username;
				$from_name = $this->mail_nickname;
			}

			$message = array(
				'html' => $template_content,
				'subject' => $subject,
				'from_email' => $from_email,
				'from_name' => $from_name,
				'to' => $to,
				'important' => false,
				'track_opens' => null,
				'track_clicks' => null,
				'auto_text' => true,
				//'auto_html' => null,
				'inline_css' => true,
				//'url_strip_qs' => null,
				//'preserve_recipients' => null,
				//'view_content_link' => null,
				//'bcc_address' => 'message.bcc_address@example.com',
				//'tracking_domain' => null,
				//'signing_domain' => null,
				//'return_path_domain' => null,
				'tags' => $tags,
				'subaccount' => 'newsletter'
				//'google_analytics_domains' => array('example.com'),
				//'google_analytics_campaign' => 'message.from_email@example.com',
				//'metadata' => array('website' => 'www.example.com'),
			);
			$async = false;
			$ip_pool = 'Main Pool';
			//$send_at = date('2014-04-02 14:01:00', time());
			$result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async, $ip_pool);

			/*
			Array
			(
				[0] => Array
					(
						[email] => recipient.email@example.com
						[status] => sent
						[reject_reason] => hard-bounce
						[_id] => abc123abc123abc123abc123abc123
					)

			)
			*/
			//return $result[0]['status'] == 'sent';
			return $result[0]['status'] == 'sent' || $result[0]['status'] == 'queued';

		} catch(Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}

	function get_templates(){
		try{
			$mandrill = new Mandrill($this->mail_key);
			$result = $mandrill->templates->getList();
			return $result;
		} catch(Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}

	function get_template($name){
		try{
			$mandrill = new Mandrill($this->mail_key);
			$result = $mandrill->templates->info($name);
			return $result;
		} catch(Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}

	function render_template($name, $template_content){
		$mandrill = new Mandrill($this->mail_key);

		/*$merge_vars = array(
			array(
				'name' => 'merge1',
				'content' => 'ESTO VA DENTRO DE LA VARIABLE ESA'
			)
		);*/
		$merge_vars = array();
		$result = $mandrill->templates->render($name, $template_content, $merge_vars);
		return $result;
	}
}
?>
