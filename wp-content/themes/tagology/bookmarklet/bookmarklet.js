
/* savory javascript */

if (document.getElementById('_savory_overlay')) {
  var e = document.getElementById('_savory_overlay');
  e.parentNode.removeChild(e);
}

function OnSubtreeModified () {
    alert ("The subtree that belongs to the container node has been modified.");
}
        
var SavorySidebar = (typeof(SavorySidebar) == 'undefined') ? {} : SavorySidebar;

SavorySidebar.setAttribute = function(e, k, v) {
  if (k == "class") {
    e.setAttribute("className", v); // set both "class" and "className"
  }
  return e.setAttribute(k, v);
};

SavorySidebar.createElement = function(e, attrs) {
  var el = document.createElement(e);
  for (var k in attrs) {
    if (k == "text") {
      el.appendChild(document.createTextNode(attrs[k]));
    } else {
      SavorySidebar.setAttribute(el, k, attrs[k]);
    }
  }
  return el;
};

var cancelSrc = src + '/wp-content/themes/savory/bookmarklet/arrow_left.png';

var overlay = SavorySidebar.createElement('div', {'id': '_savory_overlay', 'style': 'position:absolute;top:0px;left:0px;width:100%;z-index:999999;border-bottom:1px solid #ccc;'});
var content = SavorySidebar.createElement('div', {'id': "_savory_content",'style': 'text-align:left;width:100%;background:#eee;'});

var closeDiv = SavorySidebar.createElement('div', {'id': "_savory_close",'style': 'border-right:1px solid white;float:left;height:40px;width:22px;'});
var closeImg = SavorySidebar.createElement('img', {'style': 'border:0;margin-top:5px;padding:0 3px;','src':cancelSrc});
var closeA = SavorySidebar.createElement('a', {'style': '','href':'javascript:document.getElementById("_savory_overlay").style.display="none";void(0);'});
// var closeA = SavorySidebar.createElement('a', {'text':'Close','style': 'width:100%;','href':'javascript:document.getElementById("_savory_overlay").style.display="none";void(0);'});

var frameSrc = src + '/bookmarklet/panel/?url='+encodeURIComponent(window.location.href)+'&title='+encodeURIComponent(document.title);
//src = src + '/bookmarklet/panel/'+encodeURIComponent(window.location.href)+'/'+encodeURIComponent(document.title);

var iframe = SavorySidebar.createElement('iframe', {'id':'_savory_iframe','scrolling':'no','style':'padding-top:6px;padding-left:5px;border-left:1px solid #aaa;width:80%;height:40px;overflow:hide;background:#eee;', "src": frameSrc, "allowTransparency": "true", "frameBorder" : 0 });

closeA.appendChild(closeImg);
closeDiv.appendChild(closeA);

content.appendChild(closeDiv);
content.appendChild(iframe);
overlay.appendChild(content);
document.body.appendChild(overlay);
self.scrollTo(0, 0); // to the top







