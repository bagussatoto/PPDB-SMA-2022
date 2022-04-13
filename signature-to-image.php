<?php
/**
 *  Signature to Image: A supplemental script for Signature Pad that
 *  generates an image of the signature’s JSON output server-side using PHP.
 *
 *  @project ca.thomasjbradley.applications.signaturetoimage
 *  @author Thomas J Bradley <hey@thomasjbradley.ca>
 *  @link http://thomasjbradley.ca/lab/signature-to-image
 *  @link http://github.com/thomasjbradley/signature-to-image
 *  @copyright Copyright MMXI–, Thomas J Bradley
 *  @license New BSD License
 *  @version 1.1.0
 */

/**
 *  Accepts a signature created by signature pad in Json format
 *  Converts it to an image resource
 *  The image resource can then be changed into png, jpg whatever PHP GD supports
 *
 *  To create a nicely anti-aliased graphic the signature is drawn 12 times it's original size then shrunken
 *
 *  @param string|array $json
 *  @param array $options OPTIONAL; the options for image creation
 *    imageSize => array(width, height)
 *    bgColour => array(red, green, blue) | transparent
 *    penWidth => int
 *    penColour => array(red, green, blue)
 *    drawMultiplier => int
 *
 *  @return object
 */
function sigJsonToImage ($json, $options = array()) {
  $defaultOptions = array(
    'imageSize' => array(198, 55)
    ,'bgColour' => "#ffffff"
    ,'penWidth' => 4
    ,'penColour' => "#145394"
    ,'drawMultiplier'=> 5
  );

  $options = array_merge($defaultOptions, $options);
  $img = imagecreatetruecolor($options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][1] * $options['drawMultiplier']);

  if ($options['bgColour'] == 'transparent') {
    imagesavealpha($img, true);
    $bg = imagecolorallocatealpha($img, 0, 0, 0, 127);
  } else {
	$bgColor = colorToRGB($options['bgColour']);
    $bg = imagecolorallocate($img, $bgColor[0], $bgColor[1], $bgColor[2]);
  }
  $penColor = colorToRGB($options['penColour']);
  $pen = imagecolorallocate($img, $penColor[0], $penColor[1], $penColor[2]);
  imagefill($img, 0, 0, $bg);

  if (is_string($json))
    $json = json_decode(stripslashes($json));

  foreach ($json as $v)
    drawThickLine($img, $v->lx * $options['drawMultiplier'], $v->ly * $options['drawMultiplier'], $v->mx * $options['drawMultiplier'], $v->my * $options['drawMultiplier'], $pen, $options['penWidth'] * ($options['drawMultiplier'] / 2));

  $imgDest = imagecreatetruecolor($options['imageSize'][0], $options['imageSize'][1]);

  if ($options['bgColour'] == 'transparent') {
    imagealphablending($imgDest, false);
    imagesavealpha($imgDest, true);
  }

  imagecopyresampled($imgDest, $img, 0, 0, 0, 0, $options['imageSize'][0], $options['imageSize'][0], $options['imageSize'][0] * $options['drawMultiplier'], $options['imageSize'][0] * $options['drawMultiplier']);
  imagedestroy($img);

  return $imgDest;
}

/**
 *  Draws a thick line
 *  Changing the thickness of a line using imagesetthickness doesn't produce as nice of result
 *
 *  @param object $img
 *  @param int $startX
 *  @param int $startY
 *  @param int $endX
 *  @param int $endY
 *  @param object $colour
 *  @param int $thickness
 *
 *  @return void
 */
function drawThickLine ($img, $startX, $startY, $endX, $endY, $colour, $thickness) {
  $angle = (atan2(($startY - $endY), ($endX - $startX)));

  $dist_x = $thickness * (sin($angle));
  $dist_y = $thickness * (cos($angle));

  $p1x = ceil(($startX + $dist_x));
  $p1y = ceil(($startY + $dist_y));
  $p2x = ceil(($endX + $dist_x));
  $p2y = ceil(($endY + $dist_y));
  $p3x = ceil(($endX - $dist_x));
  $p3y = ceil(($endY - $dist_y));
  $p4x = ceil(($startX - $dist_x));
  $p4y = ceil(($startY - $dist_y));

  $array = array(0=>$p1x, $p1y, $p2x, $p2y, $p3x, $p3y, $p4x, $p4y);
  imagefilledpolygon($img, $array, (count($array)/2), $colour);
}

function colorToRGB($hex){
   $hex      = str_replace('#', '', $hex);
   $length   = strlen($hex);
   $rgb['0'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
   $rgb['1'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
   $rgb['2'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
   return $rgb;
}