<?php

namespace Marking\Controllers;


class Upload extends Base
{

    public function uploadFile()
    {
        $target_dir = "/var/www/marking/uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

        // Allow only C and CPP files
        if ($imageFileType != "cpp" && $imageFileType != "c") {
            return false;
            die("DEAD");
        }

        try {
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        } catch (\Exception $e) {
            var_dump($e);die();
        }

    }




}