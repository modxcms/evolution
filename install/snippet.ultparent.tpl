// UltimateParent - snippet for MODx 0.9x
// Travels up the document tree from the current document
// to return the "ultimate" parent
// March 2006 - sottwell@sottwell.com
// Bug fix Sept 17, 2006 - Jason Coward <modx@opengeek.com>
// Released to the Public Domain, use as you like
// arguments:
// &id      - the id of the document whose parent you want to find
// &top     - the top of the search 
// examples:
// [[GetUltimateParent?id=`45`&top=`6`]]
// will find the first parent of document #45 under document #6
// if id == 0 or top == id, will return id.
// you can use this as the startDoc for DropMenu to create specific submenus.

$top = isset($top)?$top:0;
$id = isset($id)?$id:$modx->documentIdentifier;
if($id==$top || $id==0) { return $id; }
$pid['id'] = $modx->getParent($id,1,'id');
if($pid['id'] == $top) { return $id; }
while ($pid['id'] != $top) {
    $id = $pid['id'];
    $pid = $modx->getParent($id,1,'id');
    if($pid['id'] == $top) { return $id; }
}
return 0; // if all else fails
