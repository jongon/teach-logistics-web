<?php
/**************************************************************
 *                                                            *
 *   Provides functions required by plugin's functionality    *
 *   Version: 2.13                                            *
 *   Author: greenline                                        *
 *   Profile: http://codecanyon.net/user/greenline            *
 *                                                            *
 **************************************************************/
 
/**
 * Returns the correct icon url based on a certain icon color or icon url
 */
function getIcon($value) {
  $icon = $value;
  switch (strtolower(trim($value))) {
      case 'black':
        $icon = plugins_url('/includes/icons/black.png', __FILE__);
      break;
      case 'blue':
        $icon = plugins_url('/includes/icons/blue.png', __FILE__);
      break;
      case 'gray':
        $icon = plugins_url('/includes/icons/gray.png', __FILE__);
      break;
      case 'green':
        $icon = plugins_url('/includes/icons/green.png', __FILE__);
      break;
      case 'magenta':
        $icon = plugins_url('/includes/icons/magenta.png', __FILE__);
      break;
      case 'orange':
        $icon = plugins_url('/includes/icons/orange.png', __FILE__);
      break;
      case 'purple':
        $icon = plugins_url('/includes/icons/purple.png', __FILE__);
      break;
      case 'red':
        $icon = plugins_url('/includes/icons/red.png', __FILE__);
      break;
      case 'white':
        $icon = plugins_url('/includes/icons/white.png', __FILE__);
      break;
      case 'yellow':
        $icon = plugins_url('/includes/icons/yellow.png', __FILE__);
      break;
      case '':
        $icon = plugins_url('/includes/icons/gray.png', __FILE__);
      break;
      case 'default':
        $icon = plugins_url('/includes/icons/gray.png', __FILE__);
      break;
      default:
  }
  return $icon;
}

/**
 * Transforms "yes" / "no" strings to true/false boolean values.
 * Returns true if string parameter was "yes"; returns false if string parameter was "no".
 */
function toBool($string) {
    switch (strtolower($string)) {
      case 'yes':
      case 'YES':
        $string = 'true';
      break;
      case 'no':
      case 'NO':
        $string = 'false';
      break;
      case 'default':
        $string = 'false';
      break;
      default:
    }
    return $string;
}

/**
  * Encodes and strips html tags inside the parameter attr.
  * Returns the attr with html tags escaped and stripped.
 */
function cleanHtml($attr) {
    $attr = str_replace(array("\n", '"', "'", "{br}", "&lt;", "&gt;"), array(' ', '\"', "\'", "<br>", "<", ">"), $attr);
    if (substr_count($attr, '|') == 1) {
      $tmp = explode('|', $attr);
      $attr = '<strong>' . $tmp[0] . '</strong><br />' . $tmp[1];
    }
    return $attr;
}
?>