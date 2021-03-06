/* *
 *
 *  (c) 2009-2019 Øystein Moseng
 *
 *  Place desriptions on a series and its points.
 *
 *  License: www.highcharts.com/license
 *
 *  !!!!!!! SOURCE GETS TRANSPILED BY TYPESCRIPT. EDIT TS FILE ONLY. !!!!!!!
 *
 * */

'use strict';

import H from '../../../../parts/Globals.js';
var numberFormat = H.numberFormat,
    find = H.find;

import U from '../../../../parts/Utilities.js';
var isNumber = U.isNumber,
    pick = U.pick,
    defined = U.defined;

import HTMLUtilities from '../../utils/htmlUtilities.js';
var stripHTMLTags = HTMLUtilities.stripHTMLTagsFromString,
    reverseChildNodes = HTMLUtilities.reverseChildNodes;

import ChartUtilities from '../../utils/chartUtilities.js';
var getAxisDescription = ChartUtilities.getAxisDescription,
    getSeriesFirstPointElement = ChartUtilities.getSeriesFirstPointElement,
    getSeriesA11yElement = ChartUtilities.getSeriesA11yElement,
    unhideChartElementFromAT = ChartUtilities.unhideChartElementFromAT;


/* eslint-disable valid-jsdoc */

/**
 * @private
 */
function findFirstPointWithGraphic(
    point: Highcharts.Point
): (Highcharts.Point|null) {
    const sourcePointIndex = point.index;

    if (!point.series || !point.series.data || !defined(sourcePointIndex)) {
        return null;
    }

    return find(point.series.data, function (p: Highcharts.Point): boolean {
        return !!(
            p &&
            typeof p.index !== 'undefined' &&
            p.index > sourcePointIndex &&
            p.graphic &&
            p.graphic.element
        );
    }) || null;
}


/**
 * @private
 */
function makeDummyElement(
    point: Highcharts.Point,
    pos: Highcharts.PositionObject
): Highcharts.SVGElement {
    var renderer = point.series.chart.renderer,
        dummy = renderer.rect(pos.x, pos.y, 1, 1);

    dummy.attr({
        'class': 'highcharts-a11y-dummy-point',
        fill: 'none',
        'fill-opacity': 0,
        'stroke-opacity': 0,
        opacity: 0
    });

    return dummy;
}


/**
 * @private
 * @param {Highcharts.Point} point
 * @return {Highcharts.HTMLDOMElement|Highcharts.SVGDOMElement|undefined}
 */
function addDummyPointElement(
    point: Highcharts.Point
): (Highcharts.HTMLDOMElement|Highcharts.SVGDOMElement|undefined) {
    var series = point.series,
        firstPointWithGraphic = findFirstPointWithGraphic(point),
        firstGraphic = firstPointWithGraphic && firstPointWithGraphic.graphic,
        parentGroup = firstGraphic ?
            firstGraphic.parentGroup :
            series.graph || series.group,
        dummyPos = firstPointWithGraphic ? {
            x: pick(point.plotX, firstPointWithGraphic.plotX, 0),
            y: pick(point.plotY, firstPointWithGraphic.plotY, 0)
        } : {
            x: pick(point.plotX, 0),
            y: pick(point.plotY, 0)
        },
        dummyElement = makeDummyElement(point, dummyPos);

    if (parentGroup && parentGroup.element) {
        point.graphic = dummyElement;

        dummyElement.add(parentGroup);

        // Move to correct pos in DOM
        parentGroup.element.insertBefore(
            dummyElement.element,
            firstGraphic ? firstGraphic.element : null
        );

        return dummyElement.element;
    }
}


/**
 * @private
 * @param {Highcharts.Series} series
 * @return {boolean}
 */
function hasMorePointsThanDescriptionThreshold(
    series: Highcharts.AccessibilitySeries
): boolean {
    var chartA11yOptions = series.chart.options.accessibility,
        threshold: (boolean|number) = (
            (chartA11yOptions.series as any).pointDescriptionEnabledThreshold
        );

    return !!(
        threshold !== false &&
        series.points &&
        series.points.length >= threshold
    );
}


/**
 * @private
 * @param {Highcharts.Series} series
 * @return {boolean}
 */
function shouldSetScreenReaderPropsOnPoints(
    series: Highcharts.AccessibilitySeries
): boolean {
    var seriesA11yOptions = series.options.accessibility || {};

    return !hasMorePointsThanDescriptionThreshold(series) &&
        !seriesA11yOptions.exposeAsGroupOnly;
}


