<?php

class Renderer {
  const PARTIAL_PATH = 'partials/';

  private static function renderPartial($partial, $data) {
    if ($data) {
      extract($data);
    }

    include self::PARTIAL_PATH . $partial . '.php';
  }

  public static function page($page, $data = null) {
    self::head();
    self::renderPartial($page, $data);
    self::foot();
  }

  public static function head($data = null) {
    self::renderPartial('head', $data);
  }

  public static function foot($data = null) {
    self::renderPartial('foot', $data);
  }

  public static function error($data = null) {
    self::page('error', $data);
  }
}