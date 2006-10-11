// --------------------
// Plugin: DisableCache
// --------------------
// Version: 1.0
// Date: 10 Oct 2006
//
// Kills the cache for every page when enabled.
//
// Event:
// OnLoadWebDocument
//

$modx->documentObject['cacheable'] = 0;