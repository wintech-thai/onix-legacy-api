<?php
/* 
    Purpose : Library for managing Balance
    Created By : Seubpong Monsar
    Created Date : 09/18/2017 (MM/DD/YYYY)
    IBSVer : 1.0 
*/

declare(strict_types=1);

require_once "phar://onix_core_framework.phar/onix_core_include.php";

class BalanceAPI extends CBaseController
{
    const BAL_ITEM_TYPE_INVENTORY = '1';
    const BAL_ITEM_TYPE_CASH = '2';
    const BAL_ITEM_TYPE_AR = '3';
    const BAL_ITEM_TYPE_AP = '4';
    const BAL_ITEM_TYPE_POINT = '5';

    const BAL_OWNER_TYPE_LOCATION = '1';
    const BAL_OWNER_TYPE_CUSTOMER = '2';
    const BAL_OWNER_TYPE_SUPPLIER = '3';
    const BAL_OWNER_TYPE_CASHACCT = '4';

    const BAL_DOC_IMPORT = '1';
    const BAL_DOC_EXPORT = '2';
    const BAL_DOC_MOVE = '3';
    const BAL_DOC_ADJUST = '4';

    const BAL_LEVEL_GLOBAL = '1';
    const BAL_LEVEL_DAILY = '2';

    private static $cfg = NULL;
    private static $callerCallback = NULL;

    private static function initSqlConfig($db)
    {
        $config = [
            //Array name, model, query ind, insert/update/delete ind, delete-by-parent ind
            ['TEMP_BAL_DOC_ITEMS', new MFrwBalDocumentDetail($db), 0, 0, 1],        
        ];

        self::$cfg = $config;

        return($config);
    }

    private static function getItem($db, $data)
    {
        $d = new CTable("");
        
        $actlID = $data->GetFieldValue("ACTUAL_ID");
        if ($actlID != '')
        {
            $d->SetFieldValue("ACTUAL_ID", $actlID);
        }
        else
        {
            $d->SetFieldValue("ACTUAL_ID", "0");
            $d->SetFieldValue("BAL_ITEM_CODE", $data->GetFieldValue("BAL_ITEM_CODE"));
            $d->SetFieldValue("!EXT_EQUAL_STRING_COMPARE_FIELDS", "BAL_ITEM_CODE");  
        }
        
        $d->SetFieldValue("BAL_ITEM_TYPE", $data->GetFieldValue("BAL_ITEM_TYPE"));
                        
        $itm = new MFrwBalItem($db);
        list($cnt, $rows) = $itm->Query(0, $d);
        
        return([$itm, $cnt, $rows]);
    }

    private static function getOwner($db, $data)
    {
        $d = new CTable("");
        
        $actlID = $data->GetFieldValue("ACTUAL_ID");
        if ($actlID != '')
        {
            $d->SetFieldValue("ACTUAL_ID", $actlID);
        }
        else
        {        
            $d->SetFieldValue("ACTUAL_ID", "0");
            $d->SetFieldValue("BAL_OWNER_CODE", $data->GetFieldValue("BAL_OWNER_CODE"));
            $d->SetFieldValue("!EXT_EQUAL_STRING_COMPARE_FIELDS", "BAL_OWNER_CODE");  
        }
        
        $d->SetFieldValue("BAL_OWNER_TYPE", $data->GetFieldValue("BAL_OWNER_TYPE"));
                        
        $itm = new MFrwBalOwner($db);
        list($cnt, $rows) = $itm->Query(0, $d);

        return([$itm, $cnt, $rows]);
    }
    
    /* Insert if does not exist */
    public static function RegisterItem($db, $data)
    {
        /* Use type and code to be keys lookup */
        list($itm, $cnt, $rows) = self::getItem($db, $data);

        if ($cnt > 1)
        {
            throw new Exception('Get more than one item in the FRW_BAL_ITEM!!!');
        }

        if ($cnt == 0)
        {
            //Create the new one here
            $actID = $data->GetFieldValue('ACTUAL_ID');
            if ($actID == '')
            {
                $data->SetFieldValue('ACTUAL_ID', '0');
            }

            $itm->Insert(0, $data, true);
        }
        else
        {
            //Update the existing one
            $d = $rows[0];

            $id = $d->GetFieldValue($itm->GetPKName());
            $actID = $d->GetFieldValue('ACTUAL_ID');

            $data->SetFieldValue($itm->GetPKName(), $id);
            $data->SetFieldValue('ACTUAL_ID', $actID);
            $itm->Update(0, $data);
        }

        return($data);
    }

    /* Owner is equivalent to Location, CashAccount, Customer, Supplier etc. */
    public static function RegisterOwner($db, $data)
    {
        /* Use type and code to be keys lookup */
        list($itm, $cnt, $rows) = self::getOwner($db, $data);

        if ($cnt > 1)
        {
            throw new Exception('Get more than one item in the FRW_BAL_OWNER!!!');
        }

        if ($cnt == 0)
        {
            //Create the new one here
            $actID = $data->GetFieldValue('ACTUAL_ID');
            if ($actID == '')
            {
                $data->SetFieldValue('ACTUAL_ID', '0');
            }

            $itm->Insert(0, $data, true);
        }
        else
        {
            //Update the existing one
            $d = $rows[0];

            $id = $d->GetFieldValue($itm->GetPKName());
            $actID = $d->GetFieldValue('ACTUAL_ID');

            $data->SetFieldValue($itm->GetPKName(), $id);
            $data->SetFieldValue('ACTUAL_ID', $actID);
            $itm->Update(0, $data);
        }

        return($data);
    }   
    
