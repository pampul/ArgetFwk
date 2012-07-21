<?php
/**
 * Copyright (c) 2002-2010 Aurélien Maille
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 * 
 * @package Wamailer
 * @author  Bobe <wascripts@phpcodeur.net>
 * @link    http://phpcodeur.net/wascripts/wamailer/
 * @license http://www.gnu.org/copyleft/lesser.html
 * @version $Id: validateMailbox.php 28 2010-09-24 17:51:19Z bobe $
 */

/**
 * Vérifie si une adresse email N’EST PAS valide (domaine et compte).
 * Ceci est différent d’une vérification de validité.
 * Le serveur SMTP peut très bien répondre par un 250 ok pour une adresse
 * email non existante, les erreurs d’adressage étant traitées ultérieurement
 * au niveau du serveur POP.
 * 
 * Appels possibles à cette fonction :
 * 
 * $result = validateMailBox('username@domain.tld');
 * $result = validateMailBox('username@domain.tld', $results);
 * $result = validateMailBox(array('username1@domain.tld',
 *     'username2@domain.tld', 'username@otherdomain.tld'), $results);
 * 
 * @param mixed $emailList      Adresse email complète ou tableau d’adresses email
 * @param array $return_errors  Passage par référence. Retour d’erreur sous
 *                              forme de tableau :
 *                              array('address1@domain.tld' => 'msg error...',
 *                                    'address2@domain.tld' => 'msg error...')
 * 
 * @return boolean
 */
function validateMailbox($emailList, &$return_errors = null)
{
	if( !class_exists('Mailer_SMTP') ) {
		require dirname(__FILE__) . '/smtp.class.php';
	}
	
	if( !is_array($emailList) ) {
		$emailList = array($emailList);
	}
	else {
		$emailList = array_unique($emailList);
	}
	
	$domainList = $return_errors = array();
	
	foreach( $emailList as $email ) {
		if( strpos($email, '@') ) {
			list($mailbox, $domain) = explode('@', $email);
			
			if( !isset($domainList[$domain]) ) {
				$domainList[$domain] = array();
			}
			
			array_push($domainList[$domain], $mailbox);
		}
		else {
			$return_errors[$email] = 'Invalid syntax';
		}
	}
	
	foreach( $domainList as $domain => $mailboxList ) {
		$mxhosts = array();
		if( function_exists('getmxrr') ) {
			$result = getmxrr($domain, $hosts, $weight);
			
			for( $i = 0, $m = count($hosts); $i < $m; $i++ ) {
				array_push($mxhosts, array($weight[$i], $hosts[$i]));
			}
		}
		else {
			exec(sprintf('nslookup -type=mx %s', escapeshellcmd($domain)), $lines);
			
			$regexp = '/^' . preg_quote($domain) . '\s+(?:(?i)MX\s+)?'
				. '(preference\s*=\s*([0-9]+),\s*)?'
				. 'mail\s+exchanger\s*=\s*(?(1)|([0-9]+)\s+)([^ ]+?)\.?$/';
			
			foreach( $lines as $value ) {
				if( preg_match($regexp, $value, $match) ) {
					array_push($mxhosts, array(
						$match[3] === '' ? $match[2] : $match[3],
						$match[4]
					));
				}
			}
			
			$result = (count($mxhosts) > 0);
		}
		
		if( !$result ) {
			array_push($mxhosts, array(0, $domain));
		}
		
		array_multisort($mxhosts);
		
		$smtp = new Mailer_SMTP();
		
		foreach( $mxhosts as $record ) {
			try {
				$smtp->connect($record[1]);
				if( $smtp->from('wamailer@' . $domain) ) {
					foreach( $mailboxList as $mailbox ) {
						$email = $mailbox . '@' . $domain;
						
						if( !$smtp->to($email, true) ) {
							$return_errors[$email] = $smtp->responseData;
						}
					}
				}
				
				$smtp->quit();
				break;
			}
			
			//
			// Code temporaire à remplacer
			//
			catch( Exception $e ) {
				if( !$result ) {
					foreach( $mailboxList as $mailbox ) {
						$return_errors[$mailbox . '@' . $domain] = $e->getMessage();
					}
					break;
				}
			}
		}
	}
	
	return (count($return_errors) == 0);
}

?>
