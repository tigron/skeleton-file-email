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
	 * Has attachments
	 *
	 * @access public
	 * @return boolean $has_attachments
	 */
	public function has_attachments() {
		$message = $this->read_message();
		$attachment_parts = $message->getAllAttachmentParts();
		if (count($attachment_parts) > 0) {
			return true;
		}

		return false;
	}

	/**
	 * Count attachments
	 *
	 * @access public
	 * @return boolean $has_attachments
	 */
	public function count_attachments() {
		$message = $this->read_message();
		$attachment_parts = $message->getAllAttachmentParts();
		return count($attachment_parts);
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
	 * @return Contact $contact
	 */
	public function get_from() {
		$message = $this->read_message();

		$from = $message->getHeader('from');
		if ($from === null) {
			return new Contact();
		}

		$contact = new Contact();
		$contact->name = $from->getPersonName();
		$contact->email = $from->getEmail();
		return $contact;
	}

	/**
	 * Get to
	 *
	 * @access public
	 * @return Contact[] $contacts
	 */
	public function get_to() {
		$message = $this->read_message();

		$to = $message->getHeader('To');
		if ($to === null) {
			return [];
		}
		$addresses = $to->getAddresses();
		$contacts = [];
		foreach ($addresses as $address) {
			$contact = new Contact();
			$contact->email = $address->getEmail();
			$contact->name = $address->getName();
			$contacts[] = $contact;
		}
		return $contacts;
	}

	/**
	 * Get cc
	 *
	 * @access public
	 * @return Contact[] $contacts
	 */
	public function get_cc() {
		$message = $this->read_message();

		$to = $message->getHeader('Cc');
		if ($to === null) {
			return [];
		}
		$addresses = $to->getAddresses();
		$contacts = [];
		foreach ($addresses as $address) {
			$contact = new Contact();
			$contact->email = $address->getEmail();
			$contact->name = $address->getName();
			$contacts[] = $contact;
		}
		return $contacts;
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
