# Instructions

Library folder contains `bulk_upload.php` file which is main library. And supporting library is PHPExcel, which is property of respected author.

`bulk_upload.php` takes file uploaded file and then verifies it using PHPExcel file. After that each record is compared as per needs (e.g. firstname_(column A in sheet)_ should not be empty etc.) and then adds invalid row numbers to variable. It returns invalid rows, and valid rows. Then you can use this data to insert to database in controller. 

Controllers folder has one controller and contains one method. Which basically uploads file and validates it. Then pass it to library and prepare data for database operation. You have to do manual work to get this working. 

View contains html file with some jQuery.

PoC folder shows screenshots of how I have implemented in my project.

### Note:
_This library is distributed in the hope that it will be useful,_
_but WITHOUT ANY WARRANTY; without even the implied warranty of_
_MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU_
_Lesser General Public License for more details._
