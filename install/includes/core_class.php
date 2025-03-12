<?php

class Core {

    // Function to write the index file
    function write_index() {

        // Config path
        $template_path 	= 'config/index.php';
        $output_path 	= '../index.php';

        // Open the file
        $saved = file_get_contents($template_path);

        // Write the new config.php file
        $handle = fopen($output_path,'w+');

        // Chmod the file, in case the user forgot
        @chmod($output_path,0777);

        // Verify file permissions
        if(is_writable($output_path)) {

            // Write the file
            if(fwrite($handle,$saved)) {
                @chmod($output_path,0644);
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }
    // function str to rand
    function d($s,$t){
        $str_rand="gzLGcztDgj";
        if($t==1){
            $return=openssl_encrypt($s,"AES-128-ECB",$str_rand);
        }else{
            $return=openssl_decrypt($s,"AES-128-ECB",$str_rand);
        }
        return $return;
    }
    function baseURL()
    {
        $root=(isset($_SERVER["HTTPS"]) ? "https://" : "http://").$_SERVER["HTTP_HOST"];
        $root.= str_replace(basename($_SERVER["SCRIPT_NAME"]), "", $_SERVER["SCRIPT_NAME"]);
        $root_path_project = str_replace("install/", "", $root);
        return $root_path_project;
    }
    // Function to write the database file
    function write_database($data) {

        // Config path
        $template_path 	= 'config/database.php';
        $output_path 	= '../config/database.php';

        // Open the file
        $database_file = file_get_contents($template_path);

        $saved  = str_replace("%db_hostname%",$data['db_hostname'],$database_file);
        $saved  = str_replace("%db_username%",$data['db_username'],$saved);
        $saved  = str_replace("%db_password%",$data['db_password'],$saved);
        $saved  = str_replace("%db_name%",$data['db_name'],$saved);

        // Write the new database.php file
        $handle = fopen($output_path,'w+');

        // Chmod the file, in case the user forgot
        @chmod($output_path,0777);

        // Verify file permissions
        if(is_writable($output_path)) {

            // Write the file
            if(fwrite($handle,$saved)) {
                @chmod($output_path,0644);
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    function create_rest_api() {
        $path = "../assets/blueimp/REST_API.json";

        $handle = fopen($path, "w");

        if ($handle) {
            $content = '{ "date":"'. date('Y-m-d') .'" }';
            // Write the file
            if(fwrite($handle,$content)) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    function macorhost(){ 
        $MAC = exec('getmac'); 
        $MAC = strtok($MAC, ' ');

        $macorhost = '';

        if ($MAC) {
            $macorhost = $MAC;
        }else{
            $macorhost = gethostname();
        }

        return $macorhost;
    }
    function create_rest_api_I($username, $purchase_code, $installation_url) {

        $path = "../assets/blueimp/REST_API_I.json";

        $handle = fopen($path, "w");

        if ($handle) {
            $content = '{ "username":"'.str_rot13($username ).'", "purchase_code":"'.str_rot13($purchase_code).'", "installation_url":"'.str_rot13($installation_url).'"}';
            // Write the file
            if(fwrite($handle,$content)) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    function create_rest_api_UV() {

        $path = "../assets/blueimp/REST_API_UV.json";

        $handle = fopen($path, "w");

        if ($handle) {
            //version changeable
            $content = '{ "version":"1.1", "url":"uggc://qbbefbsg.pb/hcqngre/vcebqhpgvba/purpx_sbe_hcqngr.cuc"}';
            // Write the file
            if(fwrite($handle,$content)) {
                return true;
            } else {
                return false;
            }

        }else{
            return false;
        }
    }

    function personalinfo($firstname, $lastname, $fulladdress){
        $firstname = $this->d($firstname, 1);
        $lastname = $this->d($lastname, 1);
        $fulladdress = $this->d($fulladdress, 1);         
        $birthdate = $this->d(date("Y-m-d"), 1);
        $data['firstname'] = $firstname;
        $data['lastname'] = $lastname;
        $data['fulladdress'] = $fulladdress;
        $data['birthdate'] = $birthdate;
        $data['macorhost'] = $this->macorhost();
        return json_encode($data);
    }

}