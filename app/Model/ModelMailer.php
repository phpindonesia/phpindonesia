<?php

/*
 * This file is part of the PHP Indonesia package.
 *
 * (c) PHP Indonesia 2013
 */

namespace app\Model;

use app\Parameter;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_Attachment;
use \Swift_SendmailTransport;

/**
 * ModelMailer
 *
 * @author PHP Indonesia Dev
 */
class ModelMailer extends ModelBase 
{
	protected $subject;
	protected $fromName;
	protected $fromEmail;
	protected $toName;
	protected $toEmail;
	protected $messageBody;
	protected $messageType;
	protected $attachmentFile;

	/**
	 * Constructor
	 */
	public function __construct(Parameter $parameter) {
		// Initialize all parameter
		$this->subject = $parameter->get('subject', 'undefined');
		$this->fromName = $parameter->get('fromName', 'PHP Indonesia');
		$this->fromEmail = $parameter->get('fromEmail', 'no-reply@phpindonesia.net');
		$this->toName = $parameter->get('toName', 'undefined');
		$this->toEmail = $parameter->get('toEmail', 'undefined');
		$this->messageBody = $parameter->get('messageBody', 'undefined');
		$this->messageType = $parameter->get('messageType', 'text/plain');
		$this->attachmentFile = $parameter->get('attachmentFile', '');
	}

	/**
	 * Send register confirmation
	 *
	 * @param string $link URL untuk konfirmasi
	 */
	public function sendRegisterConfirmation($link = '') {
		// Sily
		if (empty($link)) return false;

		// Kumpulkan data
		$data = array(
			'title' => 'Konfirmasi Pendaftaran',
			'content' => 'Terima kasih telah mendaftar di portal PHP Indonesia. Untuk menyelesaikan proses pendaftaran, silahkan kunjungi link di bawah ini.',
			'link' => $link,
			'linkText' => 'Konfirmasi Sekarang',
		);

		// Message parameter
		$this->subject = 'Konfirmasi pendaftaran';
		$this->messageType = 'text/html';
		$this->messageBody = ModelBase::factory('Template')->render('email.tpl', $data);

		return $this->send();
	}

	/**
	 * Send register confirmation
	 *
	 * @param string $link URL untuk konfirmasi
	 */
	public function sendResetPassword($link = '') {
		// Sily
		if (empty($link)) return false;

		// Kumpulkan data
		$data = array(
			'title' => 'Reset Password',
			'content' => 'Baru-baru ini terdapat request untuk mereset password anda. Jika anda tidak merasa melakukan permintaan ini, acuhkan pesan ini. Jika anda memang ingin mengubah password anda, silahkan kunjungi link di bawah ini.',
			'link' => $link,
			'linkText' => 'Reset Password',
		);

		// Message parameter
		$this->subject = 'Reset Password';
		$this->messageType = 'text/html';
		$this->messageBody = ModelBase::factory('Template')->render('email.tpl', $data);

		return $this->send();
	}

    /**
     * Send the message
     *
     * @param bool $log Whether to log process or not 
     *
     * @return bool 
     */
    public function send($log = false) {
    	// Initialize the transport and mailer instances
		$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
		$mailer = Swift_Mailer::newInstance($transport);
		
		//Create a message
		$message = Swift_Message::newInstance($this->subject.'[PHP Indonesia]')
			  ->setFrom($this->fromEmail, $this->fromName)
			  ->setTo($this->toEmail, $this->toName)
			  ->setBody($this->messageBody, $this->messageType);

		// Check for attachment
		// @codeCoverageIgnoreStart
		if ( ! empty($this->attachmentFile) && file_exists($this->attachmentFile)) {
			$message->attach(Swift_Attachment::fromPath($this->attachmentFile)); 
		}
		  
		//Send the message
		try {
			$result = $mailer->send($message); 
		} catch (\Exception $e) {
			$result = false;
		}
		// @codeCoverageIgnoreEnd

		return $result;
	}
}