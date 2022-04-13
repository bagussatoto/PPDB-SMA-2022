<?php

class WhereTabs
{
    protected static function &getGridTabs($table)
    {
		global $strTableName;

		if(!$table)
            $tableName = $strTableName;
        else
            $tableName = $table;

        if (GetEntityType($tableName) === "")
            return false;

        $pSet = new ProjectSettings($tableName);

        if(!$pSet->isExistsTableKey(".arrGridTabs"))
            $pSet->_tableData[".arrGridTabs"] = $pSet->getDefaultValueByKey("arrGridTabs");
        return $pSet->_tableData[".arrGridTabs"];
    }

    protected static function &getGridTab($table, $id)
    {
        $gridTabs = &WhereTabs::getGridTabs($table);
        if ($gridTabs === false)
            return false;

        foreach ($gridTabs as &$tab) {
            if ($tab["tabId"] === $id)
                return $tab;
        }
        return false;
    }

    public static function addTab($table, $where, $title, $id)
    {
        $gridTabs = &WhereTabs::getGridTabs($table);
        if ($gridTabs === false)
            return false;

        foreach ($gridTabs as $tab) {
            if ($tab["tabId"] === $id)
                return false;
        }

        $gridTabs[] = array(
            'tabId' => $id,
            'name' => $title,
            'nameType' => "Text",
            'where' => $where,
            'showRowCount' => false,
            'hideEmpty' => false,
        );

        return true;
    }

    public static function deleteTab($table, $id)
    {
        $gridTabs = &WhereTabs::getGridTabs($table);
        if ($gridTabs === false)
            return false;

        foreach ($gridTabs as $key => $tab) {
            if ($tab["tabId"] === $id) {
                unset($gridTabs[$key]);
                break;
            }
        }
		return true;
    }

    public static function setTabTitle($table, $id, $title)
    {
        $tab = &WhereTabs::getGridTab($table, $id);

        if ($tab) {
            $tab['name'] = $title;
            $tab['nameType'] = "Text";
            return true;
        }
        return false;
    }

    public static function setTabWhere($table, $id, $where)
    {
        $tab = &WhereTabs::getGridTab($table, $id);

        if ($tab) {
            $tab['where'] = $where;
            return true;
        }
        return false;
    }
}
