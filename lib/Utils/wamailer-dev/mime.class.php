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
 * @version $Id: mime.class.php 28 2010-09-24 17:51:19Z bobe $
 * 
 * @see RFC 2045 - Multipurpose Internet Mail Extensions (MIME) Part One: Format of Internet Message Bodies
 * @see RFC 2046 - Multipurpose Internet Mail Extensions (MIME) Part Two: Media Types
 * @see RFC 2047 - Multipurpose Internet Mail Extensions (MIME) Part Three: Message Header Extensions for Non-ASCII Text
 * @see RFC 2048 - Multipurpose Internet Mail Extensions (MIME) Part Four: Registration Procedures
 * @see RFC 2049 - Multipurpose Internet Mail Extensions (MIME) Part Five: Conformance Criteria and Examples
 * @see RFC 2076 - Common Internet Message Headers
 * @see RFC 2111 - Content-ID and Message-ID Uniform Resource Locators
 * @see RFC 2183 - Communicating Presentation Information in Internet Messages: The Content-Disposition Header Field
 * @see RFC 2231 - MIME Parameter Value and Encoded Word Extensions: Character Sets, Languages, and Continuations
 * @see RFC 2822 - Internet Message Format
 * 
 * Les sources qui m’ont bien aidées :
 * 
 * @link http://abcdrfc.free.fr/ (français)
 * @link http://www.faqs.org/rfcs/ (anglais)
 */

class Mime {
	
	/**
	 * Utilisé dans la méthode Mime::encodeHeader() pour détecter
	 * si un octet donné est le début d’une séquence d’octets utf-8
	 * 
	 * @static
	 * @var array
	 * @access private
	 */
	private static $_utf8test = array(
		0x80 => 0, 0xE0 => 0xC0, 0xF0 => 0xE0, 0xF8 => 0xF0, 0xFC => 0xF8, 0xFE => 0xFC
	);
	
	/**
	 * Encode le texte au format quoted-printable tel que défini dans la RFC 2045
	 * 
	 * @see RFC 2045 (sec. 6.7)
	 * 
	 * @param string $str
	 * 
	 * @static
	 * @access public
	 * @return string
	 */
	public static function quotedPrintableEncode($str)
	{
		$str = preg_replace('/\r\n?|\n/', "\r\n", $str);
		$str = preg_replace('/([^\x09\x0A\x0D\x20-\x3C\x3E-\x7E]|[\x09\x20](?=\x0D\x0A|$))/e',
			'sprintf(\'=%02X\', ord("\\1"));', $str);
		
		$maxlen = 76;
		$lines  = explode("\r\n", $str);
		
		foreach( $lines as &$line ) {
			if( ($strlen = strlen($line)) == 0 ) {
				continue;
			}
			
			$newline = '';
			$pos = 0;
			
			while( $pos < $strlen ) {
				$tmplen = $maxlen;
				$i = min(($pos + $tmplen), $strlen);
				
				// Si une coupure est nécessaire, on fait de la place pour
				// le signe égal, "soft break"
				if( $i < $strlen ) {
					$tmplen--;
					$i--;
				}
				
				while( $line[$i-1] == '=' || $line[$i-2] == '=' ) {
					$tmplen--;
					$i--;
				}
				
				$newline .= substr($line, $pos, $tmplen) . "=\r\n";
				$pos += $tmplen;
			}
			
			$line = rtrim($newline, "=\r\n");
		}
		
		return implode("\r\n", $lines);
	}
	
