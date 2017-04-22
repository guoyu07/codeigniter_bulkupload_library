<?php


/**
 *  PHPExcel_Writer_PDF_Core
 *
 *  Copyright (c) 2006 - 2015 PHPExcel
 *
 *  This library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 *
 *  This library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with this library; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * 
 *  @author      Shyam Makwana <shyam.makwana18@gmail.com>
 *  @website	 http://shyammakwana.me
 *  @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 *  @version     ##VERSION##, ##DATE##
 */
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * Bulk upload XLS/XLSX/CSV file and validate them.
 *
 * @package     BulkUpload Records from XLS/XLSX file
 * @author      Shyam Makwana<shyam.makwana18@gmail.com>
 * @copyright   Copyright (c) 2016 - 2017, Shyam Makwana
 * @license     http://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        http://shyammakwana.me
 * @since       v1.0.0
 * ---------------------------------------------------------------------------- */

require dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';

/**
 * Bulk uploading customers data
 *
 *
 * @package Libraries
 */
class Bulk_upload {

    /**
     * CodeIgniter Instance
     *
     * @var CodeIgniter
     */
    protected $CI;
	
	/**
     * Holds an assisiative array of mandatory fields with column name in XLS/XLSX file.
     *
     * @var mandatory_fields
     */
    protected $mandatory_fields;
	
	/**
     * Holds an array of sheet data after reading sheet.
     *
     * @var sheetData
     */
    protected $sheetData;
	
	/**
     * Holds an array of invalid rows (numbers are row numbers from sheet).
     *
     * @var invalidRows
     */
    protected $invalidRows;

    function __construct() {
        $this->CI = & get_instance();
        $this->invalidRows = [];
        $this->mandatory_fields = [
            'first_name' => 'A',
            'last_name' => 'B',
            'phone_number' => 'D',
        ];
		
        $this->fields_mapping = [
            'A' => 'first_name',
            'B' => 'last_name',
            'C' => 'email',
            'D' => 'phone_number',
            'E' => 'address',
            'F' => 'city',
            'G' => 'state',
            'H' => 'zip_code',
        ];

    }

	/**
     * Verify XLS/XLSX/CSV sheet that it has proper data according to requirement. 
	 * Here in this case I'm checking that sheet has max column H, then it's valid.
     *
     * @var file
     */
    public function verify($file) {
        $message = '';
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        $this->sheetData = $objPHPExcel->getActiveSheet()->toArray(null, false, true, true);
        
        if ($objPHPExcel->getActiveSheet()->getHighestColumn() !== 'H') {
            $message = 'Sheet data is not in proper format';
        } else {

            $i = 1;

            foreach ($this->sheetData as $key => $row) {

                if ($i > 1) { // skip first row 
                    if (!$this->is_record_valid($row)) {
                        $this->invalidRows[] = $key;
                        unset($this->sheetData[$key]);
                    }
                }
                $i++;
            }

            unset($this->sheetData[1]);
        }
        return array(
            'validData' => $this->sheetData,
            'invalidData' => $this->invalidRows,
            'message' => $message
        );
    }

    protected function is_record_valid($data) {
        $valid = false;
        foreach ($data as $key => $val) {
            if ($key == $this->mandatory_fields['first_name'] || $key == $this->mandatory_fields['last_name']) {
                if (!empty(trim($val))) {
                    $valid = true;
                } else {
                    $valid = false;
                    break;
                }
            }
            if ($key == $this->mandatory_fields['phone_number']) {
                if (ctype_digit(trim($val)) && strlen(trim($val)) == 10) {
                    $valid = true;
                } else {
                    $valid = false;
                    break;
                }
            }
        }
        return $valid;
    }

    public function get_fields_mapping() {
        return $this->fields_mapping;
    }

}

/* End of file bulk_upload.php */
/* Location: ./application/libraries/bulk_upload.php */