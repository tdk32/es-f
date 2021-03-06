<?php
/**
 * Handle application messages
 *
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2007-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */

abstract class Messages {

  /**
   * @name Message type
   * @{
   */
  const INFO    = 'info';
  const SUCCESS = 'success';
  const ERROR   = 'error';
  const CODE    = 'code';
  /** @} */

  /**
   * Variable name to store messages into session
   *
   * @var string $SessionVar
   */
  public static $SessionVar = '__MESSAGES__';

  /**
   * html code to format messages for output
   *
   * string param 1: message type: info|error|success
   * string param 2: message text
   *
   * @var string $OutHTML
   */
  public static $OutHTML = '<div class="msg%1$s">%2$s</div>';

  /**
   * Format message string
   *
   * @param mixed $msg Message(s)
   * @param string $type Message type
   * @param boolean $formated Message is still HTML formated
   * @return string
   */
  public static function toStr( $msg, $type=MSG_INFO, $formated=FALSE ) {
    if (is_array($msg)) {
      if (!$formated) $msg = array_map_recursive('htmlspecialchars', $msg);
    } else {
      @list($func, $msg) = explode(':', $msg, 2);
      if (!$msg) {
        $msg = $func;
      } elseif ($func == 'html') {
        $formated = TRUE;
      } else {
        $msg = $func.':'.$msg;
      }
      if (!$formated) $msg = htmlspecialchars($msg, ENT_QUOTES);
    }
    $return = '';
    foreach ((array)$msg as $m)
      $return .= sprintf(self::$OutHTML, $type, $m)."\n";

    return $return;
  }

  /**
   * Store info message into buffer
   *
   * @param mixed $msg Message(s)
   * @param boolean $formated Message is still HTML formated
   */
  public static function Info( $msg, $formated=FALSE ) {
    self::add($msg, self::INFO, $formated);
  }

  /**
   * Store success message into buffer
   *
   * @param mixed $msg Message(s)
   * @param boolean $formated Message is still HTML formated
   */
  public static function Success( $msg, $formated=FALSE ) {
    self::add($msg, self::SUCCESS, $formated);
  }

  /**
   * Store error message into buffer
   *
   * @param mixed $msg Message(s)
   * @param boolean $formated Message is still HTML formated
   */
  public static function Error( $msg, $formated=FALSE ) {
    if (!is_array($msg)) $msg = '[Error] '.$msg;
    self::add($msg, self::ERROR, $formated);
  }

  /**
   * Store code message into buffer
   *
   * @param mixed $msg Message(s)
   * @param boolean $formated Message is still HTML formated
   */
  public static function Code( $msg, $formated=FALSE ) {
    self::add($msg, self::CODE, $formated);
  }

  /**
   * Generic function to store message into buffer
   *
   * @param mixed $msg Message(s)
   * @param string $type Message type
   * @param boolean $formated Message is still HTML formated
   */
  public static function add( $msg, $type, $formated ) {
    Session::addP(self::$SessionVar, array($msg, $type, $formated));
  }

  /**
   * Get messages from buffer an clear buffer if requested
   *
   * @param boolean $clear Clear buffer
   * @return array
   */
  public static function get( $clear=TRUE ) {
    $msgs = array();
    foreach ((array) Session::getP(self::$SessionVar) as $msg)
      $msgs[] = self::toStr($msg[0], $msg[1], $msg[2]);
    if ($clear) self::clear();
    return $msgs;
  }

  /**
   * Get message count from buffer according to type
   *
   * @param boolean $type If not set return count of all messages
   * @return int
   */
  public static function count( $type=NULL ) {
    if (!isset($type)) {
      $cnt = count((array) Session::getP(self::$SessionVar));
    } else {
      $msgs = (array) Session::getP(self::$SessionVar);
      $cnt = 0;
      foreach ($msgs as $msg) if ($msg[1] == $type) $cnt++;
    }
    return $cnt;
  }

  /**
   * Clear message buffer
   */
  public static function clear() {
    Session::setP(self::$SessionVar);
  }
}
