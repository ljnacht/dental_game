<?php



class HtmlUtilities{
	function selectMultiControlFromDb($controlID, $size, $sql, $idVal, $optionVal, $selectedListSql, $selectedID){
		$select = "<select class='formElement' id='" . $controlID . "' name='" . $controlID . "' size='" . $size . "' multiple='true'>";
		$db = new DbUtilities;
		$itemList = $db->getDataset($sql);
		$selectedList = $db->getDataset($selectedListSql);
		$isSelected = "";
		foreach($itemList as &$row){
			$isSelected = "";
			foreach($selectedList as &$selectedItemRow){
				if($row[$idVal] == $selectedItemRow[$selectedID]){
					$isSelected = " selected ";
				}	
			}
			$select = $select . "<option " . $isSelected . " value='" . $row[$idVal] . "'>" . $row[$optionVal] . "</option>";	
	    }
		$select = $select . "</select>";
		
		return $select;
	}
	
	function selectMultiControlFromNumberList($controlID, $size, $startNum, $endNum, $selectedListSql, $selectedID){
		$select = "<select class='formElement' id='" . $controlID . "' name='" . $controlID . "' size='" . $size . "' multiple='true'>";
		$db = new DbUtilities;
		$selectedList = $db->getDataset($selectedListSql);
		$isSelected = "";
		for($i=$startNum; $i<=$endNum; $i++){
			$isSelected = "";
			foreach($selectedList as &$selectedItemRow){
				if($i == $selectedItemRow[$selectedID]){
					$isSelected = " selected ";
				}	
			}
			$select = $select . "<option " . $isSelected . " value='" . $i . "'>" . $i . "</option>";	
	    }
		$select = $select . "</select>";
		
		return $select;
	}
	
	function selectControlFromArray($controlID, $arr, $selectedVal){
		$select = "<select class='formElement' id='" . $controlID . "' name='" . $controlID . "'>";
		$isSelected = "";
		for($i=0; $i<count($arr); $i++){
			$isSelected = "";
			if($arr[$i] == $selectedVal){
				$isSelected = " selected ";
			}	
			$select = $select . "<option " . $isSelected . " value='" . $arr[$i] . "'>" . $arr[$i] . "</option>";	
	    }
		$select = $select . "</select>";
		
		return $select;
	}
	
	function displaySelectedValList($sql, $columnName){	
		$list = "<ol>";	
		$db = new DbUtilities;
		$itemList = $db->getDataset($sql);
		foreach($itemList as &$row){
			$list .= "<li>" . $row[$columnName] . "</li>";
	    }
		return $list . "</ol>";
	}
	
	function displayMonthSelectList($controlID, $selectedMonth){
		$select = "<select id='" . $controlID . "' name='" . $controlID . "'>";
		for($i=1;$i<=12;$i++){
			$select .= "<option value='" . $i . "'";
			if($selectedMonth == $i){
				$select .= " selected ";
			}
			$select .= ">" . date("F", mktime(0, 0, 0, $i, 10)) . "</option>";
		}
		$select .= "</select>";
		return $select;
	}

	

}


?>