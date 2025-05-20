<?php
function Upload_imagewith_thumb($image_nm, $image_path, $thumb_image_path) {
    

	$CI = & get_instance();
    $config['upload_path']   = $image_path; 
    $config['allowed_types'] = '*'; 
    $config['max_size']      = 5024;
    $config['encrypt_name'] = TRUE;
    $CI->load->library('upload', $config);
    if ( ! $CI->upload->do_upload($image_nm)) {
        $result = array('error' => $CI->upload->display_errors());        
    }else {
        $uploadedImage = $CI->upload->data();
        $image_path_nm= $image_path.$uploadedImage['file_name'];        
        resizeImage($uploadedImage['file_name'], $image_path_nm, $thumb_image_path);
        $original_nm=$uploadedImage['file_name'];
        $thumb_nm=$uploadedImage['raw_name']."_thumb".$uploadedImage['file_ext'];
        $result=array(
        	'original_nm'=>$original_nm,
        	'thumb_nm' => $thumb_nm
        );

    }
    return $result;
}



function resizeImage($filename, $origianl_path, $thumb_path) {
	$CI = & get_instance();
    $source_path = $origianl_path;
    $target_path = $thumb_path;
    $CI->load->library('image_lib');
    $config_manip = array(
        'image_library' => 'gd2',
        'source_image' => $source_path,
        'new_image' => $target_path,
        'maintain_ratio' => TRUE,
        'create_thumb' => TRUE,
        'thumb_marker' => '_thumb',
        'width' => 150,
        'height' => 150
    );

    $CI->image_lib->clear();
    $CI->image_lib->initialize($config_manip);
    $CI->image_lib->resize();    
}

function getGraphicsThumb($id)
{
    $ci = & get_instance();
    $graphicData = $ci->super_dbmodel->get_where_single_data('graphics', "*", array('id' => $id));
    if(!empty($graphicData)){
        return $graphicData->thumb;
    }
    else{
        return base_url('assets/images/slider_blank.png');
    }
}

?>