/**
 * Plugin's jquery functions
 * Version 2.13
 */
 
 /** Get the type of the map: roadmap, satellite, terrain or hybrid**/
function getMapType(string) {
    var mapType;
    switch (string.toUpperCase()) {
    case 'ROADMAP':
        mapType = google.maps.MapTypeId.ROADMAP;
        break;
    case 'SATELLITE':
        mapType = google.maps.MapTypeId.SATELLITE;
        break;
    case 'TERRAIN':
        mapType = google.maps.MapTypeId.TERRAIN;
        break;
    case 'HYBRID':
        mapType = google.maps.MapTypeId.HYBRID;
        break;
    default:
        mapType = google.maps.MapTypeId.ROADMAP
    }
    return mapType
}
/** Init the map color picker**/
function initColorPicker() {
    var colorPicker = jQuery('#color-picker');
    colorPicker.hide();
    jQuery('#mapColor').click(function (event) {
        var input = jQuery(this);
        jQuery.farbtastic(jQuery(colorPicker), function (color) {
            input.val(color).css('background', color);
            input.trigger('change');
            jQuery("input[id=colorType]:checked").val(color);
            updateMap();
        });
        colorPicker.show();
        event.preventDefault();
        jQuery(document).mousedown(function () {
            jQuery(colorPicker).hide();
        })
    })
}
/** Select the text inside the given element **/
function selectText(element) {
    var doc = document
        , text = doc.getElementById(element)
        , range, selection
    ;    
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();        
        range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}

