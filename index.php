<?PHP


if ( !isset($_POST['user'])) {
    echo "<!doctype html>
    <html lang=\"en\">
      <head>
        <meta charset=\"utf-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">
        <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65\" crossorigin=\"anonymous\">
    
        <title>Byebye Twitter, Hello Mastodon.</title>
      </head>
      <body>
    <div class=\"container\">
    <H1>Generate a image for Twitter so your followers know where you are.</H1>
    <H5>Save the resulting image and upload to your favorite social network. Depending on the settings used it should not be easy for IA to autodetect its contents.</H5>

    <FORM METHOD=\"POST\" ACTION=\"".$_SERVER['PHP_SELF']."\" TARGET=\"imagen\">
    <fieldset>
        <legend>Language:</legend>
        <SELECT NAME=\"lang\" class=\"form-select\">
                        <OPTION value=\"EN\">\xF0\x9F\x87\xAC\xF0\x9F\x87\xA7 English</OPTION>
                        <OPTION value=\"ES\">\xF0\x9F\x87\xAA\xF0\x9F\x87\xB8 Spanish</OPTION>
                    </SELECT>
    </fieldset>
    <fieldset>
        <legend>Your full mastodon user or address: </legend>
        <INPUT TYPE=\"TEXT\" NAME=\"user\" PLACEHOLDER=\"user@intance.com\" />
    </fieldset>
    <fieldset>
        <legend>Noise amount: </legend>
        <input type=\"range\" class=\"form-range\" min=\"0\" max=\"100\" step=\"5\" name=\"noise\">
    </fieldset>
    <fieldset class=\"mb-3\">
        <legend>Logos & Images</legend>
        <div class=\"form-check\">
          <input type=\"radio\" checked=\"true\" name=\"logos\" value=\"1\" class=\"form-check-input\" id=\"Radio1\">
          <label class=\"form-check-label\" for=\"Radio1\">Display all</label>
        </div>
        <div class=\"form-check\">
          <input type=\"radio\" name=\"logos\" value=\"2\" class=\"form-check-input\" id=\"Radio2\">
          <label class=\"form-check-label\" for=\"Radio2\">Hide Mastodon logo</label>
        </div>
        <div class=\"form-check\">
          <input type=\"radio\" name=\"logos\" value=\"3\" class=\"form-check-input\" id=\"Radio3\">
          <label class=\"form-check-label\" for=\"Radio3\">Hide Mastodon & Warning logo</label>
        </div>
      </fieldset>
    <fieldset>
        <INPUT TYPE=\"SUBMIT\" class=\"btn btn-primary\" VALUE=\"Send!\"><br/><br/>
    </fieldset>
    </FORM>
    <IFRAME STYLE=\"width: 100%; height: 400px; border: 0px;\" NAME=\"imagen\"></IFRAME>
    </div>
    </BODY></HTML>";

}
else {
    $cantidad = intval($_POST['noise']);
    $lang = $_POST['lang'];
    $cuenta = substr($_POST['user'],0,64);

    // Do not edit
    $x = 800;
    $y = 400;
    $im = imagecreatetruecolor($x,$y);
    imagefill($im, 0, 0, imagecolorallocate($im, 255, 255, 255));

    for($i = 0; $i < $x; $i++) {
        for($j = 0; $j < $y; $j++) {
                $color = imagecolorallocate($im, rand(100,255), rand(100,255), rand(100,255));
                imagesetpixel($im, $i, $j, $color);
        }
    }
    imagefilter($im, IMG_FILTER_GAUSSIAN_BLUR);

    for($i = 0; $i < $x; $i++) {
        for($j = 0; $j < $y; $j++) {
            if ( rand(1,100) >= 100-$cantidad ) {
                $color = imagecolorallocate($im, rand(100,255), rand(100,255), rand(100,255));
                imagesetpixel($im, $i, $j, $color);
            }
        }
    }
    
    if ( intval($_POST['logos']) <= 1 ) {
        $logo = imagecreatefrompng("logo.png");
        $logo = imagerotate($logo, rand(-21,21), imageColorAllocateAlpha($logo, 0, 0, 0, 127));
        imagecopyresampled($im, $logo, rand(18,22), rand(18,22), 0, 0, 155, 155, imagesx($logo), imagesy($logo));
    }

    if ( intval($_POST['logos']) <= 2 ) {    
        $warn = imagecreatefrompng("warning.png");
        $warn = imagerotate($warn, rand(-21,21), imageColorAllocateAlpha($warn, 0, 0, 0, 127));
        imagecopyresampled($im, $warn, rand(618,622), rand(18,22), 0, 0, 146, 138, imagesx($warn), imagesy($warn));
    }
    
    $color = imagecolorallocate($im, 30,30,30);
    
    imagettftext($im, rand(56,59), rand(-3,3), 220, 141, $color, "./ame.ttf", l8n("ADVERTENCIA", $lang));
    imagettftext($im, rand(20,22), rand(-1,1), rand(29,33), rand(195,200), $color, "./joker.ttf", l8n("Esta cuenta también está disponible en Mastodon.", $lang));
    imagettftext($im, rand(20,22), rand(-1,1), rand(29,33), rand(245,250), $color, "./tt0605m.ttf", l8n("Puedes encontrarme en:", $lang));
    imagettftext($im, rand(30,32), rand(-1,1), rand(29,33), rand(305,310), $color, "./stepes.ttf", $cuenta);
    imagettftext($im, rand(14,16), rand(-1,1), rand(29,33), rand(350,355), $color, "./joker.ttf", l8n("Mastodon es una red social federada, gratuita y de código abierto.", $lang));
    imagettftext($im, rand(14,16), rand(-1,1), rand(507,512), rand(390,395), $color, "./joker.ttf", l8n("Más en: JoinMastodon.org",$lang));
    
    for($i = 0; $i < $x; $i++) {
        for($j = 0; $j < $y; $j++) {
            if ( rand(1,100) >= (100-($cantidad/2)) ) {
                $color = imagecolorallocate($im, rand(100,255), rand(100,255), rand(100,255));
                imagesetpixel($im, $i, $j, $color);
            }
        }
    }
    
    $nuevo_y = rand(700,900);
    $nuevo_x = $nuevo_y * 2;
    
    $im2 = imagecreatetruecolor($nuevo_x,$nuevo_y);
    imagecopyresampled($im2, $im,0, 0, 0, 0, $nuevo_x, $nuevo_y, $x, $y );
    
    header('Content-Type: image/png');
    imagepng($im2);


}

function l8n($texto,$lang="ES") {
    $l8n['EN']['ADVERTENCIA'] = "WARNING";
    $l8n['EN']['Esta cuenta también está disponible en Mastodon.'] = "This account is also available in Mastodon.";
    $l8n['EN']['Puedes encontrarme en:'] = "You can find me at:";
    $l8n['EN']['Mastodon es una red social federada, gratuita y de código abierto.'] = "Mastodon is an open source, free and federated social network";
    $l8n['EN']['Más en: JoinMastodon.org'] = "Find more: JoinMastodon.org.";

    return isset($l8n[($lang)][($texto)])?$l8n[($lang)][($texto)]:$texto;
}