<?php
/**
 * YamLes
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
 * @version 0.1.0-alpha
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
     * @since 0.1.0-alpha
     */
    public static function parser($file, $path = null){
        
        
        
        /*
         *	Habilita el debug segun parametrización global,
         */
        if(defined('DEBUG') && (DEBUG == true)){

                error_reporting(E_ALL);ini_set("display_errors", 1);

        }
        
        /*
         *	Validaciones
         */
        if(!$file || $file == ''){ self::exception(001); }

        /*
         * Ubica archivo yml
         */
        $pathfile = ($path)? $path : '';

        $pathfile .= $file . ".yml";
        
        /*
         * Se asegura que el archivo exista
         */
        if(!file_exists($pathfile)){ self::exception(002); }

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
         *  Recorre todas las lineas del archivo,
         *  crea vector hasta de dos niveles
         *  actualmente solo se soporta el siguiente ejemplo:
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
             * key: value
             */
            $len = strlen($value);
            
            /*
             *  Si en la ultima posicion se encuentra
             *  unicamente dos puntos, se toma como vector
             */
            if(substr(trim($value), ($len-1)) == ':'){
                
                /*
                 *  Se crea vector
                 */
                $ymlKey = $value;
                
                $yml[$ymlKey]=array();

            }elseif(isset($ymlKey)){
                
                /*
                 *  Antes de los dos puntos se toma
                 *  como llave de vector
                 */
                $ymlsubkey = trim(substr($value, 0,strpos($value, ":")));
                
                /*
                 *  Se elimina la llave del vector del
                 *  string el resto se toma como el valor
                 */
                $ymlsubvalue = trim(str_replace($ymlsubkey.":", '', $value));
                
                /*
                 *  Se asigna el valor al indice actual del subvector
                 */
                $yml[$ymlKey][$ymlsubkey] = $ymlsubvalue;

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
     * @since 0.1.0-alpha
     */   
    private static function exception($code){

            echo "Error numero: " . $code;

            exit();

    }

}