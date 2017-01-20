<?php

namespace ParkingLot;

class Templates
{

const FRAME = <<<FRAME
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="./style.css">
    </head>
    <body>
        %%FEATURE_AREA_ROWS%%
    </body>
</html>
FRAME;

const FEATURE_AREA_ROW_TEMPLATE = <<<FAR
<table>
            <tr>
                %%FEATURE_AREAS%%
            </tr>
        </table>
FAR;

const FEATURE_AREA_TEMPLATE = <<<FAT
                <td>
                    <table class=boxed style="width:%%WIDTH%%">
                        <tr>
                            <th>%%TITLE%%</th>
                        </tr>
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        %%FEATURE_SET_OWNERS%%
                                    </tr>
                                    <tr>
                                        %%FEATURE_SETS%%
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
FAT;

const FEATURE_SET_OWNER_TEMPLATE = <<<FOT
                                        <td style="padding-left:%%PADDING_LEFT%%;padding-right:%%PADDING_RIGHT%%" class="ralign">%%OWNER%%</td>
FOT;

const FEATURE_SET_TEMPLATE = <<<FST
                                        <td style="padding-left:%%PADDING_LEFT%%;padding-right:%%PADDING_RIGHT%%">
                                            <table class="feature_set">
                                                <tr>
                                                    <td class="feature_set" bgcolor="yellow">%%TITLE%%<br><br>(%%NUM_FEATURES%%)<br><br>%%PERCENT_COMPLETE%%%</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <progress value="%%PERCENT_COMPLETE%%" max="100"></progress>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>%%DUE_DATE%%</td>
                                                </tr>
                                            </table>
                                        </td>
FST;

}