    public static function UnRegisterOwner($db, $data)
    {
        /* Use type and code to be keys lookup */
        list($itm, $cnt, $rows) = self::getOwner($db, $data);

        if ($cnt != 1)
        {
            throw new Exception('Item count not equal 1 in the FRW_BAL_OWNER!!!');
        }
   
        $d = $rows[0];
        $itm->Delete(0, $d);

        return($data);
    }

    public static function UnRegisterItem($db, $data)
    {
        /* Use type and code to be keys lookup */
        list($itm, $cnt, $rows) = self::getItem($db, $data);

        if ($cnt != 1)
        {
            throw new Exception('Item count not equal 1 in the FRW_BAL_ITEM!!!');
        }
   
        $d = $rows[0];
        $itm->Delete(0, $d);

        return($data);
    }

    private static function docTypeToDirection($type)
    {
        $map = [
                   self::BAL_DOC_IMPORT => 'I', 
                   self::BAL_DOC_EXPORT => 'E' 
               ];

        $direction = $map[$type];

        return($direction);
    }

    private static function deriveItem($db, $type, $data, $itm)
    {
        $d = new CTable("");
        $d->SetFieldValue('BAL_ITEM_CODE', $itm->GetFieldValue('BAL_ITEM_CODE'));
        $d->SetFieldValue('BAL_ITEM_TYPE', $data->GetFieldValue('BAL_ITEM_TYPE'));
        list($m, $cnt, $rows) = self::getItem($db, $d);
        
        if ($cnt != 1)
        {
            throw new Exception('deriveItemAndOwner() : Get more than one item in the FRW_BAL_ITEM!!!');
        }
        
        $d = $rows[0];
        $itm->SetFieldValue('BAL_ITEM_ID', $d->GetFieldValue('BAL_ITEM_ID'));
        $itm->SetFieldValue('BAL_ITEM_CODE', $d->GetFieldValue('BAL_ITEM_CODE'));
        $itm->SetFieldValue('BAL_ITEM_ACTUAL_ID', $d->GetFieldValue('ACTUAL_ID'));
    }

    private static function deriveOwner($db, $type, $data, $itm, $fldName)
    {
        $owner = $data->GetFieldValue($fldName);

        $d = new CTable("");
        $d->SetFieldValue('BAL_OWNER_CODE', $owner);
        $d->SetFieldValue('BAL_OWNER_TYPE', $data->GetFieldValue('BAL_OWNER_TYPE'));
        list($m, $cnt, $rows) = self::getOwner($db, $d);
        
        if ($cnt != 1)
        {
            throw new Exception("deriveOwner() : [$owner][$cnt] Get more than one item in the FRW_BAL_OWNER!!!");
        }
        
        $d = $rows[0];
        $itm->SetFieldValue('BAL_OWNER_ID', $d->GetFieldValue('BAL_OWNER_ID'));
        $itm->SetFieldValue('BAL_OWNER_CODE', $owner);
        $itm->SetFieldValue('BAL_OWNER_ACTUAL_ID', $d->GetFieldValue('ACTUAL_ID'));
    }

    private static function splitItem($data, $itm, $grp)
    {
        $itemID = $itm->GetFieldValue('BAL_ITEM_ID');
        $actID = $itm->GetFieldValue('ACTUAL_ID');

        $itmo = new CTable("");
        $itmi = new CTable("");

        $diri = self::docTypeToDirection(self::BAL_DOC_IMPORT);
        $diro = self::docTypeToDirection(self::BAL_DOC_EXPORT);

        $itmi->SetFieldValue("DIRECTION", $diri);
        $itmo->SetFieldValue("DIRECTION", $diro);        

        $itmi->SetFieldValue("EXT_FLAG", "A");
        $itmo->SetFieldValue("EXT_FLAG", "A");
        
        $itmi->SetFieldValue("BAL_ITEM_ID", $itemID);
        $itmo->SetFieldValue("BAL_ITEM_ID", $itemID);

        $itmi->SetFieldValue("GROUP_ID", $grp);
        $itmo->SetFieldValue("GROUP_ID", $grp);
        
        $itmi->SetFieldValue("ACTUAL_ID", $actID);
        $itmo->SetFieldValue("ACTUAL_ID", $actID);

        //This will be actually calculated in the later step
        $qty = $itm->GetFieldValue('TX_QTY_AVG');
        $amt = $itm->GetFieldValue('TX_AMT_AVG');

        $itmi->SetFieldValue("TX_QTY_AVG", $qty);
        $itmo->SetFieldValue("TX_QTY_AVG", $qty);
        $itmi->SetFieldValue("TX_QTY_FIFO", $qty);
        $itmo->SetFieldValue("TX_QTY_FIFO", $qty);

        $itmi->SetFieldValue("TX_AMT_AVG", $amt);
        $itmo->SetFieldValue("TX_AMT_AVG", $amt);
        $itmi->SetFieldValue("TX_AMT_FIFO", $amt);
        $itmo->SetFieldValue("TX_AMT_FIFO", $amt);

        return([$itmi, $itmo]);
    }

