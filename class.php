<?php
//    ________                          ___  ____           _
//   |_   __  |                        |_  ||_  _|         / |_
//   | |_ \_| _ .--.  .---.  _ .--.    | |_/ /    _ .--.`| |-'
//   |  _| _ [ `/'`\]/ /__\\[ `.-. |   |  __'.   [ `/'`\]| |
//  _| |__/ | | |    | \__., | | | |  _| |  \ \_  | |    | |,
// |________|[___]    '.__.'[___||__]|____||____|[___]   \__/

/**
 * Class Hava Durumu TR
 * @author Eren Kurt (ErenKrt)
 * @mail kurteren07@gmail.com
 * @date 17.07.2018
 * @version 1
 */


class epclass{

  public $baseurl="https://servis.mgm.gov.tr/api/";

  private function Curl( $url, $proxy = NULL ){
       $options = array (
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HEADER => false,
          CURLOPT_ENCODING => "",
          CURLOPT_REFERER => "https://servis.mgm.gov.tr/",
          CURLOPT_CONNECTTIMEOUT => 30,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_FOLLOWLOCATION=>true,
          CURLOPT_ENCODING=>"UTF-8"
       );
       $ch = curl_init( $url );
       curl_setopt_array( $ch, $options );
       $content = curl_exec( $ch );
       $err = curl_errno( $ch );
       $errmsg = curl_error( $ch );
       $header = curl_getinfo( $ch );
       curl_close( $ch );
       $header[ 'errno' ] = $err;
       $header[ 'errmsg' ] = $errmsg;
       $header[ 'content' ] = $content;
       return str_replace( array ( "\n", "\r", "\t" ), NULL, $header[ 'content' ] );
   }

       function sksort(&$array, $subkey="id", $sort_ascending=false) {

        if (count($array))
            $temp_array[key($array)] = array_shift($array);

          foreach($array as $key => $val){
              $offset = 0;
              $found = false;
              foreach($temp_array as $tmp_key => $tmp_val)
              {
                  if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
                  {
                      $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                                                  array($key => $val),
                                                  array_slice($temp_array,$offset)
                                                );
                      $found = true;
                  }
                  $offset++;
              }
              if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
          }

          if ($sort_ascending) $array = array_reverse($temp_array);

          else $array = $temp_array;
      }

      function il($il){
        $il = trim($il);
        $il = str_replace(array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü',' '),array('c','c','g','g','i','i','o','o','s','s','u','u','-'),$il);
        $bot= $this->curl($this->baseurl."merkezler/ililcesi?il=".$il);
        $json= json_decode($bot,true);
        $id= $json[0]["gunlukTahminIstNo"];
        $veri= $this->gunluk($id);
        return $veri;
      }

      function ililce($il="Ankara",$ilce="Yenimahalle"){
        if($il){
          $il="Ankara";
        }
        if($ilce==""){
          $ilce="Yenimahalle";
        }
        $ilceler= $this->ilceler($il);


        $bul= array_search($ilce,array_column($ilceler, 0));

          if(count($ilceler[$bul])){
            return $this->gunluk($ilceler[$bul][1]);
          }

      }

     function iller(){
       $bot= $this->curl($this->baseurl."merkezler/iller");
       $json= json_decode($bot,true);
       $this->sksort($json,"ilPlaka",true);
       $iller= array();
       for($i=0; $i<count($json); $i++){
         array_push($iller,array($json[$i]["gunlukTahminIstNo"],$json[$i]["il"]));
       }

      return $iller;
     }

   function ilceler($il){
     $il = trim($il);
     $il = str_replace(array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü',' '),array('c','c','g','g','i','i','o','o','s','s','u','u','-'),$il);
     $bot= $this->curl($this->baseurl."merkezler/ililcesi?il=".$il);
     $json= json_decode($bot,true);
     $this->sksort($json,"gunlukTahminIstNo",true);
     $ilceler= array();
     for($i=0; $i<count($json); $i++){
       array_push($ilceler,array($json[$i]["ilce"],$json[$i]["merkezId"]));
     }

     return $ilceler;
   }

   function gunluk($id){
     if(ctype_digit($id)){
     $bot= $this->curl($this->baseurl."tahminler/gunluk?istno=".$id);
     $json= json_decode($bot,true);
     $json= $json[0];

     $durum= array();

     for($i=1; $i<=5; $i++){
       $tarih= $json["tarihGun".$i];
       $tarih= explode("T",$tarih);
       $tarih= $tarih[0];

       $gun= iconv("ISO-8859-9","UTF-8",strftime("%A", strtotime($tarih)));


       $ary= array(
         "gun"=>$gun,
         "tarih"=>$tarih,
         "sicaklik"=>array(
           "yuksek"=>$json["enYuksekGun".$i],
           "dusuk"=>$json["enDusukGun".$i]
         ),
         "nem"=>array(
           "yuksek"=>$json["enYuksekNemGun".$i],
           "dusuk"=>$json["enDusukNemGun".$i]
         ),
         "ruzgar"=>array(
           "hiz"=>$json["ruzgarHizGun".$i],
           "yon"=>$json["ruzgarYonGun".$i]
         ),

         "hadise"=>$json["hadiseGun".$i]
       );

       array_push($durum,$ary);

     }

      return $durum;

    }
   }



}
