<?php
/**
 * @package     webpagetest
 *
 * @author      Pieter-Jan de Vries/Obix webtechniek <pieter@obix.nl>
 * @copyright   Copyright (C) 2022+ Obix webtechniek. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.obix.nl
 */
 file_put_contents(__DIR__ . '/wtppingbak.out.txt',
	 sprintf("%s, %s\n",(new DateTime())->format('Y-m-d H:i:s'), $_GET['id']), FILE_APPEND | LOCK_EX);