/**
 * @private
 * @param {Highcharts.Series} series
 * @return {boolean}
 */
function shouldSetKeyboardNavPropsOnPoints(
    series: Highcharts.AccessibilitySeries
): boolean {
    var chartA11yOptions = series.chart.options.accessibility,
        seriesNavOptions = chartA11yOptions.keyboardNavigation.seriesNavigation;

    return !!(
        series.points && (
            series.points.length <
                seriesNavOptions.pointNavigationEnabledThreshold ||
            seriesNavOptions.pointNavigationEnabledThreshold === false
        )
    );
}


/**
 * @private
 * @param {Highcharts.Series} series
 * @return {boolean}
 */
function shouldDescribeSeriesElement(
    series: Highcharts.AccessibilitySeries
): boolean {
    var chart = series.chart,
        chartOptions = chart.options.chart || {},
        chartHas3d = chartOptions.options3d && chartOptions.options3d.enabled,
        hasMultipleSeries = chart.series.length > 1,
        describeSingleSeriesOption =
            (chart.options.accessibility.series as any).describeSingleSeries,
        exposeAsGroupOnlyOption =
            (series.options.accessibility || {}).exposeAsGroupOnly,
        noDescribe3D = chartHas3d && hasMultipleSeries;

    return !noDescribe3D && (
        hasMultipleSeries || describeSingleSeriesOption ||
        exposeAsGroupOnlyOption || hasMorePointsThanDescriptionThreshold(series)
    );
}


/**
 * @private
 * @param {Highcharts.Point} point
 * @param {number} value
 * @return {string}
 */
function pointNumberToString(
    point: Highcharts.AccessibilityPoint,
    value: number
): string {
    var chart = point.series.chart,
        a11yPointOptions = chart.options.accessibility.point || {},
        tooltipOptions = point.series.tooltipOptions || {},
        lang = chart.options.lang;

    if (isNumber(value)) {
        return numberFormat(
            value,
            a11yPointOptions.valueDecimals ||
                tooltipOptions.valueDecimals ||
                -1,
            lang.decimalPoint,
            (lang.accessibility as any).thousandsSep || lang.thousandsSep
        );
    }

    return value;
}


/**
 * @private
 * @param {Highcharts.Series} series
 * @return {string}
 */
function getSeriesDescriptionText(
    series: Highcharts.AccessibilitySeries
): string {
    var seriesA11yOptions = series.options.accessibility || {},
        descOpt = seriesA11yOptions.description;

    return descOpt && series.chart.langFormat(
        'accessibility.series.description', {
            description: descOpt,
            series: series
        }
    ) || '';
}


/**
 * @private
 * @param {Highcharts.series} series
 * @param {string} axisCollection
 * @return {string}
 */
function getSeriesAxisDescriptionText(
    series: Highcharts.Series,
    axisCollection: string
): string {
    var axis: Highcharts.Axis = (series as any)[axisCollection];

    return series.chart.langFormat(
        'accessibility.series.' + axisCollection + 'Description',
        {
            name: getAxisDescription(axis),
            series: series
        }
    );
}


/**
 * Get accessible time description for a point on a datetime axis.
 *
 * @private
 * @function Highcharts.Point#getTimeDescription
 * @param {Highcharts.Point} point
 * @return {string|undefined}
 * The description as string.
 */
function getPointA11yTimeDescription(
    point: Highcharts.AccessibilityPoint
): (string|undefined) {
    var series = point.series,
        chart = series.chart,
        a11yOptions = chart.options.accessibility.point || {},
        hasDateXAxis = series.xAxis && series.xAxis.isDatetimeAxis;

    if (hasDateXAxis) {
        var tooltipDateFormat = H.Tooltip.prototype.getXDateFormat.call(
                {
                    getDateFormat: H.Tooltip.prototype.getDateFormat,
                    chart: chart
                },
                point,
                chart.options.tooltip,
                series.xAxis
            ),
            dateFormat = a11yOptions.dateFormatter &&
                a11yOptions.dateFormatter(point) ||
                a11yOptions.dateFormat ||
                tooltipDateFormat;

        return chart.time.dateFormat(dateFormat, (point.x as any), void 0);
    }
}


/**
 * @private
 * @param {Highcharts.Point} point
 * @return {string}
 */
function getPointXDescription(
    point: Highcharts.AccessibilityPoint
): string {
    var timeDesc = getPointA11yTimeDescription(point),
        xAxis = point.series.xAxis || {},
        pointCategory = xAxis.categories && defined(point.category) &&
            ('' + point.category).replace('<br/>', ' '),
        canUseId = point.id && point.id.indexOf('highcharts-') < 0,
        fallback = 'x, ' + point.x;

    return point.name || timeDesc || pointCategory ||
        (canUseId ? point.id : fallback);
}


