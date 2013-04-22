<?php
class Graphics
{
  	public static function GetCharacterSize($font, $text, $size, $angle)
  	{
    	$bbox = imagettfbbox($size, 0, $font, $text);

    	$angle = $angle/ 180 * pi();
    	for ($i=0; $i<4; $i++)
    	{
    	  	$x = $bbox[$i * 2];
	      	$y = $bbox[$i * 2 + 1];
      		$bbox[$i * 2] = cos($angle) * $x - sin($angle) * $y;  // X
      		$bbox[$i * 2 + 1] = sin($angle) * $x + cos($angle) * $y; // Y
	    }
	    $bbox['left'] = 0- min($bbox[0],$bbox[2],$bbox[4],$bbox[6]);
	    $bbox['top'] = 0- min($bbox[1],$bbox[3],$bbox[5],$bbox[7]);
	    $bbox['width'] = max($bbox[0],$bbox[2],$bbox[4],$bbox[6]) -  min($bbox[0],$bbox[2],$bbox[4],$bbox[6]);
	    $bbox['height'] = max($bbox[1],$bbox[3],$bbox[5],$bbox[7]) - min($bbox[1],$bbox[3],$bbox[5],$bbox[7]);

	    return $bbox;
  	}
}
?>