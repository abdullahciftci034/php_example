<?php
class createSitemap {
    public $dom=null;
    public function createSitemap($path){
        $this->path=$path;
        $this->oku=$this->xml_dosya_oku();
    }
    #burda okuma işlemi ve kontrol işlemi yaptık
    public function xml_dosya_oku(){    
        if(is_file($this->path)){
            $this->dom=new DOMDocument('1.0', 'UTF-8') ;
            $this->dom->load($this->path);
            return true;
        }
        return false;
    }
    ####### sitemapindex ##############
    public function searchSitemap($link){
        echo $link;
        $final=false;
        foreach($this->dom->getElementsByTagName('sitemap') as $val){ 
            if(@$val->childNodes[1]->nodeValue === $link){
                $final=$val;
                break;
            }
        }  
        return $final;
    }
    public function sitemapAdd($link){
        if(!$this->searchSitemap($link)){
            $addSitemapNode= $this->dom->createElement("sitemap");  
            $addLocNode= $this->dom->createElement("loc",$link);
            $this->dom->{"childNodes"}[0]->appendChild($addSitemapNode);
            $this->dom->{"childNodes"}[0]->{"lastChild"}->appendChild($addLocNode);
            return true;
        }
        return false;
    }
    public function sitemapRemove($link){
        $node=$this->searchSitemap($link);
        if($node ==! null){
            $node->parentNode->removeChild($node);
            return true;
        }
       return false;
    }
    public function setSitemap($arr){
        $node=$this->searchSitemap($arr["link"]);
        unset($arr["link"]);
        if($node ==! null){
            foreach($arr as $key => $val){
                $node->getElementsByTagName($key)[0]->nodeValue=$val;
            }
        }else{
            return false;
        }
        return true;
    }


    ###### URLSET  ###################
    public function searchLink($link){
        foreach($this->dom->getElementsByTagName('url') as $val){ 
            if(@$val->childNodes[1]->nodeValue === $link){
                return $val;
            }
        }  
        return false;
    }
    public function linkAdd($loc,$lastmod,$changefreq,$priority){
        if(!$this->searchLink($loc)){
            $url= $this->dom->createElement("url");
            $loc = $this->dom->createElement("loc",$loc);
            $lastmod= $this->dom->createElement("lastmod",$lastmod);
            $changefreq= $this->dom->createElement("changefreq",$changefreq);
            $priority= $this->dom->createElement("priority",$priority);
            $this->dom->{"childNodes"}[0]->appendChild($url);
            $this->dom->{"childNodes"}[0]->{"lastChild"}->appendChild($loc);
            $this->dom->{"childNodes"}[0]->{"lastChild"}->appendChild($lastmod);
            $this->dom->{"childNodes"}[0]->{"lastChild"}->appendChild($changefreq);
            $this->dom->{"childNodes"}[0]->{"lastChild"}->appendChild($priority);
            return true;
        }
        return false;
    }

    public function linkRemove($link){
        $node=$this->searchLink($link);
        if($node ==! null){
            $node->parentNode->removeChild($node);
            return true;
        }
        return false;
    }
    public function setLink($arr){
        $node=$this->searchLink($arr["link"]);
        unset($arr["link"]);
        if($node ==! null){
            foreach($arr as $key => $val){
                $node->getElementsByTagName($key)[0]->nodeValue=$val;
            }     
        }else{
            return false;
        }
        return true;
    }

    public function save(){
        if(is_file($this->path)){
            $this->dom->save($this->path);
            return true;
        }
        return false;
    }
}
?>