/**
 * @private
 * @param {Highcharts.Point} point
 * @param {string} prefix
 * @param {string} suffix
 * @return {string}
 */
function getPointArrayMapValueDescription(
    point: Highcharts.AccessibilityPoint,
    prefix: string,
    suffix: string
): string {
    var pre = prefix || '',
        suf = suffix || '',
        keyToValStr = function (key: string): string {
            var num = pointNumberToString(
                point,
                pick((point as any)[key], (point.options as any)[key])
            );
            return key + ': ' + pre + num + suf;
        },
        pointArrayMap: Array<string> = point.series.pointArrayMap as any;

    return pointArrayMap.reduce(function (desc: string, key: string): string {
        return desc + (desc.length ? ', ' : '') + keyToValStr(key);
    }, '');
}


/**
 * @private
 * @param {Highcharts.Point} point
 * @return {string}
 */
function getPointValueDescription(
    point: Highcharts.AccessibilityPoint
): string {
    var series = point.series,
        a11yPointOpts = series.chart.options.accessibility.point || {},
        tooltipOptions = series.tooltipOptions || {},
        valuePrefix = a11yPointOpts.valuePrefix ||
            tooltipOptions.valuePrefix || '',
        valueSuffix = a11yPointOpts.valueSuffix ||
            tooltipOptions.valueSuffix || '',
        fallbackKey: ('value'|'y') = (
            typeof (point as Highcharts.PackedBubblePoint).value !==
            'undefined' ?
                'value' : 'y'
        ),
        fallbackDesc = pointNumberToString(point, (point as any)[fallbackKey]);

    if (point.isNull) {
        return series.chart.langFormat('accessibility.series.nullPointValue', {
            point: point
        });
    }

    if (series.pointArrayMap) {
        return getPointArrayMapValueDescription(
            point, valuePrefix, valueSuffix
        );
    }

    return valuePrefix + fallbackDesc + valueSuffix;
}


/**
 * Return string with information about point.
 * @private
 * @return {string}
 */
function defaultPointDescriptionFormatter(
    point: Highcharts.AccessibilityPoint
): string {
    var series = point.series,
        chart = series.chart,
        description = point.options && point.options.accessibility &&
            point.options.accessibility.description,
        showXDescription = pick(
            series.xAxis &&
            series.xAxis.options.accessibility &&
            series.xAxis.options.accessibility.enabled,
            !chart.angular
        ),
        xDesc = getPointXDescription(point),
        valueDesc = getPointValueDescription(point),
        indexText = defined(point.index) ? (point.index + 1) + '. ' : '',
        xDescText = showXDescription ? xDesc + ', ' : '',
        valText = valueDesc + '.',
        userDescText = description ? ' ' + description : '',
        seriesNameText = chart.series.length > 1 && series.name ?
            ' ' + series.name + '.' : '';

    return indexText + xDescText + valText + userDescText + seriesNameText;
}


/**
 * Set a11y props on a point element
 * @private
 * @param {Highcharts.Point} point
 * @param {Highcharts.HTMLDOMElement|Highcharts.SVGDOMElement} pointElement
 */
function setPointScreenReaderAttribs(
    point: Highcharts.AccessibilityPoint,
    pointElement: (Highcharts.HTMLDOMElement|Highcharts.SVGDOMElement)
): void {
    var series = point.series,
        a11yPointOptions = series.chart.options.accessibility.point || {},
        seriesA11yOptions = series.options.accessibility || {},
        label = stripHTMLTags(
            seriesA11yOptions.pointDescriptionFormatter &&
            seriesA11yOptions.pointDescriptionFormatter(point) ||
            a11yPointOptions.descriptionFormatter &&
            a11yPointOptions.descriptionFormatter(point) ||
            defaultPointDescriptionFormatter(point)
        );

    pointElement.setAttribute('role', 'img');
    pointElement.setAttribute('aria-label', label);
}


/**
 * Add accessible info to individual point elements of a series
 * @private
 * @param {Highcharts.Series} series
 */
