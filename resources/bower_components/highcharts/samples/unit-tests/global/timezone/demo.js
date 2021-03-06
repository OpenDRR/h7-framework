/**
 * Checks that the timezone option is applied and works.
 */
QUnit.test('timezone', function (assert) {
    var chart,
        oct27Point;

    Highcharts.setOptions({
        global: {
            timezone: 'Europe/Oslo',

            // This should be ignored
            getTimezoneOffset: function (timestamp) {
                var zone = 'America/New_York',
                    timezoneOffset = -moment.tz(timestamp, zone).utcOffset();
                return timezoneOffset;
            }
        }
    });

    chart = Highcharts.chart('container', {

        title: {
            text: 'timezone with local DST crossover'
        },

        subtitle: {
            text: 'From October 27, UTC midnight is 01:00 AM in Oslo'
        },

        xAxis: {
            type: 'datetime'
        },

        series: [{
            data: (function () {
                var arr = [],
                    i;
                for (i = 0; i < 5; i = i + 1) {
                    arr.push(i);
                }
                return arr;
            }()),
            dataLabels: {
                enabled: true,
                format: '{x:%H:%M}'
            },
            pointStart: Date.UTC(2014, 9, 24),
            pointInterval: 24 * 36e5,
            name: 'UTC Midnight',
            tooltip: {
                pointFormat: 'UTC midnight = {point.x:%H:%M} local time'
            }
        }]
    });

    oct27Point = chart.series[0].data[3];

    /*
    assert.equal(
        typeof Highcharts.time.getTimezoneOffset,
        'function',
        'timezone option is applied'
    );
    */

    assert.equal(
        Highcharts.dateFormat('%H:%M', oct27Point.x),
        '01:00',
        'From October 27, UTC midnight is 01:00 AM in Oslo'
    );

    // Tear down
    Highcharts.setOptions({
        global: {
            timezone: undefined,
            getTimezoneOffset: undefined
        }
    });
});

/**
 * Checks that specified getTimezoneOffset function is used if timezone option
 * is not.
 */
QUnit.test('getTimezoneOffset', function (assert) {
    var chart,
        oct27Point;

    Highcharts.setOptions({
        global: {
            getTimezoneOffset: function (timestamp) {
                var zone = 'Europe/Oslo',
                    timezoneOffset = -moment.tz(timestamp, zone).utcOffset();
                return timezoneOffset;
            }
        }
    });

    chart = Highcharts.chart('container', {

        title: {
            text: 'timezone with local DST crossover'
        },

        subtitle: {
            text: 'From October 27, UTC midnight is 01:00 AM in Oslo'
        },

        xAxis: {
            type: 'datetime'
        },

        series: [{
            data: (function () {
                var arr = [],
                    i;
                for (i = 0; i < 5; i = i + 1) {
                    arr.push(i);
                }
                return arr;
            }()),
            dataLabels: {
                enabled: true,
                format: '{x:%H:%M}'
            },
            pointStart: Date.UTC(2014, 9, 24),
            pointInterval: 24 * 36e5,
            name: 'UTC Midnight',
            tooltip: {
                pointFormat: 'UTC midnight = {point.x:%H:%M} local time'
            }
        }]
    });

    oct27Point = chart.series[0].data[3];

    /*
    assert.equal(
        typeof Highcharts.time.getTimezoneOffset,
        'function',
        'getTimezoneOffset function is applied'
    );
    */

    assert.equal(
        Highcharts.dateFormat('%H:%M', oct27Point.x),
        '01:00',
        'From October 27, UTC midnight is 01:00 AM in Oslo'
    );

    // Reset
    Highcharts.setOptions({
        global: {
            getTimezoneOffset: undefined
        }
    });

});

QUnit.test('Crossing over DST with hourly ticks (#6278)', function (assert) {

    Highcharts.setOptions({
        global: {
            useUTC: true,
            timezone: 'Europe/London'
        }
    });

    var chart = Highcharts.chart('container', {

        chart: {
            width: 600
        },
        tooltip: {
            borderColor: 'black',
            crosshairs: true,
            shared: true,
            useHTML: true
        },

        xAxis: {
            type: 'datetime'
        },

        title: {
            text: 'DST Demo'
        },

        series: [{
            data: [
                [Date.UTC(2016, 9, 29, 23, 15), 9],
                [Date.UTC(2016, 9, 30, 0, 15), 9],
                [Date.UTC(2016, 9, 30, 0, 30), 10],
                [Date.UTC(2016, 9, 30, 0, 45), 11],
                [Date.UTC(2016, 9, 30, 1, 0), 12],
                [Date.UTC(2016, 9, 30, 1, 15), 13],
                [Date.UTC(2016, 9, 30, 1, 30), 14],
                [Date.UTC(2016, 9, 30, 1, 45), 15],
                [Date.UTC(2016, 9, 30, 2, 0), 16],
                [Date.UTC(2016, 9, 30, 2, 15), 17],
                [Date.UTC(2016, 9, 30, 2, 30), 18],
                [Date.UTC(2016, 9, 30, 2, 45), 19],
                [Date.UTC(2016, 9, 30, 3, 0), 20]
            ]
        }]
    });

    var ticks = chart.xAxis[0].tickPositions
        .map(function (pos) {
            return chart.xAxis[0].ticks[pos].label.element.textContent;
        });

    assert.deepEqual(
        ticks,
        ['00:30', '01:00', '01:30', '01:00', '01:30', '02:00', '02:30', '03:00'],
        'Ticks before DST crossover'
    );

    Highcharts.setOptions({
        global: {
            useUTC: true,
            timezone: undefined
        }
    });

});

