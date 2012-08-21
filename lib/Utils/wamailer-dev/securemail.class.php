<?php
/**
 * Copyright (c) 2006-10 Aurélien Maille
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
 * @version $Id: securemail.class.php 28 2010-09-24 17:51:19Z bobe $
 * 
 * @see RFC 1847 - Security Multiparts for MIME: Multipart/Signed and Multipart/Encrypted
 * @see RFC 3156 - MIME Security with OpenPGP
 * 
 * Les sources qui m’ont bien aidées :
 * 
 * @link http://www.gnupg.org/(en)/documentation/index.html
 * @link http://lea-linux.org/cached/index/Reseau-secu-gpg-intro.html#
 * @link http://www.kfwebs.net/articles/article/15/PHP-Sendmail-classes
 */

class SecureMail extends Email {
	
	const A_ENCRYPTION = 1;// chiffrement asymétrique
	const S_ENCRYPTION = 2;// chiffrement symétrique
	
	/**
	 * Si non défini, GnuPG utilisera la variable d’environnement $GNUPGHOME
	 * ou le répertoire ~/.gnupg
	 * 
	 * @var string
	 * @access public
	 */
	public $homedir     = null;
	
	public $tmpdir      = '/tmp';
	public $logfile     = '/dev/null';
	public $gpg_bin     = '/usr/bin/gpg';
	public $digest_algo = 'sha1';
	public $cipher_algo = null;// si non spécifié, GPG utilisera un algorithme par défaut
	
	private $_sign      = false;
	private $_encrypt   = false;
	private $secretkey  = null;
	private $passphrase = null;
	private $recipients = array();
	private $encryption = null;
	private $password   = null;
	
	/**
	 * Ajouter une signature numérique à cet email.
	 * Si le passphrase n’est pas fourni, le script supposera que la clé
	 * utilisée n’en nécessite aucun.
	 * Si $secretkey n’est pas spécifié, GnuPG utilisera la clé par défaut
	 * spécifiée dans le fichier gpg.conf.
	 * 
	 * @param string $secretkey   Identifiant de la clé ou le nom ou adresse
	 *                            email correspondant à la clé voulue
	 * @param string $passphrase  passphrase pour dévérouiller la clé
	 * 
	 * @access public
	 * @return void
	 */
	public function sign($secretkey = null, $passphrase = null)
	{
		$this->_sign = true;
		$this->secretkey  = $secretkey;
		$this->passphrase = $passphrase;
	}
	
	/**
	 * Chiffrement asymétrique ou symétrique de l’email.
	 * Si le type de chiffrement ($encryption) n’est pas spécifié,
	 * le script utilisera le chiffrement asymétrique.
	 * 
	 * @param mixed   $data        Dépend du type de chiffrement choisi.
	 *   - Si le chiffrement asymétrique est utilisé, correspondant à
	 *     l’identifiant du destinataire.
	 *   - Si le chiffrement symétrique est utilisé, correspondant au
	 *     passphrase permettant le déchiffrement du message.
	 * @param integer $encryption  Type de chiffrement. Peut valoir
	 *                             self::A_ENCRYPTION ou self::S_ENCRYPTION
	 * 
	 * @access public
	 * @return void
	 */
	public function encrypt($data, $encryption = null)
	{
		if( !in_array($encryption, array(self::A_ENCRYPTION, self::S_ENCRYPTION)) ) {
			$encryption = self::A_ENCRYPTION;
		}
		else {
			$this->encryption = $encryption;
		}
		
		if( $encryption == self::A_ENCRYPTION ) {
			if( !is_array($data) ) {
				$data = array($data);
			}
			
			$this->_encrypt   = true;
			$this->recipients = $data;
		}
		else {
			$this->_encrypt = true;
			$this->password = $data;
		}
	}
	
	/**
	 * @access public
	 * @return void
	 */
	public function initialize()
	{
		$this->_sign      = false;
		$this->_encrypt   = false;
		$this->secretkey  = null;
		$this->passphrase = null;
		$this->recipients = array();
		$this->encryption = null;
		$this->password   = null;
	}
	
