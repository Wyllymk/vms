<?php
/**
 * Template part for displaying charts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visitor_Management_System
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>
<!-- ====== Chart Four Start -->
<div
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Impression &amp; Visitor Traffic
            </h3>
            <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                Jun 1, 2024 - Dec 1, 2025
            </p>
        </div>

        <div class="flex flex-row-reverse items-center justify-end gap-0.5 sm:flex-col sm:items-start">
            <div class="flex flex-row-reverse items-center gap-3 sm:flex-row sm:gap-2">
                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                    1,200
                </h4>

                <span
                    class="flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    +7.96%
                </span>
            </div>

            <span class="text-gray-500 text-theme-xs dark:text-gray-400">
                Total Visits
            </span>
        </div>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <div id="chartEight" class="-ml-4 min-w-[1000px] pl-2 xl:min-w-full" style="min-height: 325px;">
            <div id="apexchartsdolmcscb" class="apexcharts-canvas apexchartsdolmcscb apexcharts-theme-"
                style="width: 675px; height: 310px;"><svg id="SvgjsSvg1768" width="675" height="310"
                    xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable hovering-zoom"
                    xmlns:data="ApexChartsNS" transform="translate(0, 0)">
                    <foreignObject x="0" y="0" width="675" height="310">
                        <div style="position: relative; height: 100%; width: 100%;"
                            xmlns="http://www.w3.org/1999/xhtml">
                            <div class="apexcharts-legend" style="max-height: 155px;"></div>
                        </div>
                    </foreignObject>
                    <rect id="SvgjsRect1773" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0"
                        stroke="none" stroke-dasharray="0" fill="#fefefe"></rect>
                    <g id="SvgjsG1778" class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                    <g id="SvgjsG1779" class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)"></g>
                    <g id="SvgjsG1851" class="apexcharts-yaxis" rel="0" transform="translate(18.133331298828125, 0)">
                        <g id="SvgjsG1852" class="apexcharts-yaxis-texts-g"><text id="SvgjsText1854"
                                font-family="Outfit, sans-serif" x="20" y="33.666666666666664" text-anchor="end"
                                dominant-baseline="auto" font-size="11px" font-weight="400" fill="#373d3f"
                                class="apexcharts-text apexcharts-yaxis-label "
                                style="font-family: Outfit, sans-serif;">
                                <tspan id="SvgjsTspan1855">250</tspan>
                                <title>250</title>
                            </text><text id="SvgjsText1857" font-family="Outfit, sans-serif" x="20"
                                y="81.81266666666667" text-anchor="end" dominant-baseline="auto" font-size="11px"
                                font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                style="font-family: Outfit, sans-serif;">
                                <tspan id="SvgjsTspan1858">200</tspan>
                                <title>200</title>
                            </text><text id="SvgjsText1860" font-family="Outfit, sans-serif" x="20"
                                y="129.95866666666666" text-anchor="end" dominant-baseline="auto" font-size="11px"
                                font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                style="font-family: Outfit, sans-serif;">
                                <tspan id="SvgjsTspan1861">150</tspan>
                                <title>150</title>
                            </text><text id="SvgjsText1863" font-family="Outfit, sans-serif" x="20"
                                y="178.10466666666667" text-anchor="end" dominant-baseline="auto" font-size="11px"
                                font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                style="font-family: Outfit, sans-serif;">
                                <tspan id="SvgjsTspan1864">100</tspan>
                                <title>100</title>
                            </text><text id="SvgjsText1866" font-family="Outfit, sans-serif" x="20"
                                y="226.2506666666667" text-anchor="end" dominant-baseline="auto" font-size="11px"
                                font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                style="font-family: Outfit, sans-serif;">
                                <tspan id="SvgjsTspan1867">50</tspan>
                                <title>50</title>
                            </text><text id="SvgjsText1869" font-family="Outfit, sans-serif" x="20"
                                y="274.3966666666667" text-anchor="end" dominant-baseline="auto" font-size="11px"
                                font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-yaxis-label "
                                style="font-family: Outfit, sans-serif;">
                                <tspan id="SvgjsTspan1870">0</tspan>
                                <title>0</title>
                            </text></g>
                    </g>
                    <g id="SvgjsG1770" class="apexcharts-inner apexcharts-graphical"
                        transform="translate(48.133331298828125, 30)">
                        <defs id="SvgjsDefs1769">
                            <clipPath id="gridRectMaskdolmcscb">
                                <rect id="SvgjsRect1775" width="605.2750024795532" height="240.73000000000002" x="0"
                                    y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                    fill="#fff"></rect>
                            </clipPath>
                            <clipPath id="gridRectBarMaskdolmcscb">
                                <rect id="SvgjsRect1776" width="611.2750024795532" height="246.73000000000002" x="-3"
                                    y="-3" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                    fill="#fff"></rect>
                            </clipPath>
                            <clipPath id="gridRectMarkerMaskdolmcscb">
                                <rect id="SvgjsRect1777" width="605.2750024795532" height="240.73000000000002" x="0"
                                    y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0"
                                    fill="#fff"></rect>
                            </clipPath>
                            <clipPath id="forecastMaskdolmcscb"></clipPath>
                            <clipPath id="nonForecastMaskdolmcscb"></clipPath>
                            <linearGradient id="SvgjsLinearGradient1784" x1="0" y1="0" x2="0" y2="1">
                                <stop id="SvgjsStop1785" stop-opacity="0.55" stop-color="rgba(70,95,255,0.55)"
                                    offset="0"></stop>
                                <stop id="SvgjsStop1786" stop-opacity="0" stop-color="rgba(163,175,255,0)" offset="1">
                                </stop>
                                <stop id="SvgjsStop1787" stop-opacity="0" stop-color="rgba(163,175,255,0)" offset="1">
                                </stop>
                            </linearGradient>
                            <linearGradient id="SvgjsLinearGradient1793" x1="0" y1="0" x2="0" y2="1">
                                <stop id="SvgjsStop1794" stop-opacity="0.55" stop-color="rgba(156,185,255,0.55)"
                                    offset="0"></stop>
                                <stop id="SvgjsStop1795" stop-opacity="0" stop-color="rgba(206,220,255,0)" offset="1">
                                </stop>
                                <stop id="SvgjsStop1796" stop-opacity="0" stop-color="rgba(206,220,255,0)" offset="1">
                                </stop>
                            </linearGradient>
                        </defs>
                        <line id="SvgjsLine1774" x1="109.55000045082785" y1="0" x2="109.55000045082785"
                            y2="240.73000000000002" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt"
                            class="apexcharts-xcrosshairs" x="109.55000045082785" y="0" width="1"
                            height="240.73000000000002" fill="#b1b9c4" filter="none" fill-opacity="0.9"
                            stroke-width="1"></line>
                        <g id="SvgjsG1799" class="apexcharts-grid">
                            <g id="SvgjsG1800" class="apexcharts-gridlines-horizontal">
                                <line id="SvgjsLine1804" x1="0" y1="48.146" x2="605.2750024795532" y2="48.146"
                                    stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                    class="apexcharts-gridline"></line>
                                <line id="SvgjsLine1805" x1="0" y1="96.292" x2="605.2750024795532" y2="96.292"
                                    stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                    class="apexcharts-gridline"></line>
                                <line id="SvgjsLine1806" x1="0" y1="144.438" x2="605.2750024795532" y2="144.438"
                                    stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                    class="apexcharts-gridline"></line>
                                <line id="SvgjsLine1807" x1="0" y1="192.584" x2="605.2750024795532" y2="192.584"
                                    stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                    class="apexcharts-gridline"></line>
                            </g>
                            <g id="SvgjsG1801" class="apexcharts-gridlines-vertical"></g>
                            <line id="SvgjsLine1810" x1="0" y1="240.73000000000002" x2="605.2750024795532"
                                y2="240.73000000000002" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt">
                            </line>
                            <line id="SvgjsLine1809" x1="0" y1="1" x2="0" y2="240.73000000000002" stroke="transparent"
                                stroke-dasharray="0" stroke-linecap="butt"></line>
                        </g>
                        <g id="SvgjsG1802" class="apexcharts-grid-borders">
                            <line id="SvgjsLine1803" x1="0" y1="0" x2="605.2750024795532" y2="0" stroke="#e0e0e0"
                                stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                            <line id="SvgjsLine1808" x1="0" y1="240.73000000000002" x2="605.2750024795532"
                                y2="240.73000000000002" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt"
                                class="apexcharts-gridline"></line>
                        </g>
                        <g id="SvgjsG1780" class="apexcharts-area-series apexcharts-plot-series">
                            <g id="SvgjsG1781" class="apexcharts-series" zIndex="0" seriesName="Sales"
                                data:longestSeries="true" rel="1" data:realIndex="0">
                                <path id="SvgjsPath1788"
                                    d="M0 67.40439999999998C19.258750078894874 67.40439999999998 35.766250146519056 57.775199999999984 55.025000225413926 57.775199999999984C74.2837503043088 57.775199999999984 90.79125037193297 77.03359999999998 110.05000045082785 77.03359999999998C129.30875052972272 77.03359999999998 145.8162505973469 86.6628 165.07500067624179 86.6628C184.33375075513666 86.6628 200.84125082276083 72.219 220.1000009016557 72.219C239.35875098055058 72.219 255.86625104817477 81.84819999999999 275.12500112706965 81.84819999999999C294.38375120596453 81.84819999999999 310.8912512735887 77.03359999999998 330.15000135248357 77.03359999999998C349.40875143137845 77.03359999999998 365.9162514990026 43.331399999999974 385.1750015778975 43.331399999999974C404.43375165679237 43.331399999999974 420.94125172441653 19.258399999999995 440.2000018033114 19.258399999999995C459.4587518822063 19.258399999999995 475.96625194983045 38.51679999999999 495.2250020287253 38.51679999999999C514.4837521076203 38.51679999999999 530.9912521752444 9.629199999999969 550.2500022541393 9.629199999999969C569.5087523330342 9.629199999999969 586.0162524006583 14.443799999999982 605.2750024795532 14.443799999999982C605.2750024795532 14.443799999999982 605.2750024795532 14.443799999999982 605.2750024795532 240.73000000000002L0 240.73000000000002L0 67.40439999999998C0 67.40439999999998 0 67.40439999999998 0 67.40439999999998 "
                                    fill="url(#SvgjsLinearGradient1784)" fill-opacity="1" stroke-opacity="1"
                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area"
                                    index="0" clip-path="url(#gridRectMaskdolmcscb)"
                                    pathTo="M 0 67.40439999999998C 19.258750078894874 67.40439999999998 35.766250146519056 57.775199999999984 55.025000225413926 57.775199999999984C 74.2837503043088 57.775199999999984 90.79125037193297 77.03359999999998 110.05000045082785 77.03359999999998C 129.30875052972272 77.03359999999998 145.8162505973469 86.6628 165.07500067624179 86.6628C 184.33375075513666 86.6628 200.84125082276083 72.219 220.1000009016557 72.219C 239.35875098055058 72.219 255.86625104817477 81.84819999999999 275.12500112706965 81.84819999999999C 294.38375120596453 81.84819999999999 310.8912512735887 77.03359999999998 330.15000135248357 77.03359999999998C 349.40875143137845 77.03359999999998 365.9162514990026 43.331399999999974 385.1750015778975 43.331399999999974C 404.43375165679237 43.331399999999974 420.94125172441653 19.258399999999995 440.2000018033114 19.258399999999995C 459.4587518822063 19.258399999999995 475.96625194983045 38.51679999999999 495.2250020287253 38.51679999999999C 514.4837521076203 38.51679999999999 530.9912521752444 9.629199999999969 550.2500022541393 9.629199999999969C 569.5087523330342 9.629199999999969 586.0162524006583 14.443799999999982 605.2750024795532 14.443799999999982C 605.2750024795532 14.443799999999982 605.2750024795532 14.443799999999982 605.2750024795532 240.73000000000002 L 0 240.73000000000002z"
                                    pathFrom="M 0 67.40439999999998C 19.258750078894874 67.40439999999998 35.766250146519056 57.775199999999984 55.025000225413926 57.775199999999984C 74.2837503043088 57.775199999999984 90.79125037193297 77.03359999999998 110.05000045082785 77.03359999999998C 129.30875052972272 77.03359999999998 145.8162505973469 86.6628 165.07500067624179 86.6628C 184.33375075513666 86.6628 200.84125082276083 72.219 220.1000009016557 72.219C 239.35875098055058 72.219 255.86625104817477 81.84819999999999 275.12500112706965 81.84819999999999C 294.38375120596453 81.84819999999999 310.8912512735887 77.03359999999998 330.15000135248357 77.03359999999998C 349.40875143137845 77.03359999999998 365.9162514990026 43.331399999999974 385.1750015778975 43.331399999999974C 404.43375165679237 43.331399999999974 420.94125172441653 19.258399999999995 440.2000018033114 19.258399999999995C 459.4587518822063 19.258399999999995 475.96625194983045 38.51679999999999 495.2250020287253 38.51679999999999C 514.4837521076203 38.51679999999999 530.9912521752444 9.629199999999969 550.2500022541393 9.629199999999969C 569.5087523330342 9.629199999999969 586.0162524006583 14.443799999999982 605.2750024795532 14.443799999999982C 605.2750024795532 14.443799999999982 605.2750024795532 14.443799999999982 605.2750024795532 240.73000000000002 L 0 240.73000000000002zz">
                                </path>
                                <path id="SvgjsPath1789"
                                    d="M0 67.40439999999998C19.258750078894874 67.40439999999998 35.766250146519056 57.775199999999984 55.025000225413926 57.775199999999984C74.2837503043088 57.775199999999984 90.79125037193297 77.03359999999998 110.05000045082785 77.03359999999998C129.30875052972272 77.03359999999998 145.8162505973469 86.6628 165.07500067624179 86.6628C184.33375075513666 86.6628 200.84125082276083 72.219 220.1000009016557 72.219C239.35875098055058 72.219 255.86625104817477 81.84819999999999 275.12500112706965 81.84819999999999C294.38375120596453 81.84819999999999 310.8912512735887 77.03359999999998 330.15000135248357 77.03359999999998C349.40875143137845 77.03359999999998 365.9162514990026 43.331399999999974 385.1750015778975 43.331399999999974C404.43375165679237 43.331399999999974 420.94125172441653 19.258399999999995 440.2000018033114 19.258399999999995C459.4587518822063 19.258399999999995 475.96625194983045 38.51679999999999 495.2250020287253 38.51679999999999C514.4837521076203 38.51679999999999 530.9912521752444 9.629199999999969 550.2500022541393 9.629199999999969C569.5087523330342 9.629199999999969 586.0162524006583 14.443799999999982 605.2750024795532 14.443799999999982 "
                                    fill="none" fill-opacity="1" stroke="#465fff" stroke-opacity="1"
                                    stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area"
                                    index="0" clip-path="url(#gridRectMaskdolmcscb)"
                                    pathTo="M 0 67.40439999999998C 19.258750078894874 67.40439999999998 35.766250146519056 57.775199999999984 55.025000225413926 57.775199999999984C 74.2837503043088 57.775199999999984 90.79125037193297 77.03359999999998 110.05000045082785 77.03359999999998C 129.30875052972272 77.03359999999998 145.8162505973469 86.6628 165.07500067624179 86.6628C 184.33375075513666 86.6628 200.84125082276083 72.219 220.1000009016557 72.219C 239.35875098055058 72.219 255.86625104817477 81.84819999999999 275.12500112706965 81.84819999999999C 294.38375120596453 81.84819999999999 310.8912512735887 77.03359999999998 330.15000135248357 77.03359999999998C 349.40875143137845 77.03359999999998 365.9162514990026 43.331399999999974 385.1750015778975 43.331399999999974C 404.43375165679237 43.331399999999974 420.94125172441653 19.258399999999995 440.2000018033114 19.258399999999995C 459.4587518822063 19.258399999999995 475.96625194983045 38.51679999999999 495.2250020287253 38.51679999999999C 514.4837521076203 38.51679999999999 530.9912521752444 9.629199999999969 550.2500022541393 9.629199999999969C 569.5087523330342 9.629199999999969 586.0162524006583 14.443799999999982 605.2750024795532 14.443799999999982"
                                    pathFrom="M 0 67.40439999999998C 19.258750078894874 67.40439999999998 35.766250146519056 57.775199999999984 55.025000225413926 57.775199999999984C 74.2837503043088 57.775199999999984 90.79125037193297 77.03359999999998 110.05000045082785 77.03359999999998C 129.30875052972272 77.03359999999998 145.8162505973469 86.6628 165.07500067624179 86.6628C 184.33375075513666 86.6628 200.84125082276083 72.219 220.1000009016557 72.219C 239.35875098055058 72.219 255.86625104817477 81.84819999999999 275.12500112706965 81.84819999999999C 294.38375120596453 81.84819999999999 310.8912512735887 77.03359999999998 330.15000135248357 77.03359999999998C 349.40875143137845 77.03359999999998 365.9162514990026 43.331399999999974 385.1750015778975 43.331399999999974C 404.43375165679237 43.331399999999974 420.94125172441653 19.258399999999995 440.2000018033114 19.258399999999995C 459.4587518822063 19.258399999999995 475.96625194983045 38.51679999999999 495.2250020287253 38.51679999999999C 514.4837521076203 38.51679999999999 530.9912521752444 9.629199999999969 550.2500022541393 9.629199999999969C 569.5087523330342 9.629199999999969 586.0162524006583 14.443799999999982 605.2750024795532 14.443799999999982"
                                    fill-rule="evenodd"></path>
                                <g id="SvgjsG1782"
                                    class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                    data:realIndex="0">
                                    <g class="apexcharts-series-markers">
                                        <path id="SvgjsPath1874" d="M 110.05000045082785, 0 
           m -0, 0 
           a 0,0 0 1,0 0,0 
           a 0,0 0 1,0 -0,0" fill="#465fff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                            stroke-linecap="butt" stroke-width="2" stroke-dasharray="0"
                                            cx="110.05000045082785" cy="0" shape="circle"
                                            class="apexcharts-marker wwjjw6b71 no-pointer-events"
                                            default-marker-size="0"></path>
                                    </g>
                                </g>
                            </g>
                            <g id="SvgjsG1790" class="apexcharts-series" zIndex="1" seriesName="Revenue"
                                data:longestSeries="true" rel="2" data:realIndex="1">
                                <path id="SvgjsPath1797"
                                    d="M0 202.21320000000003C19.258750078894874 202.21320000000003 35.766250146519056 211.8424 55.025000225413926 211.8424C74.2837503043088 211.8424 90.79125037193297 192.584 110.05000045082785 192.584C129.30875052972272 192.584 145.8162505973469 202.21320000000003 165.07500067624179 202.21320000000003C184.33375075513666 202.21320000000003 200.84125082276083 187.76940000000002 220.1000009016557 187.76940000000002C239.35875098055058 187.76940000000002 255.86625104817477 202.21320000000003 275.12500112706965 202.21320000000003C294.38375120596453 202.21320000000003 310.8912512735887 173.3256 330.15000135248357 173.3256C349.40875143137845 173.3256 365.9162514990026 144.438 385.1750015778975 144.438C404.43375165679237 144.438 420.94125172441653 134.80880000000002 440.2000018033114 134.80880000000002C459.4587518822063 134.80880000000002 475.96625194983045 125.1796 495.2250020287253 125.1796C514.4837521076203 125.1796 530.9912521752444 96.292 550.2500022541393 96.292C569.5087523330342 96.292 586.0162524006583 105.9212 605.2750024795532 105.9212C605.2750024795532 105.9212 605.2750024795532 105.9212 605.2750024795532 240.73000000000002L0 240.73000000000002L0 202.21320000000003C0 202.21320000000003 0 202.21320000000003 0 202.21320000000003 "
                                    fill="url(#SvgjsLinearGradient1793)" fill-opacity="1" stroke-opacity="1"
                                    stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area"
                                    index="1" clip-path="url(#gridRectMaskdolmcscb)"
                                    pathTo="M 0 202.21320000000003C 19.258750078894874 202.21320000000003 35.766250146519056 211.8424 55.025000225413926 211.8424C 74.2837503043088 211.8424 90.79125037193297 192.584 110.05000045082785 192.584C 129.30875052972272 192.584 145.8162505973469 202.21320000000003 165.07500067624179 202.21320000000003C 184.33375075513666 202.21320000000003 200.84125082276083 187.76940000000002 220.1000009016557 187.76940000000002C 239.35875098055058 187.76940000000002 255.86625104817477 202.21320000000003 275.12500112706965 202.21320000000003C 294.38375120596453 202.21320000000003 310.8912512735887 173.3256 330.15000135248357 173.3256C 349.40875143137845 173.3256 365.9162514990026 144.438 385.1750015778975 144.438C 404.43375165679237 144.438 420.94125172441653 134.80880000000002 440.2000018033114 134.80880000000002C 459.4587518822063 134.80880000000002 475.96625194983045 125.1796 495.2250020287253 125.1796C 514.4837521076203 125.1796 530.9912521752444 96.292 550.2500022541393 96.292C 569.5087523330342 96.292 586.0162524006583 105.9212 605.2750024795532 105.9212C 605.2750024795532 105.9212 605.2750024795532 105.9212 605.2750024795532 240.73000000000002 L 0 240.73000000000002z"
                                    pathFrom="M 0 202.21320000000003C 19.258750078894874 202.21320000000003 35.766250146519056 211.8424 55.025000225413926 211.8424C 74.2837503043088 211.8424 90.79125037193297 192.584 110.05000045082785 192.584C 129.30875052972272 192.584 145.8162505973469 202.21320000000003 165.07500067624179 202.21320000000003C 184.33375075513666 202.21320000000003 200.84125082276083 187.76940000000002 220.1000009016557 187.76940000000002C 239.35875098055058 187.76940000000002 255.86625104817477 202.21320000000003 275.12500112706965 202.21320000000003C 294.38375120596453 202.21320000000003 310.8912512735887 173.3256 330.15000135248357 173.3256C 349.40875143137845 173.3256 365.9162514990026 144.438 385.1750015778975 144.438C 404.43375165679237 144.438 420.94125172441653 134.80880000000002 440.2000018033114 134.80880000000002C 459.4587518822063 134.80880000000002 475.96625194983045 125.1796 495.2250020287253 125.1796C 514.4837521076203 125.1796 530.9912521752444 96.292 550.2500022541393 96.292C 569.5087523330342 96.292 586.0162524006583 105.9212 605.2750024795532 105.9212C 605.2750024795532 105.9212 605.2750024795532 105.9212 605.2750024795532 240.73000000000002 L 0 240.73000000000002zz">
                                </path>
                                <path id="SvgjsPath1798"
                                    d="M0 202.21320000000003C19.258750078894874 202.21320000000003 35.766250146519056 211.8424 55.025000225413926 211.8424C74.2837503043088 211.8424 90.79125037193297 192.584 110.05000045082785 192.584C129.30875052972272 192.584 145.8162505973469 202.21320000000003 165.07500067624179 202.21320000000003C184.33375075513666 202.21320000000003 200.84125082276083 187.76940000000002 220.1000009016557 187.76940000000002C239.35875098055058 187.76940000000002 255.86625104817477 202.21320000000003 275.12500112706965 202.21320000000003C294.38375120596453 202.21320000000003 310.8912512735887 173.3256 330.15000135248357 173.3256C349.40875143137845 173.3256 365.9162514990026 144.438 385.1750015778975 144.438C404.43375165679237 144.438 420.94125172441653 134.80880000000002 440.2000018033114 134.80880000000002C459.4587518822063 134.80880000000002 475.96625194983045 125.1796 495.2250020287253 125.1796C514.4837521076203 125.1796 530.9912521752444 96.292 550.2500022541393 96.292C569.5087523330342 96.292 586.0162524006583 105.9212 605.2750024795532 105.9212 "
                                    fill="none" fill-opacity="1" stroke="#9cb9ff" stroke-opacity="1"
                                    stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area"
                                    index="1" clip-path="url(#gridRectMaskdolmcscb)"
                                    pathTo="M 0 202.21320000000003C 19.258750078894874 202.21320000000003 35.766250146519056 211.8424 55.025000225413926 211.8424C 74.2837503043088 211.8424 90.79125037193297 192.584 110.05000045082785 192.584C 129.30875052972272 192.584 145.8162505973469 202.21320000000003 165.07500067624179 202.21320000000003C 184.33375075513666 202.21320000000003 200.84125082276083 187.76940000000002 220.1000009016557 187.76940000000002C 239.35875098055058 187.76940000000002 255.86625104817477 202.21320000000003 275.12500112706965 202.21320000000003C 294.38375120596453 202.21320000000003 310.8912512735887 173.3256 330.15000135248357 173.3256C 349.40875143137845 173.3256 365.9162514990026 144.438 385.1750015778975 144.438C 404.43375165679237 144.438 420.94125172441653 134.80880000000002 440.2000018033114 134.80880000000002C 459.4587518822063 134.80880000000002 475.96625194983045 125.1796 495.2250020287253 125.1796C 514.4837521076203 125.1796 530.9912521752444 96.292 550.2500022541393 96.292C 569.5087523330342 96.292 586.0162524006583 105.9212 605.2750024795532 105.9212"
                                    pathFrom="M 0 202.21320000000003C 19.258750078894874 202.21320000000003 35.766250146519056 211.8424 55.025000225413926 211.8424C 74.2837503043088 211.8424 90.79125037193297 192.584 110.05000045082785 192.584C 129.30875052972272 192.584 145.8162505973469 202.21320000000003 165.07500067624179 202.21320000000003C 184.33375075513666 202.21320000000003 200.84125082276083 187.76940000000002 220.1000009016557 187.76940000000002C 239.35875098055058 187.76940000000002 255.86625104817477 202.21320000000003 275.12500112706965 202.21320000000003C 294.38375120596453 202.21320000000003 310.8912512735887 173.3256 330.15000135248357 173.3256C 349.40875143137845 173.3256 365.9162514990026 144.438 385.1750015778975 144.438C 404.43375165679237 144.438 420.94125172441653 134.80880000000002 440.2000018033114 134.80880000000002C 459.4587518822063 134.80880000000002 475.96625194983045 125.1796 495.2250020287253 125.1796C 514.4837521076203 125.1796 530.9912521752444 96.292 550.2500022541393 96.292C 569.5087523330342 96.292 586.0162524006583 105.9212 605.2750024795532 105.9212"
                                    fill-rule="evenodd"></path>
                                <g id="SvgjsG1791"
                                    class="apexcharts-series-markers-wrap apexcharts-hidden-element-shown"
                                    data:realIndex="1">
                                    <g class="apexcharts-series-markers">
                                        <path id="SvgjsPath1875" d="M 110.05000045082785, 0 
           m -0, 0 
           a 0,0 0 1,0 0,0 
           a 0,0 0 1,0 -0,0" fill="#9cb9ff" fill-opacity="1" stroke="#ffffff" stroke-opacity="0.9"
                                            stroke-linecap="butt" stroke-width="2" stroke-dasharray="0"
                                            cx="110.05000045082785" cy="0" shape="circle"
                                            class="apexcharts-marker w4hvcwbmc no-pointer-events"
                                            default-marker-size="0"></path>
                                    </g>
                                </g>
                            </g>
                            <g id="SvgjsG1783" class="apexcharts-datalabels" data:realIndex="0"></g>
                            <g id="SvgjsG1792" class="apexcharts-datalabels" data:realIndex="1"></g>
                        </g>
                        <line id="SvgjsLine1811" x1="0" y1="0" x2="605.2750024795532" y2="0" stroke="#b6b6b6"
                            stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs">
                        </line>
                        <line id="SvgjsLine1812" x1="0" y1="0" x2="605.2750024795532" y2="0" stroke-dasharray="0"
                            stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                        <g id="SvgjsG1813" class="apexcharts-xaxis" transform="translate(0, 0)">
                            <g id="SvgjsG1814" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text
                                    id="SvgjsText1816" font-family="Outfit, sans-serif" x="0" y="268.73"
                                    text-anchor="middle" dominant-baseline="auto" font-size="12px" font-weight="400"
                                    fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1817">Jan</tspan>
                                    <title>Jan</title>
                                </text><text id="SvgjsText1819" font-family="Outfit, sans-serif" x="55.02500022541393"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1820">Feb</tspan>
                                    <title>Feb</title>
                                </text><text id="SvgjsText1822" font-family="Outfit, sans-serif" x="110.05000045082787"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1823">Mar</tspan>
                                    <title>Mar</title>
                                </text><text id="SvgjsText1825" font-family="Outfit, sans-serif" x="165.07500067624179"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1826">Apr</tspan>
                                    <title>Apr</title>
                                </text><text id="SvgjsText1828" font-family="Outfit, sans-serif" x="220.1000009016557"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1829">May</tspan>
                                    <title>May</title>
                                </text><text id="SvgjsText1831" font-family="Outfit, sans-serif" x="275.12500112706965"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1832">Jun</tspan>
                                    <title>Jun</title>
                                </text><text id="SvgjsText1834" font-family="Outfit, sans-serif" x="330.15000135248357"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1835">Jul</tspan>
                                    <title>Jul</title>
                                </text><text id="SvgjsText1837" font-family="Outfit, sans-serif" x="385.1750015778975"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1838">Aug</tspan>
                                    <title>Aug</title>
                                </text><text id="SvgjsText1840" font-family="Outfit, sans-serif" x="440.2000018033114"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1841">Sep</tspan>
                                    <title>Sep</title>
                                </text><text id="SvgjsText1843" font-family="Outfit, sans-serif" x="495.2250020287253"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1844">Oct</tspan>
                                    <title>Oct</title>
                                </text><text id="SvgjsText1846" font-family="Outfit, sans-serif" x="550.2500022541392"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1847">Nov</tspan>
                                    <title>Nov</title>
                                </text><text id="SvgjsText1849" font-family="Outfit, sans-serif" x="605.2750024795531"
                                    y="268.73" text-anchor="middle" dominant-baseline="auto" font-size="12px"
                                    font-weight="400" fill="#373d3f" class="apexcharts-text apexcharts-xaxis-label "
                                    style="font-family: Outfit, sans-serif;">
                                    <tspan id="SvgjsTspan1850">Dec</tspan>
                                    <title>Dec</title>
                                </text></g>
                        </g>
                        <g id="SvgjsG1871" class="apexcharts-yaxis-annotations apexcharts-hidden-element-shown"></g>
                        <g id="SvgjsG1872" class="apexcharts-xaxis-annotations apexcharts-hidden-element-shown"></g>
                        <g id="SvgjsG1873" class="apexcharts-point-annotations apexcharts-hidden-element-shown"></g>
                        <rect id="SvgjsRect1876" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1"
                            stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"
                            class="apexcharts-zoom-rect"></rect>
                        <rect id="SvgjsRect1877" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1"
                            stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"
                            class="apexcharts-selection-rect"></rect>
                    </g>
                </svg>
                <div class="apexcharts-tooltip apexcharts-theme-light" style="left: 169.183px; top: 80.0336px;">
                    <div class="apexcharts-tooltip-title" style="font-family: Outfit, sans-serif; font-size: 12px;">Mar
                    </div>
                    <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-0 apexcharts-active"
                        style="order: 1; display: flex;"><span class="apexcharts-tooltip-marker"
                            style="background-color: rgb(70, 95, 255);"></span>
                        <div class="apexcharts-tooltip-text" style="font-family: Outfit, sans-serif; font-size: 12px;">
                            <div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-y-label">Sales:
                                </span><span class="apexcharts-tooltip-text-y-value">170</span></div>
                            <div class="apexcharts-tooltip-goals-group"><span
                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                            <div class="apexcharts-tooltip-z-group"><span
                                    class="apexcharts-tooltip-text-z-label"></span><span
                                    class="apexcharts-tooltip-text-z-value"></span></div>
                        </div>
                    </div>
                    <div class="apexcharts-tooltip-series-group apexcharts-tooltip-series-group-1 apexcharts-active"
                        style="order: 2; display: flex;"><span class="apexcharts-tooltip-marker"
                            style="background-color: rgb(156, 185, 255);"></span>
                        <div class="apexcharts-tooltip-text" style="font-family: Outfit, sans-serif; font-size: 12px;">
                            <div class="apexcharts-tooltip-y-group"><span
                                    class="apexcharts-tooltip-text-y-label">Revenue: </span><span
                                    class="apexcharts-tooltip-text-y-value">50</span></div>
                            <div class="apexcharts-tooltip-goals-group"><span
                                    class="apexcharts-tooltip-text-goals-label"></span><span
                                    class="apexcharts-tooltip-text-goals-value"></span></div>
                            <div class="apexcharts-tooltip-z-group"><span
                                    class="apexcharts-tooltip-text-z-label"></span><span
                                    class="apexcharts-tooltip-text-z-value"></span></div>
                        </div>
                    </div>
                </div>
                <div
                    class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                    <div class="apexcharts-yaxistooltip-text"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ====== Chart Four End -->