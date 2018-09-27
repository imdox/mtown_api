<?php  
error_reporting ( 0 );
include 'functionapi.php';

if (isset ($_GET ['tag'] ) && $_GET ['tag'] != '') {
	$tag = $_GET ['tag'];
	$functionClass = new FunctionHelper();
	if ($tag == 'login') {
		$mobile = $_GET ['mobile'];
		$password = $_GET ['password'];
		$functionClass->Login($tag,$mobile,$password);
	}else if ($tag == 'modellist') {
		$role_type = $_GET ['role_type'];
		$functionClass->getModelList($tag,$role_type);
	}else if ($tag == 'create_update_model'){
		$id = $_GET ['id'];
		$firstname = $_GET ['firstname'];
		$lastname = $_GET ['lastname'];
		$mobile = $_GET ['mobile'];
		$email = $_GET ['email'];
		$about_you = $_GET ['about_you'];
		$profile_image = $_GET ['profile_image'];
		$model_images = $_GET ['model_images'];
		$age = $_GET ['age'];
		$gender = $_GET ['gender'];
		$experience = $_GET ['experience'];
		$designation = $_GET ['designation'];
		$height = $_GET ['height'];
		$weight = $_GET ['weight'];
		$skin_color = $_GET ['skin_color'];
		$eye_color = $_GET ['eye_color'];
		$known_languages = $_GET ['known_languages'];
		$user_id = $_GET ['user_id'];
		$functionClass->createUpdateModel($tag,$id,$firstname,$lastname,$mobile,$email,$about_you,$profile_image,$model_images,$age,$gender,$experience,$designation,$height,$weight,$skin_color,$eye_color,$known_languages,$user_id);
	}else if ($tag == 'update_profile') {
		$email = $_GET ['email'];
		$firstname = $_GET ['firstname'];
		$lastname = $_GET ['lastname'];
		$user_id = $_GET ['user_id'];
		$functionClass->updateProfile($tag,$email,$firstname,$lastname,$user_id);
	}else if ($tag == 'model_details') {
		$id = $_GET ['id'];
		$functionClass->modelDetails($tag,$id);
	}else if ($tag == 'user_details') {
		$id = $_GET ['id'];
		$functionClass->userDetails($tag,$id);
	}else if ($tag == 'add_audition') {
	    $audition_title = $_GET ['audition_title'];
		$description = $_GET ['description'];
		$role_type = $_GET ['role_type'];
		$note = $_GET ['note'];
		$created_by = $_GET ['created_by'];
		$model_ids = $_GET ['model_ids'];
		$created_name= $_GET ['created_name'];
		$created_mobile= $_GET ['created_mobile'];
		$total_model= $_GET ['total_model'];
		$functionClass->addAuditions($tag,$audition_title,$description,$role_type,$note,$created_by,$created_name,$created_mobile,$model_ids,$total_model);
	}else if ($tag == 'audition_list') {
		$created_by_id = $_GET ['created_by_id'];
		$functionClass->getAuditionList($tag,$created_by_id);
	}else if ($tag == 'audition_details_list') {
		$model_id = $_GET ['model_id'];
		$created_by_id = $_GET ['created_by_id'];
		$functionClass->getAuditionDetailsList($tag,$model_id,$created_by_id);
	}else if ($tag == 'audition_details') {
		$id = $_GET ['id'];
		$functionClass->auditionDetails($tag,$id);
	}else if ($tag == 'update_model_status') {
		$id = $_GET ['id'];
		$status = $_GET ['status'];
		$functionClass->updateModelStatus($tag,$id,$status);
	}else if ($tag == 'search') {
		$search = $_GET ['search'];
		$role_type = $_GET ['role_type'];
		$functionClass->search($tag,$search,$role_type);
	}else if ($tag == 'update_audition_detail') {
		$audition_images = $_GET ['audition_images'];
		$audition_id = $_GET ['audition_id'];
		$model_id = $_GET ['model_id'];
		$confirmation = $_GET ['confirmation'];
		$comment = $_GET ['comment'];
		$functionClass->acceptAudition($tag,$audition_images,$audition_id,$model_id,$confirmation,$comment);
	}else if ($tag == 'reject_audition_detail') {
		$audition_id = $_GET ['audition_id'];
		$model_id = $_GET ['model_id'];
		$confirmation = $_GET ['confirmation'];
		$comment = $_GET ['comment'];
		$functionClass->rejectAudition($tag,$audition_id,$model_id,$confirmation,$comment);
	}
}
?>  