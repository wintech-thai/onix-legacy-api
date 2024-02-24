<?php
/*
    Created By : Seubpong Monsar
    Created Date : 09/02/2017 (MM/DD/YYYY)
    IBSVer : 1.0
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class CSql
{
    private static $RECORD_PER_CHUNK = 80;
    private static $lastSQL = "";
    private static $dumpFlag = false;

    public static function SetDumpSQL($flag)
    {
        self::$dumpFlag = $flag;
    }

    private static function dumpSQL($sql, $method)
    {
        if (self::$dumpFlag)
        {
            CLog::WriteLnMt($method, $sql);
        }
    }

    public static function CreateSelectColumnList($columns)
    {
        $cols = array();
        foreach ($columns as $c)
        {
            //list($name, $type, $alias, $whereFlag, $selectFlag) = explode(':', $c);
            $fields = explode(':', $c);
            $name = $fields[0]; 
            $type = $fields[1]; 
            $alias = $fields[2];
            $whereFlag = $fields[3];

            $selectFlag = "";
            if (isset($fields[4]))
            {
                //New fields add, checked for backward compatible
                $selectFlag = $fields[4];
            } 
            
            if (($type != 'FD') && ($type != 'TD') && ($type != 'INC_SET') && 
                ($type != 'EXC_SET') && ($type != 'IS_NULL'))
            {
                if ($selectFlag != 'N')
                {
                    $value = "$name as $alias";
                    array_push($cols, $value);
                }
            }
        }

        $col_list = join(", ", $cols);

        return($col_list);
    }

    public static function EscapeSqlQuote($str)
    {
        return(str_replace("'", "''", $str));
    }

    public static function CreateSelectWhereList($table, $columns)
    {
        $str_cmp = $table->GetFieldValue('!EXT_EQUAL_STRING_COMPARE_FIELDS');
        $exceptions = explode('|', $str_cmp);

        $cols = array();
        foreach ($columns as $c)
        {
            list($name, $type, $alias, $whereFlag) = explode(':', $c);

            $val = $table->GetFieldValue($alias);
            $value = "";

            if ($whereFlag == 'Y')
            {
                if ($type == 'S')
                {
                    if ($val != '')
                    {
                        if (in_array($name, $exceptions))
                        {
                            $value = "($name = '" . self::EscapeSqlQuote($val) . "')";
                        }
                        else
                        {
                            $value = "($name LIKE '" . self::EscapeSqlQuote($val) . "%')";
                        }

                        array_push($cols, $value);
                    }
                }
                elseif ($type == 'FD')
                {
                    if ($val != '')
                    {
                        $value = "($name >= '" . self::EscapeSqlQuote($val) . "')";
                        array_push($cols, $value);
                    }
                }
                elseif ($type == 'TD')
                {
                    if ($val != '')
                    {
                        $value = "($name <= '" . self::EscapeSqlQuote($val) . "')";
                        array_push($cols, $value);
                    }
                }
                elseif ($type == 'EXC_SET')
                {
                    if ($val != '')
                    {
                        $value = "($name NOT IN " . self::EscapeSqlQuote($val) . ")";
                        array_push($cols, $value);
                    }
                }
                elseif ($type == 'INC_SET')
                {
                    if ($val != '')
                    {
                        $value = "($name IN " . self::EscapeSqlQuote($val) . ")";
                        array_push($cols, $value);
                    }
                }
                elseif ($type == 'IS_NULL')
                {
                    if ($val == 'Y')
                    {
                        $value = "($name IS NULL)";
                        array_push($cols, $value);
                    }
                    elseif ($val == 'N')
                    {
                        $value = "($name IS NOT NULL)";
                        array_push($cols, $value);
                    }
                }
                elseif ($type == 'CST')
                {
                    if ($val != '')
                    {
                        $value = "($val)";
                        array_push($cols, $value);
                    }
                }
                else
                {
                    self::validateFiledValueForPK($val);
                    if ($val > 0)
                    {
                        $value = "($name = $val)";
                        array_push($cols, $value);
                    }
                }
            }
            else
            {
                continue;
            }
        }

        $where_list = join(" AND ", $cols);

        if ($where_list != '')
        {
            $where_list = "WHERE " . $where_list;
        }

        return($where_list);
    }

    private static function validateFiledValueForPK($value)
    {
        if ($value == '')
        {
            return;
        }

        if (!is_numeric($value))
        {
            throw new Exception("Value is not in the numeric form [$value]");
        }
    }

    public static function GetLastSQL()
    {
        return(self::$lastSQL);
    }

    public static function QuerySQL($dbh, $stmt, $columns, $lim, $offset, $order_by)
    {
        $cnt = 0;
        $trd_history = array();

        if ($order_by != '')
        {
            $stmt = $stmt . "\n $order_by";
        }

        if (($lim >= 0) && ($offset >= 0))
        {
            $stmt = $stmt . "\n LIMIT $lim OFFSET $offset";
        }

        self::$lastSQL = $stmt;
        self::dumpSQL($stmt, __METHOD__);

        $select = $dbh->query($stmt);
        $total_column = $select->columnCount();

        for ($counter = 0; $counter < $total_column; $counter++) 
        {
            $meta = $select->getColumnMeta($counter);
            $col_name = $meta['name'];

            $cols[$counter] = $col_name;
        }

        foreach ($select as $row)
        {
            $cnt++;
            $obj = new CTable("");

            $td = array();
            $col_idx = 0;

            foreach ($cols as $cnm)
            {
                $key = strtoupper($cnm);
                $value = $row[$cnm];
                $obj->SetFieldValue($key, $value);
            }

            array_push($trd_history, $obj);
        }

        return(array($cnt, $trd_history));
    }

    public static function GetSeq($dbh, $name, $init_value)
    {
        $seq = 0;
        $columns = array('NEXTVAL');
        $stmt = "SELECT NEXTVAL('$name')";

    #print("$stmt\n");
        self::dumpSQL($stmt, __METHOD__);
        list($rc, $result) = self::QuerySQL($dbh, $stmt, $columns, -1, -1, '');

        if ($rc <= 0)
        {
            throw new Exception("Sequence [$name] not found!!!");
        }

        $h = $result[0]; #First in array
        $seq = $h->GetFieldValue('NEXTVAL');

        return($seq);
    }

    private static function GetCurrentDateTimeInternal()
    {
        return(date('Y/m/d H:i:s'));
    }

    public static function CreateInsertSql($table, $columns, $table_name)
    {
        $cols = array();
        $values = array();

        foreach ($columns as $c)
        {
            list($name, $type) = explode(':', $c);

            $value = 'NULL';
            $val = $table->GetFieldValue($name);

            if ($type == 'PK')
            {
                $value = 'NULL';
            }
            elseif ($type == 'REFID')
            {
                self::validateFiledValueForPK($val);
                if (($val == '') || ($val <= 0))
                {
                    $value = 'NULL';
                }
                else
                {
                    $value = $val;
                }
            }
            elseif ($type == 'S')
            {
                $value = "'" . self::EscapeSqlQuote($val) . "'";
            }
            elseif ($type == 'SD')
            {
                $value = "'" . self::GetCurrentDateTimeInternal() . "'";
            }
            elseif ($type == 'CD')
            {
                $value = "'" . self::GetCurrentDateTimeInternal() . "'";
            }
            elseif ($type == 'MD')
            {
                $value = "'" . self::GetCurrentDateTimeInternal() . "'";
            }
            elseif ($type == 'NZ')
            {
                self::validateFiledValueForPK($val);
                if ($val == '')
                {
                    $value = '0.00';
                }
                else
                {
                    $value = $val;
                }
            }
            else
            {
                self::validateFiledValueForPK($val);
                $value = $val;
            }

            array_push($cols, $name);
            array_push($values, $value);
        }

        $col_list = join(", ", $cols);
        $value_list = join(", ", $values);

$stmt = <<<EOD
INSERT INTO $table_name
(
    $col_list
)
VALUES
(
    $value_list
);
EOD;

        return(array($col_list, $value_list, $stmt));
    }

    public static function Insert($dbh, $ind, $param, $columns, $table_name, $pk_field_name, $auto)
    {
        $col_ptr = $columns[$ind];

        if ($auto)
        {
            $id = self::GetSeq($dbh, $table_name . '_SEQ', 1);
            $param->SetFieldValue($pk_field_name, $id);
        }

        list($col_list, $value_list, $sql) = self::CreateInsertSql($param, $col_ptr, $table_name);
        self::$lastSQL = $sql;
        self::dumpSQL($sql, __METHOD__);

        $stmt = $dbh->prepare("$sql");
        $stmt->execute();
        $added = $stmt->rowCount();

        if ($added <= 0)
        {
            throw new Exception("No data has been added!!!");
        }
//print("DEBUG [$sql][$added]\n");

        return($added);
    }

    public static function CreateDeleteSql($table, $columns, $table_name)
    {
        $where_list = "";

        foreach ($columns as $c)
        {
            list($name, $type) = explode(':', $c);

            $value = 'NULL';
            $val = $table->GetFieldValue($name);

            if (($type == 'PK') || ($type == 'SPK'))
            {
                self::validateFiledValueForPK($val);

                $value = $val;
                $where_list = "$name=$value";
            }
        }

        $stmt = "DELETE FROM $table_name WHERE ($where_list)";

        return($stmt);
    }

    public static function Delete($dbh, $ind, $param, $columns, $table_name)
    {
        $col_ptr = $columns[$ind];
        $sql = self::CreateDeleteSql($param, $col_ptr, $table_name);
        self::$lastSQL = $sql;
        self::dumpSQL($sql, __METHOD__);

        $stmt = $dbh->prepare("$sql");
        $stmt->execute();
        $deleted = $stmt->rowCount();

        return($deleted);
    }

    public static function CreateUpdateSql($table, $columns, $table_name)
    {
        $where_list = "";
        $upd = "";

        $values = array();
        foreach ($columns as $c)
        {
            list($name, $type) = explode(':', $c);

            $value = 'NULL';
            $val = $table->GetFieldValue($name);

            if ($type == 'PK')
            {
                self::validateFiledValueForPK($val);

                $value = $val;
                $where_list = "$name=$value";
            }
            elseif ($type == 'SPK')
            {
                self::validateFiledValueForPK($val);

                $value = $val;
                $where_list = "$name=$value";
            }
            elseif ($type == 'SSPK')
            {
                self::validateFiledValueForPK($val);

                $value = $val;
                $where_list = "$name='$value'";
            }
            elseif ($type == 'REFID')
            {
                self::validateFiledValueForPK($val);

                if (($val == '') || ($val <= 0))
                {
                    $value = 'NULL';
                }
                else
                {
                    $value = $val;
                }

                $upd = "$name=$value";
                array_push($values, $upd);
            }
            elseif ($type == 'S')
            {
                $value = "'" . self::EscapeSqlQuote($val) . "'";
                $upd = "$name=$value";
                array_push($values, $upd);
            }
            elseif ($type == 'SD')
            {
                $value = "'" . self::GetCurrentDateTimeInternal() . "'";
                $upd = "$name=$value";
                array_push($values, $upd);
            }
            elseif ($type == 'CD')
            {
                #$value = "'" . wisrad_utils_lib::get_current_dtm() . "'";
                #$upd = "$name=$value";
                #push(@values, $upd);
            }
            elseif ($type == 'MD')
            {
                $value = "'" . self::GetCurrentDateTimeInternal() . "'";
                $upd = "$name=$value";
                array_push($values, $upd);
            }
            elseif ($type == 'NZ')
            {
                self::validateFiledValueForPK($val);

                if ($val == '')
                {
                    $value = '0.00';
                }
                else
                {
                    $value = $val;
                }

                $upd = "$name=$value";
                array_push($values, $upd);
            }
            else
            {
                self::validateFiledValueForPK($val);

                $value = $val;
                $upd = "$name=$value";
                array_push($values, $upd);
            }
        }

        $value_list = join(", ", $values);

        $stmt = "UPDATE $table_name SET $value_list WHERE ($where_list)";

        return($stmt);
    }

    public static function Update($dbh, $ind, $param, $columns, $table_name)
    {
        $col_ptr = $columns[$ind];

        $sql = self::CreateUpdateSql($param, $col_ptr, $table_name);
        self::$lastSQL = $sql;
        self::dumpSQL($sql, __METHOD__);

        $stmt = $dbh->prepare("$sql");
        $stmt->execute();
        $updated = $stmt->rowCount();

//print("$sql\n[$updated]\n");
        return($updated);
    }

    private static function CalculateLimitOffsetChunk($item_cnt, $chunk_no)
    {
        if (($chunk_no < 0) || ($chunk_no == ''))
        {
            $chunk_no = 1;
        }

        $offs = (($chunk_no-1) * self::$RECORD_PER_CHUNK);
        $lim = self::$RECORD_PER_CHUNK;
        $chk_cnt = ceil($item_cnt / self::$RECORD_PER_CHUNK);

        return(array($lim, $offs, $chk_cnt));
    }

    public static function QueryChunk($dbh, $ind, $param, $columns, $froms, $orders)
    {
        $col_ptr = $columns[$ind];

        $col_list = self::CreateSelectColumnList($col_ptr);
        $where_list = self::CreateSelectWhereList($param, $col_ptr);
        $from_list = $froms[$ind];
        $order_list = $orders[$ind];

        $cnt_stmt = "SELECT COUNT(*) EXT_RECORD_COUNT $from_list $where_list";
        $cnt_columns = array('EXT_RECORD_COUNT:N:EXT_RECORD_COUNT:N');
        list($rc, $cnt_result) = self::QuerySQL($dbh, $cnt_stmt, $cnt_columns, -1, -1, '');
        if ($rc < 0)
        {
            throw new Exception("SQL count less than zero!!!");
        }

        $obj = $cnt_result[0];
        $item_cnt = $obj->GetFieldValue('EXT_RECORD_COUNT');

        $chunk_no = $param->GetFieldValue("EXT_CHUNK_NO");
        list($lim, $offset, $chunk_cnt) = self::CalculateLimitOffsetChunk($item_cnt, $chunk_no);

        $stmt = "SELECT $col_list $from_list $where_list";
        list($rc, $result) = self::QuerySQL($dbh, $stmt, $col_ptr, $lim, $offset, $order_list);

        return(array($rc, $item_cnt, $chunk_cnt, $result));
    }
}

?>