	/**
	 * Encode la valeur d’un en-tête si elle contient des caractères non-ascii.
	 * Autrement, des guillemets peuvent néanmoins être ajoutés aux extrémités
	 * si des caractères interdits pour le token considéré sont présents.
	 * 
	 * @param string $header   Nom de l’en-tête concerné
	 * @param string $header   Valeur d’en-tête à encoder
	 * @param string $charset  Jeu de caractères utilisé
	 * @param string $token
	 * 
	 * @static
	 * @access public
	 * @return string
	 */
	public static function encodeHeader($name, $value, $charset, $token = 'text')
	{
		if( preg_match('/[\x00-\x1F\x7F-\xFF]/', $value) ) {
			
			$maxlen = 76;
			$sep = "\r\n\t";
			
			switch( $token ) {
				case 'comment':
					$charlist = '\x00-\x1F\x22\x28\x29\x3A\x3D\x3F\x5F\x7F-\xFF';
					break;
				case 'phrase':
					$charlist = '\x00-\x1F\x22-\x29\x2C\x2E\x3A\x40\x5B-\x60\x7B-\xFF';
					break;
				case 'text':
				default:
					$charlist = '\x00-\x1F\x3A\x3D\x3F\x5F\x7F-\xFF';
					break;
			}
			
			/**
			 * Si le nombre d’octets à encoder représente plus de 33% de la chaîne,
			 * nous utiliserons l’encodage base64 qui garantit une chaîne encodée 33%
			 * plus longue que l’originale, sinon, on utilise l’encodage "Q".
			 * La RFC 2047 recommande d’utiliser pour chaque cas l’encodage produisant
			 * le résultat le plus court.
			 * 
			 * @see RFC 2045#6.8
			 * @see RFC 2047#4
			 */
			$q = preg_match_all("/[$charlist]/", $value, $matches);
			$strlen   = strlen($value);
			$encoding = (($q / $strlen) < 0.33) ? 'Q' : 'B';
			$template = sprintf('=?%s?%s?%%s?=%s', $charset, $encoding, $sep);
			$maxlen   = ($maxlen - strlen($template) + strlen($sep) + 2);// + 2 pour le %s dans le modèle
			$is_utf8  = (strcasecmp($charset, 'UTF-8') == 0);
			$newbody  = '';
			$pos = 0;
			
			while( $pos < $strlen ) {
				$tmplen = $maxlen;
				if( $newbody == '' ) {
					$tmplen -= strlen($name . ': ');
					if( $encoding == 'Q' ) $tmplen++;// TODO : à comprendre
				}
				
				if( $encoding == 'Q' ) {
					$q = preg_match_all("/[$charlist]/", substr($value, $pos, $tmplen), $matches);
					// chacun des octets trouvés prendra trois fois plus de place dans
					// la chaîne encodée. On retranche cette valeur de la longueur du tronçon
					$tmplen -= ($q * 2);
				}
				else {
					/**
					 * La longueur de l'encoded-text' doit être un multiple de 4
					 * pour ne pas casser l’encodage base64
					 * 
					 * @see RFC 2047#5
					 */
					$tmplen -= ($tmplen % 4);
					$tmplen = floor(($tmplen/4)*3);
				}
				
				if( $is_utf8 ) {
					/**
					 * Il est interdit de sectionner un caractère multi-octet.
					 * On teste chaque octet en partant de la fin du tronçon en cours
					 * jusqu’à tomber sur un caractère ascii ou l’octet de début de
					 * séquence d’un caractère multi-octets.
					 * On vérifie alors qu’il y bien $m octets qui suivent (le cas échéant).
					 * Si ce n’est pas le cas, on réduit la longueur du tronçon.
					 * 
					 * @see RFC 2047#5
					 */
					for( $i = min(($pos + $tmplen), $strlen), $c = 1; $i > $pos; $i--, $c++ ) {
						$d = ord($value[$i-1]);
						
						reset(self::$_utf8test);
						for( $m = 1; $m <= 6; $m++ ) {
							$test = each(self::$_utf8test);
							if( ($d & $test[0]) == $test[1] ) {
								if( $c < $m ) {
									$tmplen -= $c;
								}
								break 2;
							}
						}
					}
				}
				
				$tmp = substr($value, $pos, $tmplen);
				if( $encoding == 'Q' ) {
					$tmp = preg_replace("/([$charlist])/e", 'sprintf(\'=%02X\', ord("\\1"));', $tmp);
					$tmp = str_replace(' ', '_', $tmp);
				}
				else {
					$tmp = base64_encode($tmp);
				}
				
				$newbody .= sprintf($template, $tmp);
				$pos += $tmplen;
			}
			
			$value = rtrim($newbody);
		}
		else if( $token != 'text' ) {
			if( preg_match('/[^!#$%&\'*+\/0-9=?a-z^_`{|}~-]/', $value) ) {
				$value = '"'.$value.'"';
			}
		}
		
		return $value;
	}
	
