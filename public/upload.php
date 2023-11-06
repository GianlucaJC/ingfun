<?php
session_start();

header('Content-type:application/json;charset=utf-8');

try {
    if (
        !isset($_FILES['file']['error']) ||
        is_array($_FILES['file']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    switch ($_FILES['file']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    //$filepath = sprintf('files/%s_%s', uniqid(), $_FILES['file']['name']);

	$path_parts = pathinfo($_FILES["file"]["name"]);
	$extension = $path_parts['extension'];
	$filename=uniqid().".".$extension;

	$sub="allegati/curr";
	$from="0";$id_cand=0;
	if (isset($_POST['from'])) $from=$_POST['from'];
	if (isset($_POST['id_cand'])) $id_cand=$_POST['id_cand'];
	
	//Upload documenti:
	//$from=="2" -> da scheda candidato
	//$from=="doc" -> da archivi->Area documenti
	//$from=="cedolini" ->dalla dashboard->upload cedolini
	//$from="ditte' ->da view ditte
	//$from="sezionali' ->da view sezionali
	if ($from=="ditte") {
		//id_cand=id_ditte
		$sub="allegati/ditte/$id_cand";
		@mkdir($sub);
	}	

	if ($from=="sezionali") {
		$sub="allegati/sezionali";
		$filename=$id_cand.".".$extension;
		@mkdir($sub);
	}	
	if ($from=="preventivi_firmati") {
		//cancello i preventivi precedenti
		$path = 'allegati/preventivi_firmati/*';
		foreach (glob($path) as $filename) {
			$info=explode(".",$filename);
			$ff=$info[0];
			$ff=str_replace("allegati/preventivi_firmati/","",$ff);
			if ($ff==$id_cand) 
				@unlink($filename);
		}	
		$sub="allegati/preventivi_firmati";
		$filename=$id_cand.".".$extension;
		@mkdir($sub);
	}

	if ($from=="2" || $from=="doc") {
		$sub="allegati/doc/$id_cand";
		@mkdir($sub);
	}	
	if ($from=="cedolini") {
		$tipo_cedolino=$_POST['tipo_cedolino'];
		$periodo=$_POST['periodo'];
		$sub="allegati/cedolini/$tipo_cedolino";
		@mkdir($sub);
		$sub="allegati/cedolini/$tipo_cedolino/$periodo";
		@mkdir($sub);
		$filename="busta.pdf";

	}	
	
	if ($from=="sinistri") {
		$sub="dist/upload/sinistri/";
	}
	
	$filepath = "$sub/".$filename;
    if (!move_uploaded_file(
        $_FILES['file']['tmp_name'],
        $filepath
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    } else {
		if ($from=="sinistri") {
			copy ($filepath,$sub="dist/upload/sinistri/thumbnail/medium/$filename");
			copy ($filepath,$sub="dist/upload/sinistri/thumbnail/small/$filename");
		}
		
	}
	


	
    // All good, send the response
    echo json_encode([
        'status' => 'ok',
        'path' => $filepath,
		'filename' =>$filename,
		'from' =>$from
	]);

} catch (RuntimeException $e) {
	// Something went wrong, send the err message as JSON
	http_response_code(400);

	echo json_encode([
		'status' => 'error',
		'message' => $e->getMessage()
	]);
}