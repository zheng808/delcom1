<?php
class wfCRMMailer {
	
    public static function send($from, $to, $subject, $mail_body) {
	  	try {
			  $mailer = new Swift(new Swift_Connection_NativeMail());
			  $message = new Swift_Message($subject, $mail_body, 'text/html');
			  $mailer->send($message, $to, $from);
			  $mailer->disconnect();
			  return true;
			} catch (Exception $e) {
			  $mailer->disconnect();
			  if ($e->getMessage()) {
			  	die('error');
			  	return false;
			  }
			}
    }
}