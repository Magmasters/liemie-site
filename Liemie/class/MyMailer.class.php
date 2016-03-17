<?php

set_include_path('../../../pear/php' . PATH_SEPARATOR . get_include_path());

require_once "Mail.php";

Class MyMailer {
	
	private $smtp;
	
	private $body;
	private $to;
	private $headers;
	
	private $erreur; //contient l'erreur si détectée
	
	public function __construct($From, $To, $Subject, $Body, $User, $Pwd, $Host, $Port = 465) {
		$this->headers = array(
							'From' => $From,
							'To' => $To,
							'Subject' => $Subject,
							'Content-Type' => 'text/html'
		);

		$this->smtp = Mail::factory('smtp', array(
						'host' => $Host,
						'port' => $Port,
						'auth' => true,
						'username' => $User,
						'password' => $Pwd
		));
						
		$this->body = $Body;
		$this->to = $To;
	}
	
	public function envoyerMail() {
		$mail = $this->smtp->send($this->to, $this->headers, $this->body);
		
		if (PEAR::isError($mail)) {
			$this->erreur = $mail->getMessage();
			return false;
		}
		
		return true;
	}
	
	public function getErreur() {
		return $this->erreur;
	}
}