	/**
	 * @param string  $str
	 * @param integer $maxlen
	 * 
	 * @static
	 * @access public
	 * @return string
	 */
	public static function wordwrap($str, $maxlen = 78)
	{
		if( strlen($str) > $maxlen ) {
			$lines = explode("\r\n", $str);
			foreach( $lines as &$line ) {
				$line = wordwrap($line, $maxlen, "\r\n");
			}
			$str = implode("\r\n", $lines);
		}
		
		return $str;
	}
	
	/**
	 * @param string $filename
	 * 
	 * @static
	 * @access public
	 * @return string
	 */
	public static function getType($filename)
	{
		if( !is_readable($filename) ) {
			throw new Exception("Cannot read file '$filename'");
		}
		
		if( extension_loaded('fileinfo') ) {
			$info = new finfo(FILEINFO_MIME);
			$type = $info->file($filename);
		}
		else if( function_exists('exec') ) {
			$type = exec(sprintf('file -biL %s 2>/dev/null',
				escapeshellarg($filename)), $null, $result);
			
			if( $result !== 0 || !strpos($type, '/') ) {
				$type = '';
			}
/*			else {
				if( strpos($type, ';') ) {
					list($type) = explode(';', $type);
				}
			}*/
		}
		else if( extension_loaded('mime_magic') ) {
			$type = mime_content_type($filename);
		}
		
		if( empty($type) ) {
			$type = 'application/octet-stream';
		}
		
		return trim($type);
	}
}

class Mime_Part {
	
	/**
	 * Bloc d’en-têtes de cette partie
	 * 
	 * @var object
	 * @see Mime_Headers class
	 * @access public
	 */
	public $headers   = null;
	
	/**
	 * Contenu de cette partie
	 * 
	 * @var mixed
	 * @access public
	 */
	public $body      = null;
	
	/**
	 * tableau des éventuelles sous-parties
	 * 
	 * @var mixed
	 * @access public
	 */
	private $subparts = array();
	
	/**
	 * Frontière de séparation entre les différentes sous-parties
	 * 
	 * @var string
	 * @access private
	 */
	private $boundary  = null;
	
	/**
	 * Limitation de longueur des lignes de texte.
	 * Par défaut, la limitation est celle imposée par la RFC2822,
	 * à savoir 998 octets + CRLF
	 * Si cet attribut est placé à true, la limitation est de 78
	 * octets + CRLF
	 * 
	 * @var boolean
	 * @access public
	 */
	public $wraptext  = true;
	
	/**
	 * Constructeur de classe
	 * 
	 * @param string $body
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($body = null, $headers = null)
	{
		$this->headers = new Mime_Headers($headers);
		
		if( !is_null($body) ) {
			$this->body = $body;
		}
	}
	
	/**
	 * Ajout de sous-partie(s) à ce bloc MIME
	 * 
	 * @param mixed $subpart  Peut être un objet Mime_Part, un tableau
	 *                        d’objets Mime_Part, ou simplement une chaîne
	 * 
	 * @access public
	 * @return void
	 */
	public function addSubPart($subpart)
	{
		if( is_array($subpart) ) {
			$this->subparts = array_merge($this->subparts, $subpart);
		}
		else {
			array_push($this->subparts, $subpart);
		}
	}
	
