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
 * @version 0.2.0-beta
 */

Class yamles{
    
    private static $tab = "/\s\s\s\s/";
    
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
        
        $levels_index = array();
        
        /*
         *  Recorre todas las lineas del archivo,
         *  identifica el nivel del vector,
         *  crea indices para cada vector
         */
        foreach($ymlData as $key => $value){
            
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
            if(strpos($value, ': ') && preg_match(self::$tab, $value) == 0){
                
                $yml[$ymlStringParsed[0]] = $ymlStringParsed[1];
                
                continue;
                
            }
            
            /*
             * indice de vector
             */
            if(!isset($ymlStringParsed[1])){
                
                /*
                 * Nivel de indices
                 */
                $level = substr_count($value, ' ') / 4;
                
                /*
                 * Nombre de indice
                 */
                $value = trim(str_replace(':','',$value));
                
                /*
                 * Arma vector de indice por niveles
                 */
                $levels_index[$level] = $value;
                
                continue;
                
            }
            
            /*
             * Elimina espacios en blanco
             */
            $ymlStringParsed = $ymlStringParsed = array_map('trim', $ymlStringParsed);
            
            switch($level){
                case 0 : // Vector de nivel 1 -> tab 0 espacios
                    $yml[$levels_index[0]][$ymlStringParsed[0]] = $ymlStringParsed[1]; 
                break;
                
                case 1 : // Vector de nivel 2 -> tab 4 espacios
                    $yml[$levels_index[0]][$levels_index[1]][$ymlStringParsed[0]] = $ymlStringParsed[1];
                break;
                
                case 2 : // Vector de nivel 3 -> tab 8 espacios
                    $yml[$levels_index[0]][$levels_index[1]][$levels_index[2]][$ymlStringParsed[0]] = $ymlStringParsed[1]; 
                break;
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