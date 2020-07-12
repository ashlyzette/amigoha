<?php 
    include("includes/standards/upload_header.php");
    $profile_id = $user['username'];
    $header = 
    $imgSrc = "";
    $result_path = "";
    $msg = "";
    $msg_banner="";

    if (isset($_SESSION['username'])){
        $profile = new User($con,$user_log);
        $header_image = $profile->getHeaderImage();
    }

    //Upload header image profile
    if (isset($_FILES['banner']['name'])){
        // $profile->UploadHeaderImage($_POST['header_image']);
        $ImageName = $_FILES['banner']['name'];
        $ImageSize = $_FILES['banner']['size'];
        $ImageTempName = $_FILES['banner']['tmp_name'];
        //Get File Ext   
        $ImageType = @explode('/', $_FILES['banner']['type']);
        if ($ImageType[0]){
            $type = $ImageType[1]; //file type	
            if ($type=='gif' || $type=='jpg' || $type=='jpeg' || $type=='png'){
                //Set Upload directory    
                $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/assets/images/banners';
                //Set File name	
                $file_temp_name = $profile_id.'_original.'.md5(time()).'n'.$type; //the temp file name
                $fullpath = $uploaddir."/".$file_temp_name; // the temp file path
                $file_name = $profile_id.'_temp.jpeg'; //$profile_id.'_temp.'.$type; // for the final resized image
                $fullpath_2 = $uploaddir."/".$file_name; //for the final resized image
                //Move the file to correct location
                $move = move_uploaded_file($ImageTempName ,$fullpath) ; 
                chmod($fullpath, 0777);  
                //Check for valid uplaod
                if (!$move) { 
                    die ('File didnt upload');
                } else { 
                    $imgSrc= "assets/images/banners/".$file_name; // the image to display in crop area
                    $msg= "Upload Complete!";  	//message to page
                    $src = $file_name;	 		//the file name to post from cropping form to the resize		
                } 
                //upload new version
                $main_temp = $fullpath_2;
                imagejpeg($main, $main_temp, 90);
                chmod($main_temp,0777);
                //free up memory
                imagedestroy($src2);
                imagedestroy($main);
                //imagedestroy($fullpath);
                @ unlink($fullpath); // delete the original upload		
            } else {
                $msg_banner= "Invalid file, please use .jpg, .gif or .png file only.";
            }	
        }
    }

    /***********************************************************
        0 - Remove The Temp image if it exists
    ***********************************************************/
        if (!isset($_POST['x']) && !isset($_FILES['image']['name']) ){
            //Delete users temp image
                $temppath = 'assets/images/profile_pics/'.$profile_id.'_temp.jpeg';
                if (file_exists ($temppath)){ @unlink($temppath); }
        } 


    if(isset($_FILES['image']['name'])){	
    /***********************************************************
        1 - Upload Original Image To Server
    ***********************************************************/	
        //Get Name | Size | Temp Location		    
            $ImageName = $_FILES['image']['name'];
            $ImageSize = $_FILES['image']['size'];
            $ImageTempName = $_FILES['image']['tmp_name'];
        //Get File Ext   
            $ImageType = @explode('/', $_FILES['image']['type']);
            if ($ImageType[0]){
                $type = $ImageType[1]; //file type	
                if ($type=='gif' || $type=='jpg' || $type=='jpeg' || $type=='png'){
                    //Set Upload directory    
                    $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/assets/images/profile_pics';
                    //Set File name	
                    $file_temp_name = $profile_id.'_original.'.md5(time()).'n'.$type; //the temp file name
                    $fullpath = $uploaddir."/".$file_temp_name; // the temp file path
                    $file_name = $profile_id.'_temp.jpeg'; //$profile_id.'_temp.'.$type; // for the final resized image
                    $fullpath_2 = $uploaddir."/".$file_name; //for the final resized image
                    //Move the file to correct location
                    $move = move_uploaded_file($ImageTempName ,$fullpath) ; 
                    chmod($fullpath, 0777);  
                    //Check for valid uplaod
                    if (!$move) { 
                        die ('File didnt upload');
                    } else { 
                        $imgSrc= "assets/images/profile_pics/".$file_name; // the image to display in crop area
                        $msg= "Upload Complete!";  	//message to page
                        $src = $file_name;	 		//the file name to post from cropping form to the resize		
                    } 

                    /***********************************************************
                        2  - Resize The Image To Fit In Cropping Area
                    ***********************************************************/		
                        //get the uploaded image size	
                        clearstatcache();				
                        $original_size = getimagesize($fullpath);
                        $original_width = $original_size[0];
                        $original_height = $original_size[1];	
                        // Specify The new size
                        $main_width = 500; // set the width of the image
                        $main_height = $original_height / ($original_width / $main_width);	// this sets the height in ratio									
                        //create new image using correct php func			
                        if($_FILES["image"]["type"] == "image/gif"){
                            $src2 = imagecreatefromgif($fullpath);
                        }elseif($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/pjpeg"){
                            $src2 = imagecreatefromjpeg($fullpath);
                        }elseif($_FILES["image"]["type"] == "image/png"){ 
                            $src2 = imagecreatefrompng($fullpath);
                        }else{ 
                            $msg .= "There was an error uploading the file. Please upload a .jpg, .gif or .png file. <br />";
                        }
                        //create the new resized image
                        $main = imagecreatetruecolor($main_width,$main_height);
                        imagecopyresampled($main,$src2,0, 0, 0, 0,$main_width,$main_height,$original_width,$original_height);
                        //upload new version
                        $main_temp = $fullpath_2;
                        imagejpeg($main, $main_temp, 90);
                        chmod($main_temp,0777);
                        //free up memory
                        imagedestroy($src2);
                        imagedestroy($main);
                        //imagedestroy($fullpath);
                        @ unlink($fullpath); // delete the original upload		
                } else {
                    $msg= "Invalid file, please use .jpg, .gif or .png file only.";
                }			
            }  else {
                $msg = "You have not selected an image profile, please select a new file";
            }                      
    }//ADD Image 	

    /***********************************************************
        3- Cropping & Converting The Image To Jpg
    ***********************************************************/
    if (isset($_POST['x'])){
        
        //the file type posted
        $type = $_POST['type'];	
        //the image src
        $src = 'assets/images/profile_pics/'.$_POST['src'];	
        $finalname = $profile_id.md5(time());	
 
        if($type == 'jpg' || $type == 'jpeg' || $type == 'JPG' || $type == 'JPEG'){	
        
            //the target dimensions 150x150
                $targ_w = $targ_h = 150;
            //quality of the output
                $jpeg_quality = 90;
            //create a cropped copy of the image
                $img_r = imagecreatefromjpeg($src);
                $dst_r = imagecreatetruecolor( $targ_w, $targ_h );
                imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
                $targ_w,$targ_h,$_POST['w'],$_POST['h']);
            //save the new cropped version
                imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 	
                        
        }else if($type == 'png' || $type == 'PNG'){
            
            //the target dimensions 150x150
                $targ_w = $targ_h = 150;
            //quality of the output
                $jpeg_quality = 90;
            //create a cropped copy of the image
                $img_r = imagecreatefrompng($src);
                $dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
                imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
                $targ_w,$targ_h,$_POST['w'],$_POST['h']);
            //save the new cropped version
                imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 	
                            
        }else if($type == 'gif' || $type == 'GIF'){
            
            //the target dimensions 150x150
                $targ_w = $targ_h = 150;
            //quality of the output
                $jpeg_quality = 90;
            //create a cropped copy of the image
                $img_r = imagecreatefromgif($src);
                $dst_r = imagecreatetruecolor( $targ_w, $targ_h );		
                imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
                $targ_w,$targ_h,$_POST['w'],$_POST['h']);
            //save the new cropped version
                imagejpeg($dst_r, "assets/images/profile_pics/".$finalname."n.jpeg", 90); 	
        
        } 
            //free up memory
                imagedestroy($img_r); // free up memory
                imagedestroy($dst_r); //free up memory
                @ unlink($src); // delete the original upload					
            
            //return cropped image to page	
            $result_path ="assets/images/profile_pics/".$finalname."n.jpeg";

            //Insert image into database
            $insert_pic_query = mysqli_query($con, "UPDATE amigo SET profile_pic='$result_path' WHERE username='$user_log'");
            header("Location: ".$user_log);
                                                            
    }// post x

    if (isset($_POST['submit_header'])){
        $upload_ok =1;
        $image_name = $_FILES['header_image']['name'];

        if ($image_name !=""){
            $image_type = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_type = strtolower($image_type);
            $target_dir = "assets/images/banners/";
            $image_dir = $target_dir . uniqid() . basename($image_name);

            //check file size
            if ($_FILES['header_image']['size']>500000){
                $error_message = "File is too large, select file below 500KB";
                $upload_ok = 0;
            }
           
            if ($image_type!="jpeg" && $image_type!="png" && $image_type!="gif" && $image_type!="jpg"){
                $error_message = "Invalid file type, please upload jpeg, jpg, png or gif file!";
                $upload_ok = 0;
            } 

            if ($upload_ok===1){
                if (move_uploaded_file($_FILES['header_image']['tmp_name'],$image_dir)){
                    
                } else {
                    $upload_ok = 0;
                }
            }

            if ($upload_ok===1){
                $image_upload = mysqli_query($con, "UPDATE amigo SET header_img = '$image_dir' WHERE username = '$user_log'");
                $upload = new POST($con,$user_log);
                $body = "I changed my new header, check out my profile page.";
                $upload->submitPost($body, $user_log);
                $profile = new User($con,$user_log);
                $header_image = $profile->getHeaderImage();
            }
        }
    }
