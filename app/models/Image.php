<?php

/***
 * image cropper and resizer
 */

class Image {

    public function crop($scr_image_path, $dest_image_path, $max_size = 600) {
        
        if(file_exists($scr_image_path)) {

            $ext = strtolower(pathinfo($scr_image_path, PATHINFO_EXTENSION));       // getting the extention name

            // comparing different extention files
            if($ext == 'jpeg' || $ext == 'jpg') {
                $src_image = imagecreatefromjpeg($scr_image_path);
            } elseif($ext == 'png') {
                $src_image = imagecreatefrompng($scr_image_path);
            } elseif($ext == 'gif') {
                $src_image = imagecreatefromgif($scr_image_path);
            } else {
                $src_image = imagecreatefromjpeg($scr_image_path);
            }

            

            if($src_image) {
                // getting the height and with of the image
                $height = imagesy($src_image);
                $width = imagesx($src_image);

                if($width > $height) {
                    // checking when width of image is greater than the height, while extra spaces are also considered
                    $extra_space = $width - $height;
                    $src_x = $extra_space / 2;
                    $src_y = 0;                 // the cordinate point of y 

                    $src_w = $height;
                    $src_h = $height;

                } else {
                    $extra_space = $height - $width;
                    $src_y = $extra_space / 2;
                    $src_x = 0;

                    $src_w = $width;
                    $src_h = $width;
                }
                 
                // creating destination image as new image
                $dst_image = imagecreatetruecolor($max_size, $max_size);
                imagecopyresampled($dst_image, $src_image, 0, 0, $src_x, $src_y, $max_size, $max_size, $src_w, $src_h);


                // saving the image to a destination path as jpeg file
                imagejpeg($dst_image, $dest_image_path);
            }
            
        }        
    }


    public function profile_thumb($image_path) {

        $crop_size= 600;
        $ext = strtolower(pathinfo($image_path, PATHINFO_EXTENSION));
        $thumbnail = str_replace('.'.$ext, '_thumb.'.$ext, $image_path);

        if(!file_exists($thumbnail)) {
            $this->crop($image_path, $thumbnail, $crop_size);
        }

        return $thumbnail;
    }
}