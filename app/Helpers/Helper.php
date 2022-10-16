<?php


namespace App\Helpers;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Helper
{
    static function getEnv()
    {
        return App::environment();
    }

    static function isVendor( $user )
    {
        return $user->userRole->role_id == Constant::USER_ROLES['vendor'];
    }

    static function isSuperAdmin( $user )
    {
        return $user->userRole->role_id == Constant::USER_ROLES['superAdmin'];
    }

    static function isAdmin( $user )
    {
        $roles = [
            Constant::USER_ROLES['superAdmin'],
            Constant::USER_ROLES['admin'],
            Constant::USER_ROLES['samsungAdmin'],
            Constant::USER_ROLES['cheilAdmin']
        ];

        return in_array($user->userRole->role_id, $roles);
    }

    static function isAdminOnly( $user )
    {
        $roles = [
            Constant::USER_ROLES['admin'],
            Constant::USER_ROLES['samsungAdmin'],
            Constant::USER_ROLES['cheilAdmin']
        ];

        return in_array($user->userRole->role_id, $roles);
    }

    static function isRegional( $user )
    {
        $roles = [
            Constant::USER_ROLES['samsungRegional'],
            Constant::USER_ROLES['cheilRegional']
        ];

        return in_array($user->userRole->role_id, $roles);
    }

    // Snippet from PHP Share: http://www.phpshare.org
    static function formatSizeUnits($bytes)
    {
        // $bytes = $bytes / 1024; // convert as KB instead of Byte only.
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    static function validationErrors ($request, $rules) {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $validator->errors();
        } else {
            return false;
        }
    }

    static function generateCsv($data, $delimiter = ',', $enclosure = '"')
    {
        $contents = '';
        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, array_keys($data[0]));
        foreach ($data as $line) {
            fputcsv($handle, $line, $delimiter, $enclosure);
        }
        rewind($handle);
        while (!feof($handle)) {
            $contents .= fread($handle, 8192);
        }
        fclose($handle);
        return $contents;
    }

    static function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    static function buildTree($elements, $parentId = 0) {
        $branch = [];

        foreach ($elements as $element) {

            if ($element->stage->parent_id == $parentId) {
                $children = self::buildTree($elements, $element->stage->parent_id);
                print_r($element->stage->parent_id); die;
                if ($children) {
                    $element['children'] = $children;
                }

                $branch[] = $element;
            }
        }
        return $branch;
    }
}