	/**
	 * Indique si ce bloc MIME contient des sous-parties
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isMultiPart()
	{
		return count($this->subparts) > 0;
	}
	
	/**
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		if( $this->headers->get('Content-Type') == null ) {
			$this->headers->set('Content-Type', 'application/octet-stream');
		}
		
		$body = $this->body;
		
		if( $this->isMultiPart() ) {
			$this->boundary = '--=_Part_' . md5(microtime());
			$this->headers->get('Content-Type')->param('boundary', $this->boundary);
			
			if( $body != '' ) {
				$body .= "\r\n\r\n";
			}
			
			foreach( $this->subparts as $subpart ) {
				$body .= '--' . $this->boundary . "\r\n";
				$body .= !is_string($subpart) ? $subpart->__toString() : $subpart;
				$body .= "\r\n";
			}
			
			$body .= '--' . $this->boundary . "--\r\n";
		}
		else {
			if( $encoding = $this->headers->get('Content-Transfer-Encoding') ) {
				$encoding = strtolower($encoding->value);
			}
			
			if( !in_array($encoding, array('7bit', '8bit', 'quoted-printable', 'base64', 'binary')) ) {
				$this->headers->remove('Content-Transfer-Encoding');
				$encoding = '7bit';
			}
			
			switch( $encoding ) {
				case 'quoted-printable':
					/**
					 * Encodage en chaîne à guillemets
					 * 
					 * @see RFC 2045#6.7
					 */
					$body = Mime::quotedPrintableEncode($body);
					break;
				case 'base64':
					/**
					 * Encodage en base64
					 * 
					 * @see RFC 2045#6.8
					 */
					$body = rtrim(chunk_split(base64_encode($body)));
					break;
				case '7bit':
				case '8bit':
					$body = preg_replace("/\r\n?|\n/", "\r\n", $body);
					
					/**
					 * Limitation sur les longueurs des lignes de texte.
					 * La limite basse est de 78 caractères par ligne.
					 * En tout état de cause, chaque ligne ne DOIT PAS
					 * faire plus de 998 caractères.
					 * 
					 * @see RFC 2822#2.1.1
					 */
					$body = Mime::wordwrap($body, $this->wraptext ? 78 : 998);
					break;
			}
		}
		
		return $this->headers->__toString() . "\r\n" . $body;
	}
	
	public function __set($name, $value)
	{
		if( $name == 'encoding' ) {
			$this->headers->set('Content-Transfer-Encoding', $value);
		}
	}
	
	public function __get($name)
	{
		$value = null;
		
		if( $name == 'encoding' ) {
			if( $encoding = $this->headers->get('Content-Transfer-Encoding') ) {
				$value = $encoding->value;
			}
			else {
				$value = '7bit';
			}
		}
		
		return $value;
	}
	
	public function __clone()
	{
		$this->headers = clone $this->headers;
		
		if( is_array($this->subparts) ) {
			foreach( $this->subparts as &$subpart ) {
				$subpart = clone $subpart;
			}
		}
	}
}

class Mime_Headers implements Iterator {
	
	/**
	 * Tableau d’en-têtes
	 * 
	 * @var array
	 * @access private
	 */
	private $headers = array();
	
	private $_it_tot = 0;
	private $_it_ind = 0;
	private $_it_obj = null;
	
	/**
	 * Constructeur de classe
	 * 
	 * @param array $headers  Tableau d’en-têtes d’email à ajouter dans l’objet
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($headers = null)
	{
		if( is_array($headers) ) {
			foreach( $headers as $name => $value ) {
				$this->add($name, $value);
			}
		}
	}
	
	/**
	 * Ajout d’un en-tête
	 * 
	 * @param string $name   Nom de l’en-tête 
	 * @param string $value  Valeur de l’en-tête
	 * 
	 * @access public
	 * @return Mime_Header
	 */
	public function add($name, $value)
	{
		$header = new Mime_Header($name, $value);
		$name   = strtolower($header->name);
		
		if( $this->get($name) != null ) {
			if( !is_array($this->headers[$name]) ) {
				$this->headers[$name] = array($this->headers[$name]);
			}
			
			array_push($this->headers[$name], $header);
		}
		else {
			$this->headers[$name] = $header;
		}
		
		return $header;
	}
	