	/**
	 * Surcharge la méthode __toString() de la classe parente.
	 * Signe et/ou chiffre l’email résultant à l’aide du programme GnuPG.
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		if( !is_executable($this->gpg_bin) ) {
			throw new Exception(sprintf("[%s] is not a valid executable.", $this->gpg_bin));
		}
		
		if( !((file_exists($this->logfile) && is_writable($this->logfile))
			|| is_writable(dirname($this->logfile))) )
		{
			throw new Exception(sprintf("Unable to write log file [%s].", $this->logfile));
		}
		
		$tmpdir    = escapeshellarg($this->tmpdir);
		$logfile   = escapeshellcmd($this->logfile);
		$digest_algo = escapeshellarg($this->digest_algo);
		$gpg_cmd   = escapeshellcmd($this->gpg_bin);
		$gpg_cmd  .= " --no-verbose --no-tty --batch --textmode --armor"
			. " --always-trust --comment \"Using GnuPG with Wamailer\"";
		
		if( !is_null($this->homedir) ) {
			$gpg_cmd .= sprintf(" --homedir %s", escapeshellarg($this->homedir));
		}
		
		/**
		 * Les emails signés et/ou chiffrés doivent être restreints
		 * aux caractères 7bit uniquement (us-ascii)
		 * 
		 * @see RFC 3156#3 - Content-Transfer-Encoding restrictions
		 */
		if( $this->_sign || $this->_encrypt ) {
			if( !is_null($this->_textPart) ) {
				$this->_textPart->encoding = 'quoted-printable';
			}
			
			if( !is_null($this->_htmlPart) ) {
				$this->_htmlPart->encoding = 'quoted-printable';
			}
		}
		
		parent::__toString();
		
		$headers = $this->headers_txt;
		$message = $this->message_txt;
		$tmpfile = tempnam($this->tmpdir, 'wa');
		file_put_contents($tmpfile, $message);
		
		//
		// Le message doit être chiffré (+ éventuellement signé)
		//
		if( $this->_encrypt ) {
			if( !is_null($this->cipher_algo) ) {
				$gpc_cmd .= sprintf(' --cipher-algo %s', escapeshellarg($this->cipher_algo));
			}
			
			if( $this->encryption == self::A_ENCRYPTION ) {
				if( count($this->recipients) == 0 ) {
					throw new Exception("No recipient specified!");
				}
				
				foreach( $this->recipients as $recipient ) {
					$gpg_cmd .= sprintf(' --recipient %s', escapeshellarg($recipient));
				}
				
				if( $this->_sign ) {
					if( !is_null($this->secretkey) ) {
						$gpg_cmd .= sprintf(" --local-user %s", escapeshellarg($this->secretkey));
					}
					
					if( !is_null($this->passphrase) ) {
						$gpg_cmd = sprintf("echo %s | %s --passphrase-fd 0",
							escapeshellarg($this->passphrase), $gpg_cmd);
					}
					
					$gpg_cmd = sprintf("%s --output - --digest-algo %s --encrypt --sign %s",
						$gpg_cmd, $digest_algo, escapeshellarg($tmpfile));
				}
				else {
					$gpg_cmd = sprintf("%s --output - --encrypt %s",
						$gpg_cmd, escapeshellarg($tmpfile));
				}
			}
			else {
				$password = escapeshellarg($this->password);
				if( $this->_sign ) {
					if( !is_null($this->secretkey) ) {
						$gpg_cmd .= sprintf(" --local-user %s", escapeshellarg($this->secretkey));
					}
					
					$gpg_cmd = sprintf("echo %s | %s --passphrase-fd 0"
						. " --output - --digest-algo %s --symmetric --sign %s",
						$password, $gpg_cmd, $digest_algo, escapeshellarg($tmpfile));
				}
				else {
					$gpg_cmd = sprintf("echo %s | %s --passphrase-fd 0"
						. " --output - --symmetric %s",
						$password, $gpg_cmd, escapeshellarg($tmpfile));
				}
			}
			
			exec("$gpg_cmd 2>$logfile", $output, $result);
			
			if( $result !== 0 ) {
				unlink($tmpfile);
				throw new Exception("Cannot encrypt email (GPG error)");
			}
			
			$encrypted_msg = implode("\r\n", $output);
			
			$gpg_sub1 = new Mime_Part();
			$gpg_sub1->headers->set('Content-Type', 'application/pgp-encrypted');
			$gpg_sub1->headers->set('Content-Description', 'PGP/MIME version identification');
			$gpg_sub1->body = 'Version: 1';
			
			$gpg_sub2 = new Mime_Part();
			$gpg_sub2->headers->set('Content-Type', 'application/octet-stream');
			$gpg_sub2->headers->get('Content-Type')->param('name', 'encrypted.asc');
			$gpg_sub2->headers->set('Content-Description', 'OpenPGP encrypted message');
			$gpg_sub2->headers->set('Content-Disposition', 'inline');
			$gpg_sub2->headers->get('Content-Disposition')->param('filename', 'encrypted.asc');
			$gpg_sub2->body = $encrypted_msg;
			$gpg_sub2->wraptext = false;
			
			//
			// Bloc MIME global
			//
			$gpg = new Mime_Part();
			$gpg->headers->set('Content-Type', 'multipart/encrypted');
			$gpg->headers->get('Content-Type')->param('protocol', 'application/pgp-encrypted');
			$gpg->body = 'This is an OpenPGP/MIME encrypted message (RFC 2440 and 3156)';
			$gpg->addSubPart($gpg_sub1);
			$gpg->addSubPart($gpg_sub2);
			
			$message = $gpg->__toString();
		}
		
