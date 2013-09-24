<?php
   function BingResults($queries, $done_file){
       require_once 'bcurl.php';
       $curl = new bcurl(0,0,0);
       $AppId = "***";
       require_once 'domain/connect.php';
       $connection = new Connect("***", "***", "***", "***");
       $domains = $connection->selectDomain("WhoisData", 0, 100);
       while($row = mysql_fetch_array($domains, MYSQL_ASSOC)){
					$domainList[$row['id']] = $row['domain'];
       }


       $products = $connection->selectProduct("Urun", 0, 100);
       while($rowa = mysql_fetch_array($products, MYSQL_ASSOC)){
           $productList[$rowa['UrunId']] = $rowa['Baslik'];
       }

       $services = $connection->selectHizmet("Hizmet", 0, 100);
       while($rowb = mysql_fetch_array($services, MYSQL_ASSOC)){
           $serviceList[$rowb['HizmetId']] = $rowb['Baslik'];
       }


       foreach ($queries as $domain => $value){

           $TargetDir = "/screens/sirketce/";
           $WithOutWww = str_replace("www.", "", $domain);
                   $IlkHarf = substr($WithOutWww, 0, 1);
                   $IkinciHarf = substr($WithOutWww, 1, 1);
                   if ( ($IkinciHarf=="-")  ||  ($IkinciHarf==".")  )
           $IkinciHarf = substr($WithOutWww, 2, 1);
                   $Md5Domain = md5($WithOutWww);

           $folder = $TargetDir.$IlkHarf."/".$IkinciHarf."/".$Md5Domain."/foto/";

           if(!is_dir($folder)) {  mkdir($folder); }

           $domainID = array_search($domain, $domainList);
//      touch($done_file) or die("hata");
       $handle = fopen($done_file, "w+");

           fwrite($handle, $domain."\n");

           foreach($value as $query){

               $String = urlencode('"'.$query.'"'.' site:'.$domain);
               $BingS = "http://api.bing.net/json.aspx?AppId={$AppId}&Version=2.2&Market=tr-TR&Sources=image&Image.Count=5&Query={$String}";
               //echo $BingS."<br />";

               $json = json_decode($curl->gotoUrl($BingS));
               $total = $json->SearchResponse->Image->Total;

               if($total > 0){

                  $indis = 1;
                  if(!is_dir($folder)){ mkdir($folder); }

                  $prodId = array_search($query, $productList);
                  $tur = "Urun";
                  $turid = $prodId;

                  if(empty($prodId)){
                          $serId = array_search($query, $serviceList);
                          $tur = "Hizmet";
                          $turid = $serId;
                  }

                  foreach ($json->SearchResponse-> Image -> Results as $image){
                      $hashString = md5($query.$domain);

                      $thumbName = "img_".$hashString."_kucuk_".$indis.".jpg";
                      //$orgName = "img_".$hashString."_".$indis.".jpg";

                      $thumbFile = $folder.$thumbName;
                      //$orgFile = $folder.$orgName;

                      $thumb = $curl->gotoUrl($image->Thumbnail->Url);
                      //$original = $curl->gotoUrl($image->MediaUrl);

                      $tF = fopen($thumbFile, "w");
                      //$oF = fopen($orgFile, "w");

                      fwrite($tF, $thumb);
                      //fwrite($oF, $original);

                      fclose($tF);
                      //fclose($oF);

                      //last check
                      $path = $folder;
                      $url = $image->MediaUrl;
                      echo $path.$thumbName."--------";

                      //(TurId, DomainId, Tur, BigNameUrl, ThumbName, BasePath, EklemeTarihi)
                      $connection -> insert_jSon($turid, $domainID, $tur, $url, $thumbName, $path, $indis);

                      $indis++;
                   }
               }
           }
       }
       @fclose($handle);
   }//finish

   function readDomains($filename, $begin, $count){
       //      echo $filename."---------"; die;
       //$begin--;
       //echo $begin."---".$count."<br />";
       $handle = fopen($filename, 'r');
       $sayi =  0;

       $line = 1;
       $i = 0;

       if($begin == "a") { $sayi = 21; }

       if($handle){

           if($count==0){
               while(!feof($handle)){
                   $string = fgets($handle, 1024);
                   if($string == $begin || $sayi == 21){
//echo $string."================";die;

                       $domains[] = trim($string);
                       $i++;
                       $sayi = 21;
                   }
                   $line++;
               }
// var_dump($domains);die;
           }

           else if($count>0){
               echo "count>0<br />";
               while(!feof($handle)){
                   $string = fgets($handle, 1024);
               echo $string."\n";
                 if($i == $count) { break 1; }
                   if($string == $begin || $sayi == 21 ){
                       echo "ilk begin: ".$begin."ilk sayi: ".$sayi;
                       $domains[] = trim($string);
                       $i++;
                       $sayi = 21;
                   }

                   $line++;
               }
           }
       }
       echo "\n\n\n\n\n\n\n";
//      var_dump($domains);

       @fclose($handle);
       return $domains;
   }


   //time to second
   function microtime_float(){
       list($usec, $sec) = explode(" ", microtime());
       return ((float)$usec + (float)$sec);
   }
?>
