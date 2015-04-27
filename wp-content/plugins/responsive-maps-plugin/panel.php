<?php
/**************************************************************
 *                                                            *
 *   Render the plugin's settings admin panel form            *
 *   Version: 2.13                                            *
 *   Author: greenline                                        *
 *   Profile: http://codecanyon.net/user/greenline            *
 *                                                            *
 **************************************************************/
function rsmaps_render_form() {
    ?>
    <div class="wrap">

    <!-- Beginning of the Responsive Styled Gooogle Maps helper form -->
    <form method="post" action="">

          <script type="text/javascript">
          // At page load, initialize the color picker and also update the mnap preview.
          var pluginurl = "<?php echo plugins_url(); ?>";
          jQuery(document).ready(function($) {
              initColorPicker();
              updateMap(pluginurl);
          });
                
          </script>
      
            <!-- Table Structure Containing shorcode parameters -->
            <table border="0" style="table-layout: fixed;" cellpadding="5">
                <!-- Logo and plugin title -->
                <tr>
                    <td scope="row">
                        <a href="http://codecanyon.net/user/greenline" target="_blank">
                            <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/img/logo.png" id="logo" width="40" height="40">
                        </a>
                    </td>
                    <td width="300px" valign="top">
                        <h2>Responsive Styled Google Maps - Helper</h2>
                    </td>
                </tr>
            
                <!-- Address, the map and the shortcode -->
                <tr>
                    <td scope="row" width="170px" align="left" valign="top">Address or addresses</td>
                    <td width="300px" valign="top">
                        <textarea name="address" id="address" rows="3" cols="90" type='textarea' onblur="updateMap(pluginurl)">Yeronga QLD 4104, Australia</textarea><br>
                        <span class="info">For multiple markers use: address1 | address2 | address3  OR lat1, long1 | lat2, long2 | lat3, long3</span>
                    </td>
                    <td rowspan="15" valign="top" valign="top" width="500px">
                        <div id="responsive_map" class="responsive-map" style="height:500px;width:500px;"></div>
                        <span class="info">Note: In this preview, width and height remain unchanged.</span>
                        <br><br><div class="preheader">COPY IN YOUR POST / PAGE /WIDGET THE SHORTCODE GENERATED :</div>
                        <pre id="shortcode" onClick="selectText('shortcode')"></pre>
                        <!-- Update map button -->
                        <a href="javascript: updateMap(pluginurl);" class="glbutton">UPDATE MAP</a>
                    </td>
                </tr>

                <!-- Marker Description -->
                <tr>
                    <td scope="row" align="left" valign="top">Marker(s) description(s)</td>
                    <td valign="top">
                        <textarea name="pdescription" id="pdescription" rows="3" cols="90" type='textarea' onblur="updateMap(pluginurl)"><img src="http://yava.ro/images/company.png"> {br} Yeronga QLD 4104, Australia {br} Phone:  0040 752 235 756</textarea><br>
                        <span class="info">For multiple markers use: description1 | description2 | description3 and {br} for a new line</span><br>
                    </td>
                </tr>

                <!-- Directions text -->
                <tr>
                    <td scope="row" align="left" valign="top">Directions text</td>
                    <td>
                        <input type="text" size="93" name="directions" id="directions" value="(directions to our address)" onblur="updateMap(pluginurl)"  />
                        <span class="info">No HTML code, just text.</span><br>
                    </td>

                </tr>

                <!-- Marker icon color -->
                <tr>
                    <td scope="row" align="left" valign="top">Marker(s) icon</td>
                    <td valign="bottom">
                        <label><input name="color" id="color" type="radio" value="black" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/black.png"> </label>
                        <label><input name="color" id="color" type="radio" value="blue" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/blue.png"> </label>
                        <label><input name="color" id="color" type="radio" value="gray" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/gray.png"> </label>
                        <label><input name="color" id="color" type="radio" value="green" onclick="updateMap(pluginurl)" checked/> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/green.png"> </label>
                        <label><input name="color" id="color" type="radio" value="magenta" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/magenta.png"> </label>
                        <label><input name="color" id="color" type="radio" value="orange" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/orange.png"> </label>
                        <label><input name="color" id="color" type="radio" value="purple" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/purple.png"> </label>
                        <label><input name="color" id="color" type="radio" value="red" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/red.png"> </label>
                        <label><input name="color" id="color" type="radio" value="white" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/white.png"> </label>
                        <label><input name="color" id="color" type="radio" value="yellow" onclick="updateMap(pluginurl)" /> <img src="<?php echo plugins_url(); ?>/responsive-maps-plugin/includes/icons/yellow.png"> </label>
                    </td>
                </tr>
                <!-- Marker icon url -->
                <tr>
                    <tr>
                    <td scope="row" align="left" valign="top">Or custom icon(s)</td>
                    <td>
                        <textarea name="iconurl" id="iconurl" rows="3" cols="90" type='textarea' onblur="updateMap(pluginurl)">http://yava.ro/icons/car.png</textarea><br>
                        <span class="info">For multiple icons, separate the links with | (pipe) symbol like this: <br>http://yava.ro/icons/car.png | http://yava.ro/icons/sailboat.png</span>
                    </td>
                </tr>
                </tr>

                <!-- Map color -->
                <tr>
                    <td scope="row" align="left" valign="middle">Map color</td>
                    <td valign="top">
                        <label><input name="colorType" id="colorType" type="radio" value="bw" onclick="updateMap(pluginurl)" checked /> black & white </label>
                        <label><input name="colorType" id="colorType" type="radio" value="classic" onclick="updateMap(pluginurl)" /> classic </label>                       
                        <label><input name="colorType" id="colorType" type="radio" value="colored" style="background-color: #efe725 !important;" onclick="updateMap(pluginurl)" /> colored </label>
                        <input id="mapColor" name="mapColor" type="text" size="23" value="#efe725" onblur="updateMap(pluginurl);" onClick="javascript: jQuery('input:radio[id=colorType]')[2].checked = true;updateMap(pluginurl);" />
                        <div id="color-picker" rel="mapColor"></div>
                    </td>
                </tr>

                <!-- Controls -->
                <tr>
                    <td scope="row" align="left">Controls</td>
                    <td>
                        Pan control 
                        <select name='panControl' id='panControl' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                         Scale control
                        <select name='scaleControl' id='scaleControl' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                        Type control
                        <select name='typeControl' id='typeControl' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                        Street control
                        <select name='streetControl' id='streetControl' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                    </td>
                </tr>

                <!-- Zoom -->
                <tr>
                    <td scope="row" align="left">Zoom</td>
                    <td>
                        Zoom level &nbsp;
                        <select name="zoom" id="zoom" onchange="updateMap(pluginurl)">
                            <option value='1'>1</option>
                            <option value='2'>2</option>
                            <option value='3'>3</option>
                            <option value='4'>4</option>
                            <option value='5'>5</option>
                            <option value='6'>6</option>
                            <option value='7'>7</option>
                            <option value='8'>8</option>
                            <option value='9'>9</option>
                            <option value='10'>10</option>
                            <option value='11'>11</option>
                            <option value='12'>12</option>
                            <option value='13'>13</option>
                            <option value='14' selected>14</option>
                            <option value='15'>15</option>
                            <option value='16'>16</option>
                            <option value='17'>17</option>
                            <option value='18'>18</option>
                            <option value='19'>19</option>
                            </select>
                        Zoom control
                        <select name='zoomControl' id='zoomControl' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                        Draggable
                        <select name='draggable' id='draggable' onchange="updateMap(pluginurl)">>
                            <option value=''>no</option>
                            <option value='true' selected>yes</option>
                        </select>
                        Scrollwheel
                        <select name='scrollwheel' id='scrollwheel' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                        </td>
                </tr>

                <!-- Width -->
                <tr>
                    <td scope="row" align="left">Map width</td>
                    <td>
                        <input type="text" size="6" name="width" id="width" value="100" onblur="updateMap(pluginurl)"/>
                        <select name='widthm' id='widthm' onchange="updateMap(pluginurl)">
                            <option value='%' selected>%</option>
                            <option value='px'>px</option>
                        </select>
                        <span class="info">Set to 100% to make the map responsive.</span>
                    </td>
                </tr>

                <!-- Height -->
                <tr>
                    <td scope="row" align="left">Map height</td>
                    <td>
                        <input type="text" size="6" name="height" id="height" value="500" onblur="updateMap(pluginurl)"/>
                        <select name='heightm' id='heightm' onchange="updateMap(pluginurl)">
                            <option value='%'>%</option>
                            <option value='px' selected>px</option>
                        </select>
                        <span class="info">(in px or %) It's best to give width in % and height in px.</span>
                    </td>
                </tr>

                <!-- Map Type -->
                <tr>
                    <td scope="row" align="left">Map type</td>
                    <td>
                        <select name='type' id="type" style="width:113px;" onchange="updateMap(pluginurl)">
                            <option value='roadmap' selected>roadmap</option>
                            <option value='satellite'>satellite</option>
                            <option value='terrain'>terrain</option>
                            <option value='hybrid'>hybrid</option>
                        </select>
                        <span class="info">Possible values: roadmap, satellite, terrain or hybrid</span>
                    </td>
                </tr>

                <!-- Popup -->
                <tr>
                    <td scope="row" align="left">Popup window</td>
                    <td>
                        <select name='popup' id="popup" style="width:113px;" onchange="updateMap(pluginurl)">
                            <option value=''>hidden</option>
                            <option value='true' selected>visible</option>
                        </select>
                        <span class="info">The popup window which appears when a marker is clicked.</span>
                    </td>
                </tr>

                <!-- Map center -->
                <tr>
                    <td scope="row" width="200px" align="left">Center map to</td>
                    <td width="300px">
                        <input type="text" size="40" name="center" id="center" value="" onblur="updateMap(pluginurl);"/>
                        <span class="info">latitude, longitude i.e. 38.980288, 22.145996 </span>
                    </td>
                </tr>
                
                <!-- Map refresh (when window is scaled) -->
                <tr>
                    <td scope="row" width="200px" align="left">Refresh map</td>
                    <td width="300px">
                        <select name='refresh' id='refresh' onchange="updateMap(pluginurl)">>
                            <option value='' selected>no</option>
                            <option value='true'>yes</option>
                        </select>
                        <span class="info"> select "yes" if the map should be refreshed (re-centered) when the window is scaled </span>
                    </td>
                </tr>
                
                

            </table>
        </form>
    </div>

    <style type="text/css">
    pre {
        white-space: pre-wrap;       /* css-3 */
        white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
        white-space: -pre-wrap;      /* Opera 4-6 */
        white-space: -o-pre-wrap;    /* Opera 7 */
        word-wrap: break-word;       /* Internet Explorer 5.5+ */
        background-color: #2F343A;
        color: #eeeeee;
        margin-top: 1px;
        margin-bottom: 1px;
        padding: 10px;
        cursor: text;
    }
    .preheader {
        background-color: #2BB4A0;
        color: #ffffff;
        padding: 10px;
        font-family: arial;
        font-weight: bold;
        border-bottom: 1px solid #CCCCCC;
    }
    select#style {
        background-color: #eeeeee;
        border: 1px solid #DD7127;
        font-weight: bold;
    }
    .info {
        font-style: italic;
        color: #555555;
        font-size: 11px;
    }
    img#logo {
        padding: 1px;
    }
    /* Button */
    .glbutton:link {
        -moz-box-shadow: inset 0px 1px 0px 0px #ffffff;
        -webkit-box-shadow: inset 0px 1px 0px 0px #ffffff;
        box-shadow: inset 0px 1px 0px 0px #ffffff;
        background-color: #2bb49f;
        -moz-border-radius: 6px;
        -webkit-border-radius: 6px;
        border-radius: 6px;
        border: 1px solid #dcdcdc;
        display: inline-block;
        color: #ffffff;
        font-family: arial;
        font-size: 16px;
        font-weight: bold;
        padding: 6px 10px 4px 10px;
        margin: 10px 6px 7px 0;
        text-decoration: none;
    }
    .glbutton:hover {
        background-color: #77b0a8;
        color: #ffffff !important;
    }
    .glbutton:active, .glbutton:visited {
        position:relative;
        top:1px;
        color: #ffffff !important;
    }
    /* Marker settings */
    .gm-style-iw {
        overflow-y: hidden !important;
        overflow-x: hidden !important;
    }
    .gm-style a:link, 
    .gm-style a:visited, 
    .gm-style a:hover, 
    .gm-style a:active {
        text-decoration: underline !important;
        color: #000000 !important;
    }
    .gm-style img {
        border: 0;
        max-width: none !important;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
        box-shadow: none !important;
    }
    </style>
<?php
}
?>