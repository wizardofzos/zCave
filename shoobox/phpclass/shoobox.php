<?


class hkMySqlConnector{
    public $host    = "Initial Host";
    public $userID;
    public $pass;
    public $database;
		
		public $connection;
    
	
    function init($config){
		
      foreach ($config as $option => $setting){
        switch($option){
            case "host"     : $this->host      = $setting;break;
            case "user"     : $this->userID    = $setting;break;
            case "pass"     : $this->pass      = $setting;break;
            case "database" : $this->database  = $setting;break;
        }
       }
			 
			 $this->connection = mysql_connect($this->host,
							 										       $this->userID,
																	       $this->pass) 
						  or die("hkMySqlConnector : Could not open MySql connection, this is generally bad");
			 
			 mysql_select_db($this->database,$this->connection);														 
    }
    
    function execute($sql){
			return mysql_query($sql,$this->connection) or die("hkMySqlConnector : Cannot execute query!");
		}
    
}


class hkNumberBag extends hkMySqlConnector{
		
		public function get($somevalue){
			// Dangerous Function :)
			// If you GET some value, you will increase it's number by one too 
			$sql = "select number from numberbag where id='$somevalue'";
			$res = mysql_query($sql);
			$row = mysql_fetch_row($res);
			
			$prev = $row[0];
			$row[0]++;
			
			$sql = "update numberbag set number = '$row[0]' where id ='$somevalue'";
			$res = mysql_query($sql,$this->connection);
			
			return $prev;
		}
}

function llq($path){


	
	$nameparts = pathinfo($path);
	$filetype  = $nameparts['extension'];
	
	return strtolower($filetype); 
	
	}
  
  
  
  class hkShooboxManager{
  public $shoobox;
  public $stage;
  public $thumbdir;
  public $numberbag;
  public $valids;
  
  function init($config){
    echo "Setting up";
    foreach ($config as $option => $setting){
      switch($option){
        case "shoobox"      : $this->shoobox  = $setting;break;
        case "stage"        : $this->stage    = $setting;break;
        case "thumbdir"     : $this->thumbdir = $setting;break;
        case "valids"       : $this->valids   = explode(",",$setting);break;
      }
    }
  }
  
  function manageStage($number) {
    $filelist = array();
    
    if (is_dir($this->stage)) {
      if ($dh = opendir($this->stage)) {
        while (($file = readdir($dh)) !== false) {
          // "Generate" our document number
          if($file != "." & $file != ".."){
            $newNumber = $number->get('documenten');
            // Copy the stage file to shoobox
            $newfile = $this->shoobox . $newNumber . "." . llq($this->stage . $file);
            $copycommand = "cp '" . $this->stage . $file . "' " . $this->shoobox . $newNumber . "." . llq($this->stage . $file);
            system($copycommand);
            array_push($filelist, $this->shoobox . $newNumber . "." . llq($this->stage . $file));
          }
          
          
            
          // If it's a PDF we need this stuff for the thumbnail/jpg creation
          if(llq($this->stage . $file) == 'pdf'){
            
            // Convert PDF to JPG (truesize)
            $convert = "convert '" . $this->stage . $file . "[0]' " . $this->shoobox . $newNumber . ".jpg";
            system($convert);
              
            // Create the thumbnail
            $thumbnail = "convert " . $this->shoobox . $newNumber . ".jpg -auto-orient -thumbnail 300x300 " . $this->thumbdir . $newNumber . ".jpg";
            system($thumbnail);
          }
            
          // Otherwise it *should* be an image file in a format 
          // convert can handle. Accept png, jpg, bmp, jpeg
          if(in_array(llq($this->stage . $file),$this->valids)){
            
            // Create the thumbnail
            $convert = "convert '" . $this->stage . $file . "' -auto-orient thumbnail 300x300 " . $this->thumbdir . $newNumber . ".jpg"; 
            system($convert);
          }
            
          // Remove the stage file
          // todo!
            
            
            
        }
        closedir($dh);
    }

  }
    return $filelist;
  }
  
  function updateDatabase($db,$files){
    $connection = $db->connection;
    foreach($files as $file){
      $sql = "insert into items (id,box,value,tax,type,description,documentname,date) values('',0,0,0,'','','$file','')";
      echo $sql;
      $res = mysql_query($sql,$connection);
    }
    
  }

}
?>