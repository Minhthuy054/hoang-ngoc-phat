<?php 

namespace WP_Smart_Image_Resize\Exceptions;

use Exception;

class Invalid_Image_Meta_Exception extends Exception{


  public function __construct()
  {
    parent::__construct('Invalid image meta');
  }
}