function describePointsInSeries(series: Highcharts.AccessibilitySeries): void {
    var setScreenReaderProps = shouldSetScreenReaderPropsOnPoints(series),
        setKeyboardProps = shouldSetKeyboardNavPropsOnPoints(series);

    if (setScreenReaderProps || setKeyboardProps) {
        series.points.forEach(function (
            point: Highcharts.AccessibilityPoint
        ): void {
            var shouldAddDummyPoint = point.isNull,
                pointEl = point.graphic && point.graphic.element ||
                    shouldAddDummyPoint && addDummyPointElement(point);

            if (pointEl) {
                // We always set tabindex, as long as we are setting
                // props.
                pointEl.setAttribute('tabindex', '-1');

                if (setScreenReaderProps) {
                    setPointScreenReaderAttribs(point, pointEl);
                } else {
                    pointEl.setAttribute('aria-hidden', true);
                }
            }
        });
    }
}


/**
 * Return string with information about series.
 * @private
 * @return {string}
 */
function defaultSeriesDescriptionFormatter(
    series: Highcharts.AccessibilitySeries
): string {
    var chart = series.chart,
        chartTypes = chart.types || [],
        description = getSeriesDescriptionText(series),
        shouldDescribeAxis = function (
            coll: ('xAxis'|'yAxis')
        ): (boolean|Highcharts.Axis) {
            return chart[coll] && chart[coll].length > 1 && series[coll];
        },
        xAxisInfo = getSeriesAxisDescriptionText(series, 'xAxis'),
        yAxisInfo = getSeriesAxisDescriptionText(series, 'yAxis'),
        summaryContext = {
            name: series.name || '',
            ix: (series.index as any) + 1,
            numSeries: chart.series && chart.series.length,
            numPoints: series.points && series.points.length,
            series: series
        },
        combinationSuffix = chartTypes.length > 1 ? 'Combination' : '',
        summary = chart.langFormat(
            'accessibility.series.summary.' + series.type + combinationSuffix,
            summaryContext
        ) || chart.langFormat(
            'accessibility.series.summary.default' + combinationSuffix,
            summaryContext
        );

    return summary + (description ? ' ' + description : '') + (
        shouldDescribeAxis('yAxis') ? ' ' + yAxisInfo : ''
    ) + (
        shouldDescribeAxis('xAxis') ? ' ' + xAxisInfo : ''
    );
}


/**
 * Set a11y props on a series element
 * @private
 * @param {Highcharts.Series} series
 * @param {Highcharts.HTMLDOMElement|Highcharts.SVGDOMElement} seriesElement
 */
function describeSeriesElement(
    series: Highcharts.AccessibilitySeries,
    seriesElement: (Highcharts.HTMLDOMElement|Highcharts.SVGDOMElement)
): void {
    var seriesA11yOptions = series.options.accessibility || {},
        a11yOptions = series.chart.options.accessibility,
        landmarkVerbosity = a11yOptions.landmarkVerbosity;

    // Handle role attribute
    if (seriesA11yOptions.exposeAsGroupOnly) {
        seriesElement.setAttribute('role', 'img');
    } else if (landmarkVerbosity === 'all') {
        seriesElement.setAttribute('role', 'region');
    } /* else do not add role */

    seriesElement.setAttribute('tabindex', '-1');
    seriesElement.setAttribute(
        'aria-label',
        stripHTMLTags(
            (a11yOptions.series as any).descriptionFormatter &&
            (a11yOptions.series as any).descriptionFormatter(series) ||
            defaultSeriesDescriptionFormatter(series)
        )
    );
}


/**
 * Put accessible info on series and points of a series.
 * @param {Highcharts.Series} series The series to add info on.
 */
function describeSeries(series: Highcharts.AccessibilitySeries): void {
    var chart = series.chart,
        firstPointEl = getSeriesFirstPointElement(series),
        seriesEl = getSeriesA11yElement(series);

    if (seriesEl) {
        // For some series types the order of elements do not match the
        // order of points in series. In that case we have to reverse them
        // in order for AT to read them out in an understandable order
        if (seriesEl.lastChild === firstPointEl) {
            reverseChildNodes(seriesEl);
        }

        describePointsInSeries(series);

        unhideChartElementFromAT(chart, seriesEl);

        if (shouldDescribeSeriesElement(series)) {
            describeSeriesElement(series, seriesEl);
        } else {
            seriesEl.setAttribute('aria-label', '');
        }
    }
}

var SeriesDescriber = {
    describeSeries: describeSeries,
    defaultPointDescriptionFormatter: defaultPointDescriptionFormatter,
    defaultSeriesDescriptionFormatter: defaultSeriesDescriptionFormatter,
    getPointA11yTimeDescription: getPointA11yTimeDescription,
    getPointXDescription: getPointXDescription,
    getPointValueDescription: getPointValueDescription
};

export default SeriesDescriber;
