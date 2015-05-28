<?php
/*
 * Copyright 2005-2015 CENTREON
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 * 
 * This program is free software; you can redistribute it and/or modify it under 
 * the terms of the GNU General Public License as published by the Free Software 
 * Foundation ; either version 2 of the License.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with 
 * this program; if not, see <http://www.gnu.org/licenses>.
 * 
 * Linking this program statically or dynamically with other modules is making a 
 * combined work based on this program. Thus, the terms and conditions of the GNU 
 * General Public License cover the whole combination.
 * 
 * As a special exception, the copyright holders of this program give CENTREON 
 * permission to link this program with independent modules to produce an executable, 
 * regardless of the license terms of these independent modules, and to copy and 
 * distribute the resulting executable under terms of CENTREON choice, provided that 
 * CENTREON also meet, for each linked independent module, the terms  and conditions 
 * of the license of that module. An independent module is a module which is not 
 * derived from this program. If you modify this program, you may extend this 
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 * 
 * For more information : contact@centreon.com
 * 
 */

namespace Centreon\Commands\Database;

use Centreon\Internal\Command\AbstractCommand;
use Centreon\Internal\Exception;
use Centreon\Internal\Di;
use Centreon\Internal\Installer\Database\Installer as DbInstaller;

class ToolsCommand extends AbstractCommand
{
    
     /**
     * Extract json file and prints a sql string 
     *
     * @param string $file
     * @param string $tablename
     * @param string destination
     */
    public function jsonToSqlAction($file, $tablename, $destination = '')
    {
        $sInsert = "INSERT INTO ".$tablename."(";
        if (file_exists($file)) {
            try {
                $sColumns = '';
                $aData = json_decode(file_get_contents($file), true);
 
                $sSql = '';

                foreach($aData as $value) {
                    $sColumns = implode(',', array_keys($value));
                    $sSql .= "(".'"'.implode('","',array_values($value)).'"'."), "; 
                }
                $sChars = $sInsert.$sColumns.") VALUES ".$sSql;
                $sContent = substr($sChars, 0, strlen($sChars) - 2 ).";";
               // echo $destination;
                
                if (empty($destination)) {
                    echo $sContent;
                } else  {
                    if (file_exists($destination)) {
                        file_put_contents($sContent, $destination);
                    } else throw new Exception("invalide desination");
                }
             
            } catch (Exception $ex) {
                throw new Exception("invalid content");
                //return "invalid content";
            } 
        }
    }

    /**
     * 
     */
    public function generateMigrationClassAction()
    {
        $myMigrationManager = new DbInstaller();
        $myMigrationManager->generateDiffClasses('/tmp');
    }
}