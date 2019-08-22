<?php

class faImage {

	var $image;
	var $image_type;

	function hex2rgb($color) {
		$color = str_replace('#', '', $color);
		$s = strlen($color) / 3;
		$rgb[] = hexdec(str_repeat(substr($color, 0, $s), 2 / $s));
		$rgb[] = hexdec(str_repeat(substr($color, $s, $s), 2 / $s));
		$rgb[] = hexdec(str_repeat(substr($color, 2 * $s, $s), 2 / $s));
		return $rgb;
	}

	function create($width, $height) {
		if (function_exists('imagecreatetruecolor')) {
			$this->image = imagecreatetruecolor($width, $height);
		} elseif (function_exists('imagecreate')) {
			$this->image = imagecreate($width, $height);
		} else {
			raise_error('unable to create an image');
			exit;
		};
		imagealphablending($this->image, true);
		imagesavealpha($this->image, true);
		imagefill($this->image, 0, 0, imagecolorallocatealpha($this->image, 0, 0, 0, 127));
	}

	function load($filename) {
		$image_info = getimagesize($filename);
		$imageType = $image_info[2];
		if ($imageType == IMAGETYPE_JPEG) {
			$this->image_type = 'jpg';
			$this->image = imagecreatefromjpeg($filename);
		} elseif ($imageType == IMAGETYPE_GIF) {
			$this->image_type = 'gif';
			$this->image = imagecreatefromgif($filename);
		} elseif ($imageType == IMAGETYPE_PNG) {
			$this->image_type = 'png';
			$this->image = imagecreatefrompng($filename);
		}
	}

	function save($filename, $image_type = 'jpg', $image_quality = 75, $permissions = null) {
		if ($image_type == 'jpg') {
			imagejpeg($this->image, $filename, $image_quality);
		} elseif ($image_type == 'gif') {
			imagegif($this->image, $filename);
		} elseif ($image_type == 'png') {
			$compression = 9 - round(($image_quality / 100) * 9); // Scale quality from 0-100 to 9-0, 0 is best, 9 is poorest
			imagepng($this->image, $filename, $compression);
		};
		if ($permissions != null) {
			chmod($filename, $permissions);
		};
	}

	function output($image_type = 'jpg', $image_quality = 75) {
		if ($image_type == 'jpg') {
			header('Content-Type: image/jpeg');
			imagejpeg($this->image, null, $image_quality);
		} elseif ($image_type == 'gif') {
			header('Content-Type: image/gif');
			imagegif($this->image, null);
		} elseif ($image_type == 'png') {
			$compression = 9 - round(($image_quality / 100) * 9); // Scale quality from 0-100 to 9-0, 0 is best, 9 is poorest
			header('Content-Type: image/png');
			imagepng($this->image, null, $compression);
		};
		imagedestroy($this->image);
	}

	function get_width() {
		return imagesx($this->image);
	}

	function get_height() {
		return imagesy($this->image);
	}

	function resize_to_fit($width, $height) {
		$ratioX = $this->get_width() / $width;
		$ratioY = $this->get_height() / $height;
		if ($ratioX > $ratioY) {
			$this->resize_to_width($width);
		} else {
			$this->resize_to_height($height);
		};
	}

	function resize_to_height($height) {
		$ratio = $height / $this->get_height();
		$width = $this->get_width() * $ratio;
		$this->resize($width, $height);
	}

	function resize_to_width($width) {
		$ratio = $width / $this->get_width();
		$height = $this->get_height() * $ratio;
		$this->resize($width, $height);
	}

	function scale($scale) {
		$width = $this->get_width() * $scale / 100;
		$height = $this->get_height() * $scale / 100;
		$this->resize($width, $height);
	}

	function resize($width, $height) {
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->get_width(), $this->get_height());
		$this->image = $new_image;
	}

	/* graphics */

	function draw_line($positionX1, $positionY1, $positionX2, $positionY2, $color, $boldness = 2) {
		$center = round($boldness / 2);
		for ($i = 0; $i < $boldness; $i++) {
			$a = $center - $i;
			if ($a < 0) {
				$a -= $a;
			};
			for ($j = 0; $j < $boldness; $j++) {
				$b = $center - $j;
				if ($b < 0) {
					$b -= $b;
				};
				$c = sqrt($a * $a + $b * $b);
				if ($c <= $boldness) {
					imageline($this->image, $positionX1 + $i, $positionY1 + $j, $positionX2 + $i, $positionY2 + $j, $color);
				};
			};
		};
	}

	/*
	  alpha 0-127
	  0-100%
	  127-0%
	 */

	function allocate_color($color, $alpha) {
//		imagecolortransparent($this->image, imagecolorat($this->image, 0, 0));
		$color = imagecolorallocatealpha($this->image, $color[0], $color[1], $color[2], $alpha);
		return $color;
	}

	function draw_point($positionX, $positionY, $radius, $color) {
		imagefilledellipse($this->image, $positionX, $positionY, $radius, $radius, $color);
	}

	function draw_point_new($positionX, $positionY, $radius, $color, $alpha) {
		$pointColor = $this->allocate_color($this->hex2rgb($color), $alpha);
		imagefilledellipse($this->image, $positionX, $positionY, $radius, $radius, $pointColor);
	}

	function draw_point_gradient($positionX, $positionY, $radius, $rgb, $alphastart, $alphaend, $step = 0, $alpha = false) {
		list($r, $g, $b) = $this->hex2rgb($rgb);

		if ($alpha == false) {
			$a1 = $this->a2sevenbit($alphastart);
			$a2 = $this->a2sevenbit($alphaend);
		} else {
			$a1 = 120;
			$a2 = 127;
		}


		$line_numbers = $radius;
//		imagefill($this->image, 0, 0, imagecolorallocatealpha($this->image, $r, $g, $b, $a1));

		for ($i = 0; $i < $line_numbers; $i = $i + 1 + $step) {
			$old_a = ( empty($a) ) ? $a2 : $a;
			if ($a2 - $a1 != 0) {
				$a = intval($a1 + ( $a2 - $a1 ) * ( $i / $line_numbers ));
			} else {
				$a = $a1;
			};
			if ($old_a != $a) {
				$fill = imagecolorallocatealpha($this->image, $r, $g, $b, $a);
			};
			imagefilledellipse($this->image, $positionX, $positionY, $line_numbers - $i, $line_numbers - $i, $fill);
		};
		// exit;
	}

	function draw_text($positionX, $positionY, $text, $font, $color) {
		$fontHeight = imagefontheight($font);
		$fontWidth = imagefontwidth($font);
		if ($positionX == 'center' and $positionY == 'center') {
			$positionX = $this->get_width() / 2 - strlen($text) / 2 * $fontWidth;
			$positionY = $this->get_height() / 2 - 1 / 2 * $fontHeight;
		} else {

		};
		imagestring($this->image, $font, $positionX, $positionY, $text, $color);
	}

	function a2sevenbit($alpha) {
		return (abs($alpha - 255) >> 1);
	}

}