?>

<div id="Overlay" style=" width:100%; height:100%; border:0px #990000 solid; position:absolute; top:0px; left:0px; z-index:2000; display:none;">
</div>
<div class="upload_column column">
	<div class = "col-10">
        <div class = "header_image_tab">
            <form action="upload.php" method="post"  enctype="multipart/form-data">
                <div class="form-group">
                    <div class="mb-2"><?=$msg_banner?></div>
                    <label for="upload_header_image">Upload header image (Recommended height is 30% of the image width)</label>
                    <img class = "header_image" src = " <?php echo $header_image; ?> ">
                    <input class="mt-1 form-control-file" type="file" name="header_image" id="banner">
                    <div class = "mt-2"><input class ="btn btn-primary btn-sm" type="submit" name="submit_header" value="Save New Header Image"></div>
                </div>
            </form>
        </div>
        <div class = "profile_image_tab">
            <div class="mb-5"><?=$msg?></div>
            <form action="" method="post"  enctype="multipart/form-data">
                <label for="upload_profile_image">Upload new profile image</label>
                <div><img class = "profile_image" src = " <?php echo $profile->getProfilePic(); ?> "></div>
                <input class ="col-12 mt-2 px-0 btn btn-sm" type="file" id="upload_profile_image" name="image">
                <input class ="btn mt-2 btn-primary btn-sm" type="submit" value="Submit and Crop">
            </form><br /><br />
        </div>
	</div>
    <?php
    if($imgSrc){ //if an image has been uploaded display cropping area?>
	    <script>
	    	$('#Overlay').show();
			$('#formExample').hide();
	    </script>
	    <div class ="CropContainer" id="CroppingContainer">  
	        <div id="CroppingArea">	
	            <img src="<?=$imgSrc?>" border="0" id="jcrop_target" style="border:0px #990000 solid; position:relative; margin:0px 0px 0px 0px; padding:0px; " />
	        </div>  

	        <div id="InfoArea" style="width:180px; height:150px; position:relative; overflow:hidden; margin:40px 0px 0px 40px; border:0px #666 solid; float:left;">	
	           <p style="margin:0px; padding:0px; color:#444; font-size:18px;">          
	                <b>Crop Profile Image</b><br /><br />
	                <span style="font-size:14px;">
	                    Crop / resize your uploaded profile image.
	                </span>
	           </p>
	        </div>  

	        <div id="CropImageForm" style="height:100px; float:left; margin:10px 0px 0px 40px;" >  
	            <form class="form-inline" action="upload.php" method="post" onsubmit="return checkCoords();">
	                <input type="hidden" id="x" name="x" />
	                <input type="hidden" id="y" name="y" />
	                <input type="hidden" id="w" name="w" />
	                <input type="hidden" id="h" name="h" />
	                <input type="hidden" value="jpeg" name="type" /> <?php // $type ?> 
	                <input type="hidden" value="<?=$src?>" name="src" />
                    <input class="image_crop_button btn btn-primary btn-sm" type="submit" value="Save"/>
	            </form>
	        </div>
            <div id="CropImageForm2">  
                <form action="upload.php" method="post" onsubmit="return cancelCrop();">
                    <input class="image_crop_button btn btn-warning btn-sm" type="submit" value="Cancel Crop"/>
                </form>
            </div>      
	    </div><!-- CroppingContainer -->
	<?php 
	} ?>
</div>
 
 <?php if($result_path) {
	 ?>
     
     <img src="<?=$result_path?>" style="position:relative; margin:10px auto; width:150px; height:150px;" />
	 
 <?php 
 } 
 include("includes/standards/footer.php");
 ?>
