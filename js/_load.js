/*
 *
 */

// ---------------------------------------------------------------------------
function LoadJS( _file ) {
  document.writeln('<script type="text/javascript" src="'+_file+'"><\/script>');
}

// ---------------------------------------------------------------------------
function LoadStyle( _file ) {
  document.writeln('<link rel="stylesheet" type="text/css" href="'+_file+'" \/>');
}

// ---------------------------------------------------------------------------
var LoadedJSLibs = new Array();

// ---------------------------------------------------------------------------
function LoadJSLib( _lib, _ver ) {
  if (typeof _ver !== 'undefined') _lib += '/' + _ver + '/' + _lib;
  var Lib = 'js/' + _lib + '.js';
  if (!LoadedJSLibs[Lib]) {
    LoadJS(Lib);
    LoadedJSLibs[Lib] = true;
  }
}

// ---------------------------------------------------------------------------
// load libraries
// ---------------------------------------------------------------------------
LoadJSLib('prototype', '1.7');
LoadJSLib('prototypePlus', '1.7');
LoadJSLib('scriptaculous', '1.9.0');

var DialogJsPath = 'js/';
LoadJSLib('dialog');

var TabberRootDir = 'js/';
LoadJSLib('tabber');
document.write('<style type="text/css">.tabber{display:none;}<\/style>');

LoadJSLib('cookies');
LoadJSLib('string');

// ---------------------------------------------------------------------------
// load single scripts
// ---------------------------------------------------------------------------