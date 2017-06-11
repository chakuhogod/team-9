var Page = function() {
    var updateTimerCurrent,
        updateCurrentPower,
        chartDay,
        reinitializeGraphs = true,
        myGraphs = true,
        data1=[];

    var defaults = {
        padding: {
            right: 15,
            top: 0,
            bottom: 0
        },
        legend: {
            show: false
        },
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    culling: true,
                    fit: false,
                    outer: false
                }
            },
            y: {
                padding: {
                    bottom: 0,
                    top: 25
                }
            }
        },
        grid: {
            lines: {
                front: false
            }
        },
        data: {
            mimeType: 'json'
        },
        line: {
            connectNull: true
        }
    };

    var mergeWithDefaults = function(options) {
        return $.extend(true, {}, defaults, options);
    };

    var createPowerGraphJson = function(productionPower) {
        var addDataPointsToJson = function(json, power, type) {
            if (typeof power == 'undefined') return;

            $.each(power, function(key, value) {
                $.each(value, function (datetime, watt) {


                var time = datetime.split(' ')[1];

                if (!json.hasOwnProperty(time)) {
                    json[time] = {
                        dt: time
                    };
                }

                json[time][type] = watt;
                });
            });
        };

        var json = {};

        addDataPointsToJson(json, productionPower, 'production');

        $.each(json, function(time, entry) {
            entry.total = 0;

            if (entry.hasOwnProperty('production')) entry.total += entry.production;

            entry.total = Math.round(entry.total * 1000) / 1000;
        });

        // object of objects to array of objects
        json = Object.keys(json).map(function(key) {
            return json[key];
        });

        return json;
    };

    var initChartDay = function(productionPower) {
        var json = createPowerGraphJson(productionPower);
        var options = {
            bindto: '#graph-day',
            axis: {
                x: {
                    tick: {
                        format: '%y-%m-%d',
                    }
                },
                y:{
                    tick: {
                        format: function (d) { return d + "W"; }
                    }
                }
            },
            grid: {
                y: {
                    show: true
                }
            },
            data: {
                json: json,
                keys: {
                    x: 'dt',
                    value: ['total']
                },
                axes: {
                    production: 'y',
                    total: 'y'
                },
                names: {
                    production:  ' (W)',
                    total:  ' (W)'
                },
                xFormat: '%H:%M:%S',
                types: {
                    production: 'line',
                    total: 'line'
                },
                colors: {
                    production: 'green',
                    total: 'blue'
                },
                classes: {
                    production: 'thick-line',
                    total: 'thick-line'
                }
            },
            point: {
                r: 1
            }
        };

        if (typeof consumptionPower !== 'undefined') {
            $('#graph-day').css('height', '414px');
            options.data.keys.value = ['production',  'total'];
        }

        chartDay = c3.generate(mergeWithDefaults(options));

        $('.chart-legend').each(function(index1, box) {
            box = $(box);
            box.find('a.legend-item').each(function(index2, item) {
                item = $(item);

                item.click(function(event) {
                    if (item.attr('href')) event.preventDefault();

                    var target = item.data('chart');

                    if (box.data('chart') == target) {
                        box.data('chart', '');
                        chartDay.revert();
                    } else {
                        box.data('chart', target);
                        chartDay.focus(target);
                    }
                })
            });
        });
    };

    var updateChartDay = function(productionPower) {
        chartDay.load({
            json: createPowerGraphJson(productionPower),
            keys: {
                x: 'dt',
                value: (typeof consumptionPower === 'undefined' ? ['production'] : ['production', 'total'])
            }
        });
    };

    var update = function(stats) {
        // fill key performance indicators

        if (reinitializeGraphs)
        {
            // Init on page load and on changing date
            initChartDay(stats);
        }
        else
        {
            updateChartDay(stats);
        }

        reinitializeGraphs = false;
    };


    var abortCurrent = function() {
        // Abort previous request and clear timeout.
        if (updateCurrentPower && updateCurrentPower.readyState != 4) updateCurrentPower.abort();
        window.clearTimeout(updateTimerCurrent);

    };


    var fetchCurrent = function() {
        abortCurrent();

        updateCurrentPower = $.getJSON('api/abn', function(data) {

            $.each(data, function (key, val) {
                $.each(val, function (key, val) {
                    $.each(val.transactions, function (key, val) {

                        $.each(val, function (i, j) {
                            var item = {};
                            item[this.transactionTimestamp] = this.balanceAfterMutation;
                           data1.push(item);
                        });
                    });

                });
            });
            update(data1);

        }).always(function() {
            // schedule new update.
            updateTimerCurrent = window.setTimeout(fetchCurrent, 10000);
        });
    };

    var init = function() {
        reinitializeGraphs = true;
        myGraphs = true;
        fetchCurrent();

        // making use of jQuery Visibility plugin
        $(document).on({
            'show': function () {
                fetchCurrent();
            },
            'hide': function () {
                abortCurrent();
            }
        });
    };

    return {
        update: update,
        init: init
    }
}();

$(function() {
    Page.init();
});