QUnit.test('Negative timezoneOffset', function (assert) {
    Highcharts.setOptions({
        global: {
            timezoneOffset: -3 * 60
        }
    });

    var chart = Highcharts.chart('container', {
        chart: {
            width: 400
        },
        xAxis: {
            type: 'datetime'
        },
        series: [{
            data: [{
                x: 1493031600000,
                y: 39.9
            }, {
                x: 1493031630000,
                y: 81.5
            }]
        }]
    });

    var ticks = chart.xAxis[0].tickPositions
        .map(function (pos) {
            return chart.xAxis[0].ticks[pos].label.element.textContent;
        });

    assert.deepEqual(
        ticks,
        ['14:00:00', '14:00:30'],
        'Two ticks'
    );

    // Reset
    Highcharts.setOptions({
        global: {
            timezoneOffset: 0
        }
    });

});

QUnit.test('Crossing DST with a wide pointRange (#7432)', function (assert) {
    Highcharts.setOptions({
        global: {
            timezone: 'Europe/Copenhagen'
        }
    });

    var chart = Highcharts.chart('container', {
        chart: {
            type: 'column',
            width: 600
        },
        xAxis: {
            type: 'datetime',
            labels: {
                format: '{value:%Y-%m-%d<br>%H:%M}'
            }
        },
        series: [{
            data: [
                [Date.UTC(2017, 9, 29, 23), 10],
                [Date.UTC(2017, 9, 30, 23), 8]
            ]
        }]
    });

    assert.notEqual(
        chart.xAxis[0].ticks[chart.xAxis[0].tickPositions[0]].label.element
            .textContent.indexOf('00:00'),
        -1,
        'Tick should land on midnight'
    );
    assert.notEqual(
        chart.xAxis[0].ticks[chart.xAxis[0].tickPositions[1]].label.element
            .textContent.indexOf('00:00'),
        -1,
        'Tick should land on midnight'
    );

    Highcharts.setOptions({
        global: {
            timezone: undefined
        }
    });
});

// Highcharts v4.0.3, Issue #3359
// Default datetime axis formatter glitch when timezoneOffset is negative
// Skipped, doesn't work in browser
QUnit.skip('Midnight ticks should show date (#3359)', function (assert) {
    function initChart(timezoneOffset) {
        Highcharts.setOptions({
            global: {
                timezoneOffset: timezoneOffset
            }
        });
        var chart = Highcharts.chart('container', {
            chart: {
                width: 600
            },
            xAxis: {
                type: 'datetime'
            },
            series: [{
                data: Array.apply(null, { length: 48 }).map(Math.random),
                pointStart: Date.UTC(2013, 0, 1, 12),
                pointInterval: 36e5 // one hour
            }]
        });
        return chart;
    }

    function getCurrentTickLabels(children) {
        var tickLabels = [];
        for (var i = 0; i < children.length; i++) {
            tickLabels.push(children[i].textContent);
        }
        return tickLabels;
    }
    var defaultHighchartsOptions = Highcharts.getOptions().global.timezoneOffset;
    var minus60OffsetXLabels = ['16:00', '2. Jan', '08:00', '16:00', '3. Jan', '08:00'];
    var plus60OffsetXLabels = ['16:00', '2. Jan', '08:00', '16:00', '3. Jan', '0???08:00'];
    var chartMinus60OffsetXLabels = getCurrentTickLabels(
        initChart(-60).xAxis[0].labelGroup.element.childNodes || []
    );
    var chartPlus60OffsetXLabels = getCurrentTickLabels(
        initChart(60).xAxis[0].labelGroup.element.childNodes || []
    );
    assert.deepEqual(
        chartMinus60OffsetXLabels,
        minus60OffsetXLabels,
        'Midnight ticks is not showing properly'
    );
    assert.notDeepEqual(
        chartPlus60OffsetXLabels,
        minus60OffsetXLabels,
        'The timezone offset is not working as it should'
    );

    assert.deepEqual(
        chartPlus60OffsetXLabels,
        plus60OffsetXLabels,
        'Midnight ticks is not showing properly'
    );
    // reset options
    initChart(defaultHighchartsOptions);
});