	/**
	 * Ajout d’un en-tête, en écrasant si besoin la valeur précédemment affectée
	 * à l’en-tête de même nom
	 * 
	 * @param string $name   Nom de l’en-tête
	 * @param string $value  Valeur de l’en-tête
	 * 
	 * @access public
	 * @return Mime_Header
	 */
	public function set($name, $value)
	{
		$header = new Mime_Header($name, $value);
		$this->headers[strtolower($name)] = $header;
		
		return $header;
	}
	
	/**
	 * Retourne l’objet Mime_Header ou un tableau d’objets correspondant au nom d’en-tête donné
	 * 
	 * @param string $name  Nom de l’en-tête
	 * 
	 * @access public
	 * @return mixed
	 */
	public function get($name)
	{
		$name = strtolower($name);
		if( isset($this->headers[$name])
			&& (is_array($this->headers[$name]) || $this->headers[$name]->value != '') )
		{
			return $this->headers[$name];
		}
		
		return null;
	}
	
	/**
	 * Supprime le ou les en-têtes correspondants au nom d’en-tête donné dans $name
	 * 
	 * @param string $name  Nom de l’en-tête
	 * 
	 * @access public
	 * @return void
	 */
	public function remove($name)
	{
		$name = strtolower($name);
		if( isset($this->headers[$name]) ) {
			unset($this->headers[$name]);
		}
	}
	
	public function current()
	{
		return $this->_it_obj->value;
	}
	
	public function key()
	{
		return $this->_it_obj->name;
	}
	
	public function next()
	{
		$this->_it_ind++;
	}
	
	public function rewind()
	{
		reset($this->headers);
		$this->_it_tot = count($this->headers);
		$this->_it_ind = 0;
	}
	
	public function valid()
	{
		if( $this->_it_ind < $this->_it_tot ) {
			$tmp = each($this->headers);
			$this->_it_obj = $tmp['value'];
			$ret = true;
		}
		else {
			$ret = false;
		}
		
		return $ret;
	}
	
	/**
	 * Retourne le bloc d’en-têtes sous forme de chaîne
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		$str = '';
		foreach( $this->headers as $headers ) {
			if( !is_array($headers) ) {
				$headers = array($headers);
			}
			
			foreach( $headers as $header ) {
				if( $header->value != '' ) {
					$str .= $header->__toString();
					$str .= "\r\n";
				}
			}
		}
		
		return $str;
	}
	
	public function __clone()
	{
		foreach( $this->headers as &$headers ) {
			if( is_array($headers) ) {
				foreach( $headers as &$header ) {
					$header = clone $header;
				}
			}
			else {
				$headers = clone $headers;
			}
		}
	}
}

class Mime_Header {
	
	/**
	 * Nom de l’en-tête
	 * 
	 * @var string
	 * @access private
	 */
	private $_name;
	
	/**
	 * Valeur de l’en-tête
	 * 
	 * @var string
	 * @access private
	 */
	private $_value;
	
	/**
	 * Liste des paramètres associés à la valeur de cet en-tête
	 * 
	 * @var array
	 * @access private
	 */
	private $params = array();
	
	/**
	 * Active/Désactive le pliage des entêtes tel que décrit dans la RFC 2822
	 * 
	 * @see RFC 2822#2.2.3 Long Header Fields
	 * 
	 * @var boolean
	 * @access public
	 */
	public $folding = true;
	
	/**
	 * Constructeur de classe
	 * 
	 * @param string $name   Nom de l’en-tête
	 * @param string $value  Valeur de l’en-tête
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct($name, $value)
	{
		$name  = self::validName($name);
		$value = self::sanitizeValue($value);
		
		if( ($name == 'Content-Type' || $name == 'Content-Disposition') && strpos($value, ';') ) {
			list($value, $params) = explode(';', $value, 2);
			preg_match_all('/([\x21-\x39\x3B-\x7E]+)=(")?(.+?)(?(2)(?<!\\\\)(?:\\\\\\\\)")(?=;|$)/S',
				$params, $matches, PREG_SET_ORDER);
			
			foreach( $matches as $param ) {
				$this->param($param[1], $param[3]);
			}
		}
		
		$this->_name  = $name;
		$this->_value = $value;
	}
	
	/**
	 * Le nom de l’en-tête ne doit contenir que des caractères us-ascii imprimables,
	 * et ne doit pas contenir le caractère deux points (:)
	 * 
	 * @see RFC 2822#2.2
	 * 
	 * @param string $name
	 * 
	 * @access public
	 * @return string
	 */
	public function validName($name)
	{
		if( !preg_match('/^[\x21-\x39\x3B-\x7E]+$/', $name) ) {
			throw new Exception("'$name' is not a valid header name!");
		}
		
		return str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
	}
	
