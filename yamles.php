<?php
/**
 * Yamles
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Luis Gdonis <ldonis.emc2@gmail.com>
 * @link http://ldonis.com
 * @package YML Parser
 * @version 0.1.1-beta
 */

Class yamles{


    /**
     * Parser
     * @param $file: Nombre de archivo .yml
     * @param $path: Ruta de archivo .yml
     * ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ 
     * @package Front-Controller
     * @author Luis Gdonis <ldonis.emc2@gmail.com>
     * @link http://ldonis.com
     * @since 0.1.1-beta
     */
    public static function parser($file, $path = null){
        
        return self::yamles_parser($file, $path = null);
        
    }
    
    /**
     * YamLesParser
     * @param $file: Nombre de archivo .yml
     * @param $path: Ruta de archivo .yml
     * ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ 
     * @package Front-Controller
     * @author Luis Gdonis <ldonis.emc2@gmail.com>
     * @link http://ldonis.com
     * @since 0.1.1-beta
     */    
    private static function yamles_parser($file, $path = null){
        
        /*
         *	Habilita el debug segun parametrización global,
         */
        if(defined('DEBUG') && (DEBUG == true)){

                error_reporting(E_ALL);ini_set("display_errors", 1);

        }
        
        /*
         *	Validaciones
         */
        if(!$file || $file == ''){ self::exception(001); return; }

        /*
         * Ubica archivo yml
         */
        $pathfile = ($path)? $path : '';

        $pathfile .= $file . ".yml";
        
        /*
         * Se asegura que el archivo exista
         */
        if(!file_exists($pathfile)){ self::exception(002); return; }

        /*
         *	Inicializa variables
         */
        $yml = array();

        /*
         *	Obtiene el archivo yml
         */
        $ymlData = file_get_contents($file . ".yml");
        
        /*
         *	Explode por salto de linea
         */
        $ymlData = explode("\n", $ymlData);
        
        /*
         *      Se eliminan valores nulos
         */
        $ymlData = array_filter($ymlData);
        
        /*
         *      Reordena el indice del vector
         */
        $ymlData = array_values($ymlData);
        
        /*
         *      Se eliminan espacios en blanco
         */
        //$ymlData = array_map('trim', $ymlData);
        
        /*
         *  Recorre todas las lineas del archivo,
         *  crea vector hasta de dos niveles
         *  actualmente solo se soporta el siguiente ejemplo:
         *  nivel-01: texto de nivel 1
         *  nivel-01: mas texto de nivel 1
         *  nivel-01:
         *      subnivel-01: Texto de subnivel
         *      subnivel-02: Texto de subnivel
         *  nivel-02:
         *      subnivel-01: Texto de subnivel
         *      subnivel-02: Texto de subnivel
         */
        foreach($ymlData as $key => $value){
            
            /*
             * Total de caracteres, por cada linea
             */
            $len = strlen($value);
            
            /*
             *  Es comentario
             */
            if(substr($value,0,2) == '##'){
                
                continue;
                
            }
            
            /*
             *  Explode current string
             */
            $ymlStringParsed = explode(': ', $value);

            /*
             * Es una cadena unica
             */
            if(strpos($value, ': ') && preg_match('/\s\s\s\s/',$value) == 0){
                
                $yml[$ymlStringParsed[0]] = $ymlStringParsed[1];
                
                continue;
                
            }
            
            /*
             *  Es elemento de vector
             */
            if(preg_match('/\s\s\s\s/',$value) == 1 && $array){
                
                /*
                 *  Sub valores
                 *  $array = vector de nivel 1
                 *  $ymlStringParsed[0] = indice de subnivel
                 *  $ymlStringParsed[0] = texto de subnivel
                 */
                $yml[$array][trim($ymlStringParsed[0])] = $ymlStringParsed[1];
                
                continue;
                
            }else{
                
                $array = false;
                
            }
            
            /*
             *  Es indice de vector
             */
            if(substr(trim($value), ($len-1)) == ':'){

                /*
                 *  Elimina los dos puntos del indice
                 */
                $ymlStringParsed[0] = str_replace(':', '', $ymlStringParsed[0]);
                
                /*
                 *  Crea el indice en el vector principal
                 */
                $yml[$ymlStringParsed[0]] = array();
                
                /*
                 *  Indica que los elementos pertenecen a un vector
                 */
                $array = $ymlStringParsed[0];
                
                continue;
                
            }
            
        }
        
        return $yml;

    }
    
    /**
     * Capturador de errores
     * @param int $code Codigo de error
     * ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ ~·~ 
     * @package Front-Controller
     * @author Luis Gdonis <ldonis.emc2@gmail.com>
     * @link http://ldonis.com
     * @since 0.1.1-beta
     */   
    private static function exception($code){

        if(defined('DEBUG') && (DEBUG == true)){

            echo "Error numero: " . $code;

            exit();

        }

    }

}