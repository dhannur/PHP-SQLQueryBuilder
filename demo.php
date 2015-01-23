<?php

include ("SQLQueryBuilder.php");

$hostname = "localhost";
$username = "root";
$password = "";
$database = "demo";	

$link = mysqli_connect($hostname, $username, $password, $database);
if(!$link)
{
	Exit(0);
}

// insert example 
try{
	$data = array(
		"username" => date("ymdhis"),
		"password" => md5(date("ymdhis"))
	);
	$gen = new SQLGenerator();
	$sql = $gen->generateInsert("users", $data);
	echo $sql . "<br /><br />\n";
	mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

/*
// insert example 
try{
	$data = array(
		"username" => "admin",
		"password" => md5("admin")
	);
	$gen = new SQLGenerator();
	$sql = $gen->generateInsert("users", $data);
	echo $sql . "<br /><br />\n";
	mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

// update example 
try{
	$data = array(
		"username" => "admin",
		"password" => md5("admin")
	);
	$gen = new SQLGenerator();
	$gen->where("users.user_id", "=", 1);
	$sql = $gen->generateUpdate("users", $data);
	echo $sql . "<br /><br />\n";
	// mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

// delete example 
try{
	$gen = new SQLGenerator();
	$gen->where("users.user_id", "=", 1);
	$sql = $gen->generateDelete("users", array(
		"username" => "admin",
		"password" => md5("admin")
	));
	echo $sql . "<br /><br />\n";
	// mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

// select example 
try{
	$gen = new SQLGenerator();
	$gen->join("user_detail", "user_detail.username = users.username");
	$gen->where("user_detail.gender", "like", "Male");
	$gen->orWhere("user_detail.gender", "like", "Man");
	$sql = $gen->generateSelect("users", "users.username");
	echo $sql . "<br /><br />\n";
	// mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

// select count example
try{
	$gen = new SQLGenerator();
	$gen->join("user_detail", "user_detail.username = users.username");
	$gen->where("user_detail.gender", "like", "Male");
	$gen->orWhere("user_detail.gender", "like", "Man");
	$sql = $gen->generateSelectCount("users");
	echo $sql . "<br /><br />\n";
	// mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

// select max example
try{
	$gen = new SQLGenerator();
	$gen->join("user_detail", "user_detail.username = users.username");
	$gen->where("user_detail.gender", "like", "Male");
	$gen->orWhere("user_detail.gender", "like", "Man");
	$sql = $gen->generateSelectMax("users", "user_detail.age", "oldest");
	echo $sql . "<br /><br />\n";
	// mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

// select min example
try{
	$gen = new SQLGenerator();
	$gen->join("user_detail", "user_detail.username = users.username");
	$gen->where("user_detail.gender", "like", "Male");
	$gen->orWhere("user_detail.gender", "like", "Man");
	$sql = $gen->generateSelectMin("users", "user_detail.age", "youngest");
	echo $sql . "<br /><br />\n";
	// mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

// select sum example
try{
	$gen = new SQLGenerator();
	$gen->join("user_detail", "user_detail.username = users.username");
	$gen->where("user_detail.gender", "like", "Male");
	$gen->orWhere("user_detail.gender", "like", "Man");
	$sql = $gen->generateSelectSum("users", "user_detail.value", "val_sum");
	echo $sql . "<br /><br />\n";
	// mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
}

// select avg example
try{
	$gen = new SQLGenerator();
	$gen->join("user_detail", "user_detail.username = users.username");
	$gen->where("user_detail.gender", "like", "Male");
	$gen->orWhere("user_detail.gender", "like", "Man");
	$sql = $gen->generateSelectAvg("users", "user_detail.age", "age_avg");
	echo $sql . "<br /><br />\n";
	// mysqli_query($link, $sql);
}catch(Exception $ex){
	echo $ex->getMessage() . "<br /><br />\n";
} 
*/

mysqli_close($link);