/** Update the map live preview according to settings **/
function updateMap(pluginurl) {
    var address = jQuery("#address").val();
    var description = jQuery("#pdescription").val();
    if (jQuery("#pdescription").val().trim().length == 0) {
        jQuery("#pdescription").val(jQuery("#address").val())
    }
    if (jQuery("#center").val().trim().length == 0) {
        var lat = null;
        var lng = null
    } else {
        var center = jQuery("#center").val().split(",");
        var lat = center[0];
        var lng = center[1]
    }
    if (jQuery("#iconurl").val().trim().length == 0) {
        var icon = jQuery("input[id=color]:checked").val();
        var iconUrl = pluginurl + "/responsive-maps-plugin/includes/icons/" + icon + ".png";
    } 

    var shadowUrl = pluginurl + "/responsive-maps-plugin/includes/icons/shadow.png";
    var mapColor = jQuery("#mapColor").val();
    var colorType = jQuery("input[id=colorType]:checked").val()
    
    // If color is not set, show the default map style. If color is set to 'bw' show black&white map. Otherwise, if color given, set the map color to the given color.
    var styles = '';
    if (colorType == 'bw') {
        styles = '[{ "stylers": [{ "featureType": "all" }, { "saturation": -100 }, { "gamma": 0.50 }, {"lightness": 40 }]}]';
    } else if (colorType == 'classic') {
        styles = '';
    } else {
        colorType = mapColor;
        styles = '[{ "stylers": [{ "hue": "'+ mapColor + '" }]}]';
    }

    var zoom = parseInt(jQuery("select#zoom").val());
    var mapdiv = jQuery("#responsive_map");
    var maptype = getMapType(jQuery("select#type").val());
    var panControl = Boolean(jQuery("select#panControl").val());
    var zoomControl = Boolean(jQuery("select#zoomControl").val());
    var pdraggable = Boolean(jQuery("select#draggable").val());
    var prefresh = Boolean(jQuery("select#refresh").val());
    var pscrollwheel = Boolean(jQuery("select#scrollwheel").val());
    var mapTypeControl = Boolean(jQuery("select#typeControl").val());
    var scaleControl = Boolean(jQuery("select#scaleControl").val());
    var streetViewControl = Boolean(jQuery("select#streetControl").val());
    var width = jQuery("#width").val() + jQuery("select#widthm").val();
    var height = jQuery("#height").val() + jQuery("select#heightm").val();
    var pancontrol = (panControl == "") ? "no" : "yes";
    var zoomcontrol = (zoomControl == "") ? "no" : "yes";
    var draggable = (pdraggable == "") ? "no" : "yes";
    var refresh = (prefresh == "") ? "no" : "yes";
    var scrollwheel = (pscrollwheel == "") ? "no" : "yes";
    var typecontrol = (mapTypeControl == "") ? "no" : "yes";
    var scalecontrol = (scaleControl == "") ? "no" : "yes";
    var streetcontrol = (streetViewControl == "") ? "no" : "yes";
    var popup = "no";
    var addresses = address.split("|");
    var descriptions = description.split("|");
    
    // The array with custom icons
    if (jQuery("#iconurl").val().trim().length != 0) {
        var icons = jQuery("#iconurl").val().split("|");
    }
    
    var markers = '[';
    for (var i = 0; i < addresses.length; i++) {
        var addr = addresses[i].replace(new RegExp("'", "g"), "\'");
        var descr = descriptions[i];
        var showPopup = false;
        if (addresses.length > 1) {
            popup = "no";
            jQuery("select#popup").val("")
        } else {
            var showPopup = Boolean(jQuery("select#popup").val());
            popup = (jQuery("select#popup").val() == "") ? "no" : "yes"
        } if (descr == null || descr.trim().length == 0) {
            descr = addr
        }
        descr = descr.replace(new RegExp("\"", "g"), "\'").replace(new RegExp("\n", "g"), " ");
        // Replace in the html code the {br} expression with < br >  tag
        descr =  descr.replace(new RegExp("{br}", "g"), "<br>"); 
        var directionstext = jQuery("#directions").val();
        
        // The custom icon
        if (icons == null || icons[i] == null || icons[i].trim() == "") {
            var icon = jQuery("input[id=color]:checked").val();
            var iconUrl = pluginurl + "/responsive-maps-plugin/includes/icons/" + icon + ".png";
        } else {
            icon = jQuery("#iconurl").val();
            iconUrl = icons[i];
        }
        
        var html = '<strong>' + descr + '<br><a target=\'_blank\' href=\'http://maps.google.com/?daddr=' + encodeURIComponent(addr).replace(new RegExp("'", "g"), "&#39;") + '\'>' + directionstext + '</a></strong>';
        if (i > 0) markers += ",";
        markers += '{' + '"address": "' + addr + '",' + '"html": "' + html + '",' + '"popup": ' + showPopup + ',' + '"flat": true,' + '"icon": {' + '"image": "' + iconUrl + '",' + '"iconsize": [50, 50],' + '"iconanchor": null,' + '"shadow": "' + shadowUrl + '",' + '"shadowsize": [50, 50],' + '"shadowanchor": null}}';
    }
    markers += ']';
    mapdiv.gMapResp({
        maptype: maptype,
        zoom: zoom,
        markers: jQuery.parseJSON(markers),
        panControl: panControl,
        zoomControl: zoomControl,
        draggable: pdraggable,
        mapTypeControl: mapTypeControl,
        scaleControl: scaleControl,
        streetViewControl: streetViewControl,
        overviewMapControl: true,
        styles: jQuery.parseJSON(styles),
        scrollwheel: pscrollwheel,
        latitude: lat,
        longitude: lng
    });
    var parsedDescription = jQuery("#pdescription").val().replace(new RegExp("\"", "g"), "\'").replace(new RegExp("<", "g"), "&lt;").replace(new RegExp(">", "g"), "&gt;");
    jQuery("#shortcode").html('[res_map address="' + address + '" description="' + parsedDescription + '" directionstext="' + directionstext + '" icon="' + icon + '" color="' + colorType + '" pancontrol="' + pancontrol + '" scalecontrol="' + scalecontrol + '" typecontrol="' + typecontrol + '" streetcontrol="' + streetcontrol + '" zoom="' + zoom + '" zoomcontrol="' + zoomcontrol + '" draggable="' + draggable + '" scrollwheel="' + scrollwheel + '" width="' + width + '" height="' + height + '" maptype="' + maptype + '" popup="' + popup + '" center="' + jQuery("#center").val() + '" refresh="' + refresh + '"]')
}