    private static function normalizeBalItems($db, $type, $data)
    {
        $items = $data->GetChildArray('BAL_DOC_ITEMS');
        $nitems = [];
        $grp = 0;
        $refID = 0;

        foreach ($items as $itm)
        {
            $grp++;            

            //Derive BAL_ITEM_ID and BAL_OWNER_ID
            self::deriveItem($db, $type, $data, $itm);

            if (($type == self::BAL_DOC_IMPORT) || ($type == self::BAL_DOC_EXPORT))
            {
                self::deriveOwner($db, $type, $data, $itm, 'BAL_OWNER_CODE');

                $dir = self::docTypeToDirection($type);
                $itm->SetFieldValue("DIRECTION", $dir);
                $itm->SetFieldValue("EXT_FLAG", "A");
                $itm->SetFieldValue("GROUP_ID", '');

                $refID++;
                $itm->SetFieldValue("REF_ID", $refID);
                $itm->SetFieldValue("TX_CATEGORY", 'IO');

                array_push($nitems, $itm);     
            }
            elseif ($type == self::BAL_DOC_MOVE)
            {
                //Split item to two
                list($itmi, $itmo) = self::splitItem($data, $itm, $grp);

                self::deriveOwner($db, $type, $data, $itmi, 'BAL_OWNER_CODE_TO');
                self::deriveOwner($db, $type, $data, $itmo, 'BAL_OWNER_CODE_FROM');

                //Will be used in the callback function
                $refID++;
                $itmo->SetFieldValue("REF_ID", $refID);
                $itmo->SetFieldValue("TX_CATEGORY", 'XFER');

                $refID++;
                $itmi->SetFieldValue("REF_ID", $refID);
                $itmi->SetFieldValue("TX_CATEGORY", 'XFER');

                //Export first and then Import
                array_push($nitems, $itmo);
                array_push($nitems, $itmi);   
            }  
            elseif ($type == self::BAL_DOC_ADJUST)
            {
                self::deriveOwner($db, $type, $data, $itm, 'BAL_OWNER_CODE');
                //Caller populated the DIRECTION for us
                //$itm->SetFieldValue("DIRECTION", $dir);

                $itm->SetFieldValue("EXT_FLAG", "A");
                $itm->SetFieldValue("GROUP_ID", '');
                $itm->SetFieldValue("TX_CATEGORY", 'ADJ');

                $refID++;
                $itm->SetFieldValue("REF_ID", $refID);

                array_push($nitems, $itm);     
            }                      
        }

        $data->AddChildArray('TEMP_BAL_DOC_ITEMS', $nitems);
    }

    /* This is call back function */
    public static function updatePrice($cd, $param, $tx)
    {      
        if ($cd == 'AVG')
        {
            $arr = $param->GetNameArray();
            foreach ($arr as $name)
            {
                $value = $param->GetFieldValue($name);
                $tx->SetFieldValue($name, $value);
            }
        }
    }

    private static function populateDefaultField($data)
    {
        $docDate = $data->GetFieldValue('BAL_DOC_DATE');
        $actualDocDate = $data->GetFieldValue('ACTUAL_DOC_DATE');

        if ($actualDocDate == '')
        {
            $data->SetFieldValue('ACTUAL_DOC_DATE', $docDate);
        }
    }

    public static function Apply($db, $type, $data, $allowNotEnough, $callBack)
    {
//CSql::SetDumpSQL(true); 
        self::$callerCallback = $callBack;

        $tx = false;        
        if (!$db->inTransaction())
        {
            $db->beginTransaction();
            $tx = true;
        }

        $data->SetFieldValue("BAL_DOC_TYPE", $type);
        self::normalizeBalItems($db, $type, $data);

        $result = BalanceTxProcessor::Process($db, $data, ['BalanceAPI', 'updatePrice'], $allowNotEnough);
        if (!$result)
        {
            if ($tx) $db->rollBack();
            return(false);
        }

        self::populateDefaultField($data);
        
        $u = new MFrwBalDocument($db);
        $childs = self::initSqlConfig($db);
        self::CreateData($db, $data, $u, 0, $childs);

        if ($tx)
        {
            $db->commit();
        } 

        return(true);
    }  
    
    public static function Verify($db, $type, $data, $allowNotEnough)
    {
//CSql::SetDumpSQL(true); 
        $data->SetFieldValue("BAL_DOC_TYPE", $type);
        self::normalizeBalItems($db, $type, $data);

        $result = BalanceTxProcessor::Verify($db, $data, $allowNotEnough);

        return($result);
    }
}

?>