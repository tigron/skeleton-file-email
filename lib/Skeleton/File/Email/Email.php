<?php
/**
 * Email class
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\File\Email;

use Skeleton\File\File;

class Email extends File {

	/**
	 * The parsed message
	 *
	 * @access private
	 * @private $message
	 */
	private $message = null;

	/**
	 * Extract attachments
	 *
	 * @access public
	 * @return array $files
	 */
	public function extract_attachments() {
		$message = $this->read_message();
		$attachment_parts = $message->getAllAttachmentParts();
		$attachments = [];

		$index = 0;
		foreach ($attachment_parts as $attachment_part) {
			if (empty($attachment_part->getFilename())) {
				$filename = 'attachment_' . $index;
			} else {
				$filename = $attachment_part->getFilename();
			}


			$attachment = \Skeleton\File\File::store($filename, $attachment_part->getContent());
			$attachments[] = $attachment;

			$index++;
		}

		return $attachments;
	}

	/**
	 * Get the html content of the email
	 *
	 * @access public
	 * @return string $html
	 */
	public function get_content() {
		$message = $this->read_message();

		$content = $message->getHtmlContent();

		if (trim($content) == '') {
			$content = $message->getTextContent();
		}

		return $content;
	}

	/**
	 * Get subject
	 *
	 * @access public
	 * @return string $subject
	 */
	public function get_subject() {
		$message = $this->read_message();
		return $message->getHeaderValue('subject');
	}

	/**
	 * Get date
	 *
	 * @access public
	 * @return string $subject
	 */
	public function get_date() {
		$message = $this->read_message();
		return date('Y-m-d H:i:s', strtotime($message->getHeaderValue('date')));
	}

	/**
	 * Get from
	 *
	 * @access public
	 * @return string $from
	 */
	public function get_from() {
		$message = $this->read_message();

		if ($message->getHeader('from') === null) {
			return ['name' => '', 'email' => ''];
		}

		$from = [
			'name' => $message->getHeader('from')->getPersonName(),
			'email' => $message->getHeaderValue('from'),
		];

		return $from;
	}

	/**
	 * Get to
	 *
	 * @access public
	 * @return string $to
	 */
	public function get_to() {
		$message = $this->read_message();

		if ($message->getHeader('to') === null) {
			return ['name' => '', 'email' => ''];
		}

		$to = [
			'name' => $message->getHeader('to')->getPersonName(),
			'email' => $message->getHeaderValue('to'),
		];

		return $to;
	}

	/**
	 * Parse the message
	 *
	 * @access private
	 * @return \ZBateson\MailMimeParser\Message $message
	 */
	private function read_message() {
		if ($this->message === null) {
			$mailParser = new \ZBateson\MailMimeParser\MailMimeParser();
			$this->message = $mailParser->parse($this->get_contents());
		}
		return $this->message;
	}

	/**
	 * Get an email by ID
	 *
	 * @access public
	 * @param int $id
	 * @return Email $email
	 */
	public static function get_by_id($id) {
		return new Email($id);
	}
}
