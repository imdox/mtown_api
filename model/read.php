<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/model.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$model = new Model($db);
 
// query products
$stmt = $model->read();
$num = $stmt->rowCount();
 
// check if more than 0 record found
if($num>0){
 
    // products array
    $models_arr=array();
    $models_arr["data"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $model_item=array(
            "id" => $id,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "mobile" => $mobile,
            "about_you" => $about_you,
            "profile_image" => $profile_image,
			"model_images" => $model_images,
            "age" => $age,
            "gender" => $gender,
            "experience" => $experience,
            "designation" => $designation,
            "height" => $height,
			"weight" => $weight,
            "skin_color" => $skin_color,
            "eye_color" => $eye_color,
            "known_languages" => $known_languages,
			"model_code" => $model_code
        );
 
        array_push($models_arr["data"], $model_item);
		$models_arr["status"] = "success";
		$models_arr["message"] = "Data Fetched!";
    }
 
    echo json_encode($models_arr);
}
 
else{
    echo json_encode(
        array("message" => "No models found.", "status" => "error")
    );
}
?>