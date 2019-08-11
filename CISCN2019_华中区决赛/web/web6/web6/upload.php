<?php

session_start();

class Picture {
    function __construct($tmpName)
    {
        $whitelist = [
            "image/jpeg" => "jpg",
            "image/svg+xml" => "svg"
        ];

        $this->tmpName = $tmpName;
        $this->mimeType = mime_content_type($tmpName);


        if (!array_key_exists($this->mimeType, $whitelist)) {
            $this->jsonencode = json_encode(array("error"=>"图片不符合要求格式"));
            exit();
        }
        
        $this->getPictureSize($this->tmpName, $this->mimeType);

        if ($this->width * $this->height > 1500 * 1500) {
            $this->jsonencode = json_encode(array("error"=>"图片过大"));
            exit();
        }

        $this->extension = "." . $whitelist[$this->mimeType];
        $this->fileName = md5(random_bytes(10));
        $this->sandbox = $filePath = "picture/" . session_id() . "/";
    }

    function getPictureSize($file, $mimeType) {
        if ($mimeType == "image/jpeg") {
            $size = getimagesize($file);
            $this->width = (int) $size[0];
            $this->height = (int) $size[1];
        } else {
            $xml = file_get_contents($file);
            $domcument = new DOMDocument();
            $domcument->loadXML($xml, LIBXML_NOENT | LIBXML_DTDLOAD);
            $img = simplexml_import_dom($domcument);
            $attrs = $img->attributes();
            $this->width = (int) $attrs->width;
            $this->height = (int) $attrs->height;
        }
    }
    
    function samplePicture() {
        $filePath = $this->sandbox . $this->fileName . $this->extension;
        $samplePath = $this->sandbox . $this->fileName . "_sample.jpg";
        exec('convert ' . $filePath . " -sample 50%x50% " . $samplePath);
        $jsonencode = json_encode(array("success"=>array("orgin"=>$filePath, "sample"=>$samplePath)));
        echo $jsonencode;
    }

    function __destruct(){
        if (!empty($this->jsonencode)){
            echo $this->jsonencode;
            return ;
        }

        if (!file_exists($this->sandbox)){
            mkdir($this->sandbox);
        }
        $fileDst = $this->sandbox . $this->fileName . $this->extension;
        move_uploaded_file($this->tmpName, $fileDst);
        $this->samplePicture();
    }

}

header('Content-Type:text/json;charset=utf-8');
new Picture($_FILES['picture']['tmp_name']);