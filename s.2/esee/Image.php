<?php
                        

namespace esee;

/**
 * Description of Image
 *
 * @author vench
 */
class Image {

	private $fileName;

        /**
         *
         * @var resource Resource open file
         */
	private $iSrc;

	private $width;

	private $height;

	private $hash = [];

	public function __construct($fileName) {
		$this->fileName = $fileName;
	}

        /**
         * 
         * @throws \Exception
         */
	public function open() {
		$info = getimagesize($this->fileName); 
		switch(isset($info[2]) ? $info[2] : 0) {
			case IMAGETYPE_PNG:
				 $this->iSrc = imagecreatefrompng($this->fileName);
				break;
			case IMAGETYPE_JPEG: 
			case IMAGETYPE_JPEG2000:
				$this->iSrc = imagecreatefromjpeg($this->fileName);
				break;
			default:
				throw new \Exception("Type image not found");
				break;
		} 

		$this->width = $info[0];
		$this->height = $info[1];
	}

	public function getWidth() {
		return $this->width;
	}

	public function getHeight() {
		return $this->height;
	}

        /**
         * 
         * @param int $x
         * @param int $y
         * @return int the index of the color.
         */
	public function getPixel($x, $y) {
		if(isset($this->hash[$x . '_' . $y])) {
			return $this->hash[$x . '_' . $y];
		}
		return $this->hash[$x . '_' . $y] = imagecolorat($this->iSrc, $x, $y);
	}

        /**
         * 
         * @param int $x
         * @param int $y
         * @return boolean
         */
	public function hasPixel($x, $y) {
		return $x >= 0 && $x <= $this->width && 
		       $y >= 0 && $y <= $this->height;
	}

        /**
         * 
         */
	public function close() {
		imageDestroy($this->iSrc);
		$this->iSrc = null;
	}
	
}
