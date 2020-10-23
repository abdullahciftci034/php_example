<?php
class commonMysql{
    function __construct($mysqlUser){
        require_once APP_FUNCS."config_funcs.php";
        require_once APP_FUNCS."error_funcs.php";   
        $this->mysql_return_pdo(APP_MYSQL_CONFIG,$mysqlUser);
    }
    
    
    public function mysql_return_pdo($path,$person){
        if($config_data=json_config_read($path)){
            $this->mysql_connecting_pdo($config_data->{$person});
        }    
    }
    
    public function mysql_connecting_pdo($array){
        if(empty($array->{"dbname"})){
            try{  
             
                $this->connPdo=new PDO("mysql:host=".$array->{"host"}.";",$array->{"user"},$array->{"password"});
            }catch(PDOException $e){
                error_registions("mysql_connect_problems.txt",$e->getMessage());
                return false;
            }
        }else{
            try{  
                $this->connPdo=new PDO("mysql:host=".$array->{"host"}.";dbname=".$array->{"dbname"}.";",$array->{"user"},$array->{"password"});       
            }catch(PDOException $e){
                error_registions("mysql_connect_problems.txt",$e->getMessage());
                return false;
            }
        }
       
    }
    #genel query
    public function query($data,$stetmant){
        $sorgu=$this->connPdo->prepare($stetmant);
        if($sorgu->execute($data)){
            if(!empty($data=$sorgu->fetchAll())){
                return $data;
            }
            return true;
        }
        return false;
    }


 


    #genel insert
    public function insert($data){
        //  print_r($data);
        $stetmant="INSERT INTO ".$this->table." (";
        $stetmant1="";
        $stetmant2="";
        $i=0;           
        foreach($data as $key=> $val){
            if($i==0){
                $stetmant1.=" $key ";
                $stetmant2 .=" :$key ";
                $i++;
            }else{
                $stetmant1.=", $key ";
                $stetmant2.=", :$key ";
            }   
        }
        $stetmant.= $stetmant1." ) values ( ".$stetmant2." ) ";
        return $this->query($data,$stetmant);
    }





    #genel and  update
    public function updateAnd($data,$select){
        $stetmant="UPDATE ".$this->table." SET ";
        $i=0;
        foreach ($data as $key => $val){
            if($i==0){
                $stetmant.= "$key =:$key";
                $i++;
            }else{
                $stetmant.= " , $key =:$key ";
            }
        }

        $stetmant .=" where ";
        $i=0;
        foreach ($select as $key => $val){
            if($i==0){
                $stetmant.= "$key =:$key";
                $i++;
            }else{
                $stetmant.= " and $key =:$key ";
            }    
        }
        $data=array_merge($select,$data);
        return $this->query($data,$stetmant);
    }
    #genel or update
    public function updateOr($data,$select){
        $stetmant="UPDATE ".$this->table." SET ";
        $i=0;
        foreach ($data as $key => $val){
            if($i==0){
                $stetmant.= "$key =:$key";
                $i++;
            }else{
                $stetmant.= " , $key =:$key ";
            }
        }

        $stetmant .=" where ";
        $i=0;
        foreach ($select as $key => $val){
            if($i==0){
                $stetmant.= "$key =:$key";
                $i++;
            }else{
                $stetmant.= " or $key =:$key ";
            }
        }
        $data=array_merge($select,$data);
        return $this->query($data,$stetmant);
    }





    #genel silme işlemi and ile silme
    public function deleteAnd($select){
        $stetmant="DELETE FROM ".$this->table." where ";
        $i=0;
        foreach ($select as $key => $val){
            if($i==0){
                $stetmant.= "$key =:$key";
                $i++;
            }else{
                $stetmant.= " and $key =:$key ";
            }
        }
        return $this->query($select,$stetmant);
    }
    #genel silme işlemi or ile silme
    public function deleteOr($select){
        $stetmant="DELETE FROM ".$this->table." where ";
        $i=0;
        foreach ($select as $key => $val){
            if($i==0){
                $stetmant.= "$key =:$key";
                $i++;
            }else{
                $stetmant.= " or $key =:$key ";
            }
        }
        return $this->query($select,$stetmant);
    }

    





    #bir nesnesyi eleyerek çağırma and ile
    public function getElementAndAll($data){
        $stetmant="SELECT * FROM ".$this->table." where ";
        $i=0;
        foreach ($data as $key => $val){
            if ($i==0){
            $stetmant.= " $key =:$key ";
                $i++;
            }else{
                $stetmant.= " and $key =:$key ";
            }
        }
        return $this->query($data,$stetmant);
    }
    #bir nesnesyi eleyerek çağırma or ile
    public function getElementOrAll($data){
        $stetmant="SELECT * FROM ".$this->table." where ";
        $i=0;
        foreach ($data as $key => $val){
            if ($i==0){
            $stetmant.= " $key =:$key ";
                $i++;
            }else{
                $stetmant.= " or $key =:$key ";
            }
        }
        return $this->query($data,$stetmant);
    }



    #bir nesnesyi ve nesne belli kısımlarını  getirir eleyerek çağırma and ile belirli sutünları çektik
    public function getElementAnd($data,$select){

        $stetmant="SELECT "; 
        $i=0;
        foreach ($data as $key => $val){
            if ($i==0){
                $stetmant.= " $val ";
                $i++;
            }else{
                $stetmant.= ", $val ";
            }
        }
        $stetmant.=" FROM ".$this->table." where ";
        $i=0;
        foreach ($select as $key => $val){
            if ($i==0){
                $stetmant.= " $key =:$key ";
                $i++;
            }else{
                $stetmant.= " and $key =:$key ";
            }
        }
        return $this->query($select,$stetmant);
    }


    #bir nesnesyi eleyerek çağırma or ile belirli sutunarı çektik
    public function getElementOr($data,$select){
        $stetmant="SELECT "; 
        $i=0;
        foreach ($data as $key => $val){
            if ($i==0){
                $stetmant.= " $val ";
                $i++;
            }else{
                $stetmant.= ", $val ";
            }
        }
        $stetmant.=" FROM ".$this->table." where ";
        $i=0;
        foreach ($select as $key => $val){
            if ($i==0){
            $stetmant.= " $key =:$key ";
                $i++;
            }else{
                $stetmant.= " or $key =:$key ";
            }
        }
        return $this->query($select,$stetmant);
    }
    






    


    #bir nesnesyi eleyerek çağırma or ile belirli sutunarı çektik
    public function getElementAllColumn($data){
        $stetmant="SELECT "; 
        $i=0;
        foreach ($data as $key => $val){
            if ($i==0){
                $stetmant.= " $val ";
                $i++;
            }else{
                $stetmant.= ", $val ";
            }
        }
        $stetmant.=" FROM ".$this->table;
        
        return $this->query([],$stetmant);
    }



    #genel tablo çekme bir array olarak çekme
    public function getArr(){
        $stetmant="SELECT * FROM ".$this->table; 
        return $this->query([],$stetmant);   
    }
    #genel tablo çekme bir obje olarak
    public function getObj(){
        $stetmant="SELECT * FROM ".$this->table ;
        $data=$this->query([],$stetmant);
        $obj=[];
        $obj1=[];
        $i=0;
        if(!empty($data[0])){
            foreach($data as $key => $val){
                $i=0;
                $obj1=[];
                foreach($val as $key1 => $val1 ){
                    if("$i"=="$key1"){
                        $i++;
                    }else{
                        $obj1[$key1]=$val1;
                    }
                }
                array_push($obj,(object)$obj1);
            }
            return (object)$obj;
        }
        return (object)[];
    }
}
?>
