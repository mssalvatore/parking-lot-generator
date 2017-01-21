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
                            <th class="feature_area">%%TITLE%%</th>
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
                                        <td class="ralign owner">%%OWNER%%</td>
FOT;

const FEATURE_SET_TEMPLATE = <<<FST
                                        <td style="padding-left:%%PADDING_LEFT%%;padding-right:%%PADDING_RIGHT%%">
                                            <table class="feature_set">
                                                <tr>
                                                    <td class="feature_set %%BG_STYLE%%">
                                                        <div class="td_feature_set">
                                                            <div class="feature_set_name">%%TITLE%%</div>
                                                            <div class="number_of_features">(%%NUM_FEATURES%%)</div>
                                                            <div class="percent_complete">%%PERCENT_COMPLETE%%%</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="progress_date">
                                                        <progress value="%%PERCENT_COMPLETE%%" max="100"></progress>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="progress_date">%%DUE_DATE%%</td>
                                                </tr>
                                            </table>
                                        </td>
FST;

}
