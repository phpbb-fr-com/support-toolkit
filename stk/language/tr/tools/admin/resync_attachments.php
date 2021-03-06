<?php
/**
 *
 * @package Support Toolkit - Resync Attachments
 * @copyright (c) 2009 phpBB Group
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'RESYNC_ATTACHMENTS'			=> 'Eklentileri yeniden senkronize et',
	'RESYNC_ATTACHMENTS_CONFIRM'	=> 'Bu araç veritabanında saklanan tüm eklentilerin, sunucu üzerinde bir dosya olarak gerçekten mevcut olup olmadığını kontrol edecek. Eğer dosya kayıpsa, bu araç eklentiyi veritabanından kaldıracaktır. Devam etmek istediğinize emin misiniz?',
	'RESYNC_ATTACHMENTS_FINISHED'	=> 'Eklentiler başarıyla yeniden senkronize edildi!',
	'RESYNC_ATTACHMENTS_PROGRESS'	=> 'Eklentilerin yeniden senkronizasyonu devam ediyor. Lütfen bu işlemi yarıda kesmeyin.',
));