	/**
	 * Le contenu de l’en-tête ne doit contenir aucun retour chariot
	 * ou saut de ligne
	 *
	 * @see RFC 2822#2.2
	 * 
	 * @param string $value
	 * 
	 * @access public
	 * @return string
	 */
	public function sanitizeValue($value)
	{
		return preg_replace('/\s+/S', ' ', trim($value));
	}
	
	/**
	 * Vérifie si la chaîne passée en argument est un 'token' tel que défini dans la RFC 2045
	 * 
	 * @see RFC 2045#5.1
	 * 
	 * @param string $str
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isToken($str)
	{
		/**
		 * Tout caractère ASCII est accepté à l’exception des caractères de contrôle, de l’espace
		 * et des caractères spéciaux listés ci-dessous.
		 * 
		 * token := 1*<any (US-ASCII) CHAR except SPACE, CTLs, or tspecials>
		 * 
		 * tspecials := "(" / ")" / "<" / ">" / "@" /
		 *              "," / ";" / ":" / "\" / <">
		 *              "/" / "[" / "]" / "?" / "="
		 */
		return (bool) !preg_match('/[^\x21\x23-\x27\x2A\x2B\x2D\x2E\x30-\x39\x5E-\x7E]/Si', $str);
	}
	
	/**
	 * Complète la valeur de l’en-tête
	 * 
	 * @param string $str
	 * 
	 * @access public
	 * @return void
	 */
	public function append($str)
	{
		$this->_value .= self::sanitizeValue($str);
	}
	
	/**
	 * Ajoute un paramètre à l’en-tête
	 * 
	 * @param string $name   Nom du paramètre
	 * @param string $value  Valeur du paramètre
	 * 
	 * @access public
	 * @return string
	 */
	public function param($name, $value = null)
	{
		$curVal = null;
		if( isset($this->params[$name]) ) {
			$curVal = $this->params[$name];
		}
		else if( !self::isToken($name) ) {
			$value = null;
		}
		
		if( !is_null($value) ) {
			$this->params[$name] = strval($value);
		}
		
		return $curVal;
	}
	
	/**
	 * Renvoie l’en-tête sous forme de chaîne formatée
	 * 
	 * @access public
	 * @return string
	 */
	public function __toString()
	{
		$value = $this->_value;
		
		foreach( $this->params as $pName => $pValue ) {
			if( empty($pValue) ) {
				continue;
			}
			
			if( !self::isToken($pValue) ) {
				/**
				 * Syntaxe spécifique pour les valeurs comportant
				 * des caractères non-ascii.
				 * 
				 * @see RFC 2231#4
				 */
				if( preg_match('/[\x80-\xFF]/S', $pValue) ) {
					$pName .= '*';
					$pValue = 'UTF-8\'\'' . rawurlencode($pValue);// TODO charset
				}
				else {
					$pValue = '"' . $pValue . '"';
				}
			}
			
			$value .= sprintf('; %s=%s', $pName, $pValue);
		}
		
		$value = sprintf('%s: %s', $this->_name, $value);
		
		if( $this->folding ) {
			$value = wordwrap($value, 77, "\r\n\t");
		}
		
		return $value;
	}
	
	public function __get($name)
	{
		$value = null;
		
		switch( $name ) {
			case 'name':
			case 'value':
				$value = $this->{'_'.$name};
				break;
		}
		
		return $value;
	}
}

?>
