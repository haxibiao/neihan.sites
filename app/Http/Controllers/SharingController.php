<?php

namespace App\Http\Controllers;

use QrCode;

class SharingController extends Controller {
	public function qrcode() {
		$url = request("url");
		return QrCode::encoding('UTF-8')->format('svg')->size(400)->generate($url);
	}
}
