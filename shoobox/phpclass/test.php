<?

   
  $config1 = array(
                         "host" => "localhost",
                         "user" => "root",
                         "pass" => "blaat123",
                         "database" => "shoobox"
  );
    
  include "shoobox.php";
    
  $numberGenerator = new hkNumberBag();
  $numberGenerator->init($config1);
  
  $shooboxDB = new hkMySqlConnector();
  $shooboxDB->init($config1);
  
  echo "start";
  
  $config2 = array(
                        "shoobox" => "/home/henri/shoobox/",
                        "stage" => "/home/henri/shoobox/stage/",
                        "thumbdir" => "/home/henri/shoobox/thumbs/",
                        "valids" => "jpg,jpeg,bmp,png"
  );
  
  
  $manager = new hkShooboxManager();
  $manager->init($config2);
  
  $addedFiles = $manager->manageStage($numberGenerator);
  foreach($addedFiles as $file){
    echo "added : " . $file . "<br />";
  }
  echo "First Added Number : " . $firstAdded . "<br />";
  $manager->updateDatabase($shooboxDB,$addedFiles);
  
  
   
   
  
?>
