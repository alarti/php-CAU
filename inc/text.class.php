<?php	

	class text {
		/**
		* funcion que separa la consulta de la busqueda en tokens delimitador por un caracter
		* ademas elimina los tokens vacios
		* @author Alberto Arce & Esteban Garcia
		* @var $caracter: caracter separador de las expresiones
		* @var $entrada: texto que se separara
		* @return la funcian devuelve una matriz con los datos separados
		*/
		function separa($caracter,$entrada){
		
			$arrayEntrada = split($caracter,$entrada);
			$j = 0;

			for ($i=0;$i<count($arrayEntrada);$i++){
			
				if (
					(ord($arrayEntrada[$i]) != 13) && 
					(ord($arrayEntrada[$i]) != 32) && 
					(ord($arrayEntrada[$i]) != 0)
					){

					$arrayFinal[$j] = $arrayEntrada[$i];
					$j ++;
				}			
			}

			return($arrayFinal);
		}
		/**
		* funcion crea una matriz con campos y valores
		* 
		* parametro: $caracter que separa los campos dentro del texto
		* parametro: $campos texto donde estan los campos
		* parametro: $valores texto donde estan los valores de los campos
		*/
		function matriz($caracter,$campos,$valores){
			$a = split($caracter,$campos);
			$b = split($caracter,$valores);
			array_shift($a);
			array_shift($b);
			array_pop($a);
			array_pop($b);
			$c = array_combine($a, $b);
			return $c;
		}

		/*
			función que devuelve una matriz con la parte que en la posicion 0 lleva la parte entera
			y en la posicion 1 la parte decimal
		*/
		function numericoDecimal($cadena){
			$salida[0] = floor($cadena);
			$salida[1] = floor(($cadena - floor($cadena))*100);
			return($salida);
		}

		/*
			función que devuelve true si la cadena pasada solo tiene numero y false en caso contrario
		*/
		function numerico($cadena){			

			$caracteres = "0123456789";
			$salida = true;
			
			for ($i=0;$i<strlen($cadena);$i++){
		
				$caracter = substr($cadena,$i,1);
				$posicion = strpos($caracteres, $caracter);

				// Seguidamente se utiliza ===.  La forma simple de comparacion (==)
				// no funciona como deberia, ya que la posicion de 'a' es el caracter
				// numero 0 (cero)
				if ($posicion === false) {
				   $salida = false;
				}
			}
			return $salida;
		}


		/*
			función que devuelve true si la cadena pasada tiene numeros y letras y false en caso contrario
		*/
		function alfanumerico($cadena){		

			$caracteres = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyz
                                        áéíóúàèìòùâêîôû
                                        @€.-_ ,0123456789?¿!¡{}[]<>()#%&=ºª*Ç
                                      ";
                        $salida = true;
			
			for ($i=0;$i<strlen($cadena);$i++){
		
				$caracter = substr($cadena,$i,1);
				$posicion = strpos($caracteres, $caracter);
			
				// Seguidamente se utiliza ===.  La forma simple de comparacion (==)
				// no funciona como deberia, ya que la posicion de 'a' es el caracter
				// numero 0 (cero)
				if ($posicion === false) {
                                    if (!preg_match('/[\r?\n]+/', $caracter))
				   $salida = false;
				}
			}
			return $salida;
		}
		
		
		/*
			función que devuelve true si la cadena pasada tiene s�lo letras y false en caso contrario
		*/
		function alfabetico($cadena){			

			$caracteres = "ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwxyzáéíóúàèìòùâêîôû@€.-_ ,";
			$salida = true;
			
			for ($i=0;$i<strlen($cadena);$i++){
		
				$caracter = substr($cadena,$i,1);
				$posicion = strpos($caracteres, $caracter);
			
				// Seguidamente se utiliza ===.  La forma simple de comparacion (==)
				// no funciona como deberia, ya que la posicion de 'a' es el caracter
				// numero 0 (cero)
				if ($posicion === false) {
				   $salida = false;
				}
			}
			return $salida;
		}

		/*
			función que devuelve true si la cadena pasada tiene sólo letras basadas en el correo electronico y false en caso contrario
		*/
		function alfabeticoNet($cadena){			

			$caracteres = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890@._-";
			$salida = true;
			
			for ($i=0;$i<strlen($cadena);$i++){
		
				$caracter = substr($cadena,$i,1);
				$posicion = strpos($caracteres, $caracter);
			
				// Seguidamente se utiliza ===.  La forma simple de comparacion (==)
				// no funciona como deberia, ya que la posicion de 'a' es el caracter
				// numero 0 (cero)
				if ($posicion === false) {
				   $salida = false;
				}
			}
			return $salida;
		}
	}
?>