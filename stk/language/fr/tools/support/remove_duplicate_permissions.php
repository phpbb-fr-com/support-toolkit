<?php
/**
*
* This file is part of French (Formal Honorifics) STK translation.
* Copyright (C) 2010 phpBB.fr
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; version 2 of the License.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License along
* with this program; if not, write to the Free Software Foundation, Inc.,
* 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*
* @package   Support Toolkit - Duplicate Permission Remover
* @author    Maël Soucaze <maelsoucaze@phpbb.fr> (Maël Soucaze) http://www.phpbb.fr/
* @copyright (c) 2009 phpBB Group
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
* @version   $Id: remove_duplicate_permissions.php 415 2010-06-09 00:44:26Z iwisdom $
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
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
	'DUPLICATES_FOUND'						=> 'L’outil a trouvé et a supprimé toutes les permissions dupliquées.',

	'NO_DUPLICATES_FOUND'					=> 'L’outil a terminé la vérification des permissions dupliquées et n’en a trouvé aucune.',

	'REMOVE_DUPLICATE_PERMISSIONS'			=> 'Supprimer les permissions dupliquées',
	'REMOVE_DUPLICATE_PERMISSIONS_CONFIRM'	=> 'Êtes-vous sûr de vouloir supprimer les permissions dupliquées ?',
));

?>