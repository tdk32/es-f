<?php
/**
 * Package Loader
 *
 * description ...
 *
 * @ingroup    es-f
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    http://www.gnu.org/licenses/gpl.txt GNU General Public License
 * @version    $Id: v2.4.1-29-gacb4bc2 - Fri Jan 7 21:24:31 2011 +0100 $
 */
abstract class Loader {

  // -------------------------------------------------------------------------
  // PUBLIC
  // -------------------------------------------------------------------------

  /**
   * @var array
   */
  public static $AutoLoadPath = array();

  /**
   * Function setPreload...
   *
   * @public
   * @param string $function Callback function pre load with one parameter: $file
   * @return boolean
   */
  public static function setPreload( $function=NULL ) {
    self::setCallback('Pre', $function);
  } // function setPreload()

  /**
   * Function setPostload...
   *
   * @public
   * @param string $function Callback function after load with one parameter: $file
   * @return boolean
   */
  public static function setPostload( $function=NULL ) {
    self::setCallback('Post', $function);
  } // function setPostload()

  /**
   * Function Load...
   *
   * @public
   * @param string $file
   * @return boolean
   */
  public static function Load( $file, $once=TRUE, $throw=TRUE ) {
    $file = str_replace('/', DIRECTORY_SEPARATOR, $file);
    if (file_exists($file)) {
      self::callback('Pre', $file);
      if ($once)
        include_once $file;
      else
        include $file;
      self::callback('Post', $file);
      return TRUE;
    } elseif ($throw) {
      throw new LoaderException(__METHOD__.' : Missing file: '.$file);
    }
    return FALSE;
  } // function Load()

  /**
   * Function Autoload...
   *
   * @public
   * @param string $class
   * @return void
   */
  public static function __autoload( $class ) {
    $cpath = str_replace('_', DIRECTORY_SEPARATOR, strtolower($class));

    foreach (array(strtolower($cpath), $cpath) as $path) {
      foreach (self::$AutoLoadPath as $dir) {
        foreach (array('%s.class.php', '%s.if.php', '%s.php') as $file) {
          $file = sprintf($dir.DIRECTORY_SEPARATOR.$file, $path);
          // Don't throw an exception!
          if (self::Load($file, TRUE, FALSE)) {
            /// Yryie::Debug($class.' ('.$file.')');
            return;
          }
        }
      }
    }
  } // function Autoload()

  /**
   * Function Register...
   *
   * @public
   * @return boolean
   */
  public static function Register( $throw=TRUE ) {
    if (function_exists('spl_autoload_register'))
      return spl_autoload_register(array(__CLASS__, '__autoload'), $throw);
    else
      return FALSE;
  } // function Register()

  // -------------------------------------------------------------------------
  // PROTECTED
  // -------------------------------------------------------------------------

  // -------------------------------------------------------------------------
  // PRIVATE
  // -------------------------------------------------------------------------

  /**
   *
   */
  private static $Callbacks = array();

  /**
   *
   */
  private static function setCallback( $step, $function ) {
    if ($function AND !function_exists($function))
      throw new LoaderException('Missing callback function: '.$function);
    self::$Callbacks[$step] = $function;
  }

  /**
   *
   */
  private static function callback( $step, &$file ) {
    if (empty(self::$Callbacks[$step])) return;
    $callback = self::$Callbacks[$step];
    $callback($file);
  }

}

/**
 * Loader exception class
 */
class LoaderException extends Exception {}
