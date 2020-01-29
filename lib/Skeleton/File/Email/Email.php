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
		foreach ($attachment_parts as $attachment_part) {
			$attachment = \Skeleton\File\File::store($attachment_part->getFilename(), $attachment_part->getContent());
			$attachments[] = $attachment;
		}
		return $attachments;
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
			$this->message = $mailParser->parse($incoming->file->get_contents());
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
