<?php
/**
 * XML object to configuration array
 *
 * @ingroup    XMLArray
 * @author     Knut Kohl <knutkohl@users.sourceforge.net>
 * @copyright  2009-2011 Knut Kohl
 * @license    GNU General Public License http://www.gnu.org/licenses/gpl.txt
 * @version    1.0.0
 * @version    $Id: v2.4.1-62-gb38404e 2011-01-30 22:35:34 +0100 $
 */
class XML_Array_Configuration extends XML_Array {

  /**
   *
   */
  public function XML2Array( $childs ) {

    if (!is_array($childs)) return $childs;

    $data = array();
    foreach ($childs as $child) {
      if ($child->Name == 'section') {
        $data[$this->map($child->Attributes->Name)] = $this->XML2Array($child->Childs);
      } elseif ($child->Name == 'config') {
        $name = $child->Attributes->Name;
        if (!$child->Attributes->CaseSensitive) $name = $this->map($name);
        if ($name)
          $data[$name] = $this->getChildValue($child);
        else
          $data[] = $this->getChildValue($child);
      }
    }
    return $data;
  }
}