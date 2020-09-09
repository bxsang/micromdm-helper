<?php

function parsePlist($child) {
    $nodeName = $child->nodeName;
	switch($nodeName) {
		case 'dict':
			$d = new StdClass();
			$nodes = $child->childNodes;
			for ($i = 0; $i < $nodes->length; $i++) {
				if ($nodes->item($i)->nodeName == 'key'){
					$key = $nodes->item($i)->textContent;
					$i++;
					while ($nodes->item($i)->nodeName == "#text") {
						$i++;
					}
					$d->$key = parsePlist($nodes->item($i));
				}
			}
			return $d;
		    break;
        case 'array':
            $a = array();
            $nodes = $child->childNodes;
            for($i = 0; $i < $nodes->length; $i++){
                if ($nodes->item($i)->nodeName != "#text") {
                    $a[] = parsePlist($nodes->item($i));
                }
            }
            return $a;
            break;
        case ($nodeName == 'string' || $nodeName == 'data' || $nodeName == 'real' || $nodeName == 'integer'):
            return $child->textContent;
            break;
        case 'true':
            return true;
            break;
        case 'false':
            return false;
            break;
        default:
            return false;
	}
}

?>