		//
		// Le message doit être signé
		//
		else if( $this->_sign ) {
			if( !is_null($this->secretkey) ) {
				$gpg_cmd .= sprintf(" --local-user %s", escapeshellarg($this->secretkey));
			}
			
			if( !is_null($this->passphrase) ) {
				$gpg_cmd = sprintf("echo %s | %s --passphrase-fd 0",
					escapeshellarg($this->passphrase), $gpg_cmd);
			}
			
			$gpg_cmd = sprintf("%s --output - --digest-algo %s --detach-sign %s",
				$gpg_cmd, $digest_algo, escapeshellarg($tmpfile));
			exec("$gpg_cmd 2>$logfile", $output, $result);
			
			if( $result !== 0 ) {
				unlink($tmpfile);
				throw new Exception("Cannot sign email (GPG error)");
			}
			
			$signature = implode("\r\n", $output);
			
			$gpg_sub = new Mime_Part();
			$gpg_sub->headers->set('Content-Type', 'application/pgp-signature');
			$gpg_sub->headers->get('Content-Type')->param('name', 'signature.asc');
			$gpg_sub->headers->set('Content-Description', 'OpenPGP digital signature');
			$gpg_sub->headers->set('Content-Disposition', 'attachment');
			$gpg_sub->headers->get('Content-Disposition')->param('filename', 'signature.asc');
			$gpg_sub->body = $signature;
			$gpg_sub->wraptext = false;
			
			//
			// Bloc MIME global
			//
			$gpg = new Mime_Part();
			$gpg->headers->set('Content-Type', 'multipart/signed');
			$gpg->headers->get('Content-Type')->param('micalg', "pgp-$this->digest_algo");
			$gpg->headers->get('Content-Type')->param('protocol', 'application/pgp-signature');
			$gpg->body = "This is an OpenPGP/MIME signed message (RFC 2440 and 3156).";
			$gpg->addSubPart($message);
			$gpg->addSubPart($gpg_sub);
			
			$message = $gpg->__toString();
		}
		
		unlink($tmpfile);
		
		return $headers . $message;
	}
}

?>
