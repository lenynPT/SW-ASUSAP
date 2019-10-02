<?php
	class viewsModel{
		public function getViewsModel($views){
			$WhiteList=["dashboard","newaasociat","gconsumo","erecibo","crecibo"
                ,"section","salon","admin","adminlist","adminsearch","teacher","student",
                "representative","registration","payments","institution","year","yearlist","myaccount","mydata"];
			if(in_array($views, $WhiteList)){
				if(is_file("./views/modules/".$views."-view.php")){
					$contents="./views/modules/".$views."-view.php";
				}else{
					$contents="login";
				}
			}elseif($views=="login"){
				$contents="login";
			}elseif($views=="index"){
				$contents="login";
			}else{
				$contents="404";
			}
			return $contents;
		}
	}