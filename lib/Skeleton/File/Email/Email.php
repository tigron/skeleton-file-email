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
