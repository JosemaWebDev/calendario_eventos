<?php
	class Router{
		public $directorioBase = "";
		private $profundidadDirectorios = 0;
		private $rutas =  array();
		private $vista = "";
		public $urlRedireccion = "";//no obligatoria si es vacio se coge directorioBase ejempo $OBJETO_INICIALIZADO->directorioBase."/xxx"
		public $parametros=[];// registramos las url que empiezan con : 

		//$url= ruta absoluta  debe tener el formato-> con http y sin / al final
		public function setDirectorioBase($url){
			$this->directorioBase = $url;
			$this->profundidadDirectorios = count(explode("/",explode("//",$url)[1]));
		}

		//$url = $_SERVER["REQUEST_URI"]
		public function navegar($url){
			$url = explode("/",$url);
			for($i = 0; $i < $this->profundidadDirectorios; $i++){
				array_shift($url);
			}
			if(!$this->setVista($url)){
				$this->redirigir($this->urlRedireccion != "" ? $this->urlRedireccion : $this->directorioBase);
			}
		}

		public function registrarRuta($ruta,$vista){ 
			array_push($this->rutas,["ruta" => explode("/", $ruta), "vista" => $vista]);
		}

		private function setVista($ruta){
			for($i = 0; $i < count($this->rutas); $i++){
				if(count($ruta) == count($this->rutas[$i]["ruta"])){
					$contador = 0;
					for($j = 0; $j < count($ruta);$j++){
						if($ruta[$j] == $this->rutas[$i]["ruta"][$j]) { 
							$contador++ ;
						}elseif( $j>0 && $contador>=$j && str_split($this->rutas[$i]["ruta"][$j])[0]==":" && is_numeric($ruta[$j])){
							$this->parametros[substr($this->rutas[$i]["ruta"][$j],1)] = intval($ruta[$j]);

							$contador++ ;
						}
					}

					if($contador == count($ruta)){
						$this->vista = $this->rutas[$i]["vista"];
						return true;
					}
				}
			}
			return false;
		}

		public function getVista(){
			return $this->vista;
		}
		public function getProfundidadDirectorios(){
			return $this->profundidadDirectorios;
		}

		//$url = a la pagina que nos lleva si es incorrecto la profundidad o la ruta descrita
		private function redirigir($url){
			header("Location: {$url}");
			exit;
		}	
	}  
?>
