<?php $method = "";

$file = "json/products.json";
if ( isset($_GET["option"]) ){
	$method = $_GET["option"];
}
$json = json_decode(file_get_contents($file),true);


switch ($method){
	case "destroy":
		destroy($json,$file);
		break;
	case "update":
		update($json,$file);
		break;
	case "create":
		create($json,$file);
		break;
	default:
		print_r(file_get_contents($file));		
		break;
}


function create($json,$file){

	if ( isset($_POST["ProductBrand"]) ){

		$id = count($json)+1;

		$ProductPrice 	= (int)$_POST["ProductPrice"];
		$ProductBrand 	= $_POST["ProductBrand"];
		$ProductModel 	= $_POST["ProductModel"];
		$ProductYear 	= (int)$_POST["ProductYear"];
		$ProductColor 	= $_POST["ProductColor"];
		$ProductDeal 	= $_POST["ProductDeal"];

		$arr = array('id'=>$id,'ProductPrice'=>$ProductPrice, 'ProductBrand' => $ProductBrand, 'ProductModel' => $ProductModel, 'ProductYear' => $ProductYear, 'ProductColor' => $ProductColor, 'ProductDeal' => $ProductDeal);

		array_push($json, $arr);
		array_multisort($json);
		
		$njson = json_encode($json);
		file_put_contents($file, $njson);
	}

}

function update($json,$file){

	if ( isset($_POST["id"]) ){
		$id = (int)$_POST["id"];
		$ProductPrice 	= (int)$_POST["ProductPrice"];
		$ProductBrand 	= $_POST["ProductBrand"];
		$ProductModel 	= $_POST["ProductModel"];
		$ProductYear 	= (int)$_POST["ProductYear"];
		$ProductColor 	= $_POST["ProductColor"];
		$ProductDeal 	= $_POST["ProductDeal"];

		$arr = array('id'=>$id,'ProductPrice'=>$ProductPrice, 'ProductBrand' => $ProductBrand, 'ProductModel' => $ProductModel, 'ProductYear' => $ProductYear, 'ProductColor' => $ProductColor, 'ProductDeal' => $ProductDeal);
		$result = search($json, "id", $id, true);

		array_push($result, $arr);
		array_multisort($result);

		$njson = json_encode($result);
		file_put_contents($file, $njson);
	}

}

function destroy($json,$file){

	if ( isset($_POST["id"]) ){
		$id = $_POST["id"];
		$result = search($json, "id", $id, true);
		$njson = json_encode($result);

		file_put_contents($file, $njson);
	}
}

function search($array, $key, $value, $destroy = false)
{
    $results = array();

    if (is_array($array)) {
  if($destroy === false){
   if (isset($array[$key]) && $array[$key] == $value ) {           
    $results[] = $array;   
   }
  }else{
   if (isset($array[$key]) && $array[$key] != $value ) {           
    $results[] = $array;   
   }
  }
  
  
        foreach ($array as $subarray) {   
             $results = array_merge($results, search($subarray, $key, $value, $destroy));
   
        }
    }

    return $results;
}
?>