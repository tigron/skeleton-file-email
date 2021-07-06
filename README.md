# skeleton-file-email

## Description

This library adds RAW email functionality for Skeleton\File\File objects

## Installation

Installation via composer:

    composer require tigron/skeleton-file-email

## Howto

Get an email
If the file is an email, a \Skeleton\File\Email\Email object is returned

	$email = \Skeleton\File\File::get_by_id(1);

Persons involved in the email will be returned as \Skeleton\File\Email\Contact
objects.
A contact has the following structure:

    Skeleton\File\Email\Contact Object
    (
        [name] => Recipient1
        [email] => email@example.com
    )

To retrieve the contacts, the following methods can be used

Get the From contact

    $email->get_from();

Get the To contact(s)

    $email->get_to(); // returns an array of Contact objects

Get the CC contact(s)

    $email->get_cc(); // returns an array of Contact objects

Other information can be obtained via the following methods:

Get the subject

    $email->get_subject();

Get the content

    $email->get_content();

Get the date

    $email->get_date();

Has attachments

	$email->has_attachments(); // true/false

Count attachments

	$email->count_attachments();

Extract all attachments from the Email

	$attachments = $file->extract_attachments(); // Each attachment is a \Skeleton\File\File object
