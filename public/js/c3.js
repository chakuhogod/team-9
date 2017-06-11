var Page = function() {
    var focusedDate = null,
        focusedMonth = null,
        updateTimerCurrent,
        updateTimerKpi,
        updateCurrentPower,
        updateKpi,
        chartDay,
        chartMonth,
        chartYear,
        reinitializeGraphs = true,
        myGraphs = true,
        parameters = {};

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

    var createPowerGraphJson = function(productionPower, consumptionPower) {
        var addDataPointsToJson = function(json, power, type) {
            if (typeof power == 'undefined') return;

            $.each(power, function(datetime, watt) {
                var time = datetime.split(' ')[1];

                if (!json.hasOwnProperty(time)) {
                    json[time] = {
                        dt: time
                    };
                }

                json[time][type] = watt;
            });
        };

        var json = {};

        addDataPointsToJson(json, productionPower, 'production');
        addDataPointsToJson(json, consumptionPower, 'consumption');

        $.each(json, function(time, entry) {
            entry.total = 0;

            if (entry.hasOwnProperty('consumption')) entry.total += entry.consumption;
            if (entry.hasOwnProperty('production')) entry.total += entry.production;

            entry.total = Math.round(entry.total * 1000) / 1000;
        });

        // object of objects to array of objects
        json = Object.keys(json).map(function(key) {
            return json[key];
        });

        return json;
    };

    var initChartDay = function(productionPower, consumptionPower) {
        var json = createPowerGraphJson(productionPower, consumptionPower);
        var options = {
            bindto: '#graph-day',
            axis: {
                x: {
                    min: '00:00:00',
                    max: '24:00:00',
                    tick: {
                        format: '%H:%M'
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
                    value: ['production']
                },
                axes: {
                    production: 'y',
                    consumption: 'y',
                    total: 'y'
                },
                names: {
                    production: trans('ui.site.show.day.production') + ' (W)',
                    consumption: trans('ui.site.show.day.consumption') + ' (W)',
                    total: trans('ui.site.show.day.total') + ' (W)'
                },
                xFormat: '%H:%M:%S',
                types: {
                    production: 'line',
                    consumption: 'area',
                    total: 'line'
                },
                colors: {
                    production: bgSuccess,
                    consumption: bgInfo,
                    total: bgAlert
                },
                classes: {
                    production: 'thick-line',
                    consumption: 'thick-line',
                    total: 'thick-line'
                }
            },
            point: {
                r: 1
            }
        };

        if (typeof consumptionPower !== 'undefined') {
            $('#graph-day').css('height', '414px').closest('.panel').find('.panel-menu').removeClass('hidden');
            options.data.keys.value = ['production', 'consumption', 'total'];
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

    var updateChartDay = function(productionPower, consumptionPower) {
        chartDay.load({
            json: createPowerGraphJson(productionPower, consumptionPower),
            keys: {
                x: 'dt',
                value: (typeof consumptionPower === 'undefined' ? ['production'] : ['production', 'consumption', 'total'])
            }
        });
    };

    var createEnergyGraphJson = function(production, consumption) {
        var addEnergyToJson = function(json, energy, type) {
            if (typeof energy == 'undefined') return;

            $.each(energy, function(date, kwh) {
                if (!json.hasOwnProperty(date)) {
                    json[date] = {
                        dt: date
                    };
                }

                json[date][type] = kwh;
            });
        };

        var json = {};

        addEnergyToJson(json, production, 'production');
        addEnergyToJson(json, consumption, 'consumption');

        $.each(json, function(date, entry) {
            entry.total = 0;

            if (entry.hasOwnProperty('consumption')) entry.total += entry.consumption;
            if (entry.hasOwnProperty('production')) entry.total += entry.production;

            entry.total = Math.round(entry.total * 1000) / 1000;
        });

        // object of objects to array of objects
        json = Object.keys(json).map(function(key) {
            return json[key];
        });

        return json;
    };

    var initEnergyChart = function(production, consumption, chartOptions) {
        var first = null,
            last = null,
            most = 0;

        var json = createEnergyGraphJson(production, consumption);

        $.each(json, function(index, stat) {
            if (first === null) first = stat.dt;
            last = stat.dt;
            if (stat.total > most) most = stat.total;
        });

        var lines = [];

        for (var i = 0; i < 4; i++) {
            lines.push({value: (most / 4) * i});
        }

        chartOptions = $.extend(true, {}, mergeWithDefaults(chartOptions), {
            axis: {
                x: {
                    min: first,
                    max: last
                },
                y:{
                    tick: {
                        format: function (d) { return d + "kWh"; }
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
                    value: ['production', 'consumption']
                },
                groups: [
                    ['production', 'consumption']
                ],
                order: null, // order stacked bars by data definition
                names: {
                    production: trans('ui.site.show.month.production') + ' (kWh)',
                    consumption: trans('ui.site.show.month.consumption') + ' (kWh)'
                },
                type: 'bar',
                colors: {
                    production: bgSuccess,
                    consumption: bgInfo,
                    total: bgAlert
                }
            }
        });

        return c3.generate(chartOptions);
    };

    var initChartMonth = function(production, consumption) {
        chartMonth = initEnergyChart(production, consumption, {
            bindto: '#graph-month',
            axis: {
                x: {
                    tick: {
                        format: '%m-%d',
                        fit:true,
                        culling: {
                            max: window.innerWidth > 500 ? 8 : 5
                        }
                    }
                }
            },
            bar: {
                width: {
                    ratio: 0.3
                }
            },
            onresized: function () {
                window.innerWidth > 500 ? chartMonth.internal.config.axis_x_tick_culling_max = 8 : chartMonth.internal.config.axis_x_tick_culling_max = 5;
            },
            data: {
                xFormat: '%Y-%m-%d',
                onclick: function(dataPoint) {
                    focusedDate = moment(dataPoint.x);
                    reinitializeGraphs = true;
                    fetchCurrent();
                    fetchKpi();
                }
            }
        });
    };

    var updateChartMonth = function(production, consumption) {
        chartMonth.load({
            json: createEnergyGraphJson(production, consumption),
            keys: {
                value: ['production', 'consumption']
            }
        });
    };

    var initChartYear = function(production, consumption) {
        var month;

        if(Lang.locale() === 'en')
        {
            month = ["jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec"];
        }
        else if (Lang.locale() === 'nl')
        {
            month = ["jan","feb","mrt","apr","mei","jun","jul","aug","sep","okt","nov","dec"];
        }

        chartYear = initEnergyChart(production, consumption, {
            bindto: '#graph-year',
            axis: {
                x: {
                    tick: {
                        format: function(x) {
                            return (month[x.getMonth()]);
                        }
                    }
                }
            },
            data: {
                xFormat: '%Y-%m',
                onclick: function(dataPoint) {
                    focusedMonth = moment(dataPoint.x);
                    reinitializeGraphs = true;
                    myGraphs = true;
                    fetchCurrent();
                    fetchKpi();
                }
            }
        });
    };

    var updateChartYear = function(production, consumption) {
        chartYear.load({
            json: createEnergyGraphJson(production, consumption),
            keys: {
                value: ['production', 'consumption']
            }
        });
    };

    var updateBalance = function(balance) {
        if ('amount' in balance)$('#current-balance').html('<b> € ' + balance.amount + '</b> ');
    };

    var update = function(stats) {
        // fill key performance indicators

        if (reinitializeGraphs)
        {
            // Init on page load and on changing date
            initChartDay(stats.graphs.realtime_power, stats.graphs.consumption_during_day);
            if (focusedDate instanceof moment) $('.panel-title.day').text( trans('ui.site.show.day.title-change') + ' ' + focusedDate.format('L'));
        }
        else
        {
            updateChartDay(stats.graphs.realtime_power, stats.graphs.consumption_during_day);
        }

        reinitializeGraphs = false;
    };

    var updateGraph = function(stats) {

        if(myGraphs)
        {
            initChartMonth(stats.graphs.daily_output, stats.graphs.daily_consumption);
            initChartYear(stats.graphs.monthly_output, stats.graphs.monthly_consumption);
            if (focusedMonth instanceof moment) $('.panel-title.month').text( trans('ui.site.show.month.title-change') + ' ' + focusedMonth.format('MMMM YYYY'));
        }
        else
        {
            updateChartMonth(stats.graphs.daily_output, stats.graphs.daily_consumption);
            updateChartYear(stats.graphs.monthly_output, stats.graphs.monthly_consumption);
        }

        myGraphs = false;
    };

    var abortCurrent = function() {
        // Abort previous request and clear timeout.
        if (updateCurrentPower && updateCurrentPower.readyState != 4) updateCurrentPower.abort();
        window.clearTimeout(updateTimerCurrent);

    };

    var abortKpi = function() {
        // Abort previous request and clear timeout.
        if (updateKpi && updateKpi.readyState != 4) updateKpi.abort();
        window.clearTimeout(updateTimerKpi);

    };

    var fetchCurrent = function() {
        abortCurrent();

        updateCurrentPower = $.getJSON('api/abn', parameters, function(data) {
            var chart1 = c3.generate({
                data: {
                    json: data,

                    type: 'bar'
                },
                axis: {
                    x: {
                        type: 'category',
                        height: 50
                    }
                },
                bar: {
                    width: {
                        ratio: 0.5 // this makes bar width 50% of length between ticks
                    }
                    // or
                    //width: 100 // this makes bar width 100px
                },
                bindto: '#chart1'
            });

        }).always(function() {
            // schedule new update.
            updateTimerCurrent = window.setTimeout(fetchCurrent, 10000);
        });
    };

    var fetchKpi = function() {
        abortKpi();

        updateKpi = $.getJSON('api/abn/balance', function (data) {
            updateBalance(data.balances[0]);
        }).always(function () {
            // schedule new update.
            updateTimerKpi = window.setTimeout(fetchKpi, 60*1000);
        });
    };

    var init = function() {
        reinitializeGraphs = true;
        myGraphs = true;
        fetchCurrent();
        fetchKpi();

        // making use of jQuery Visibility plugin
        $(document).on({
            'show': function () {
                fetchCurrent();
                fetchKpi();
            },
            'hide': function () {
                abortCurrent();
                abortKpi();
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
var chart = c3.generate({
    data: {
        x : 'x',
        columns: [
            ['x', '2017-01', '2017-02', '2017-03', '2017-04', '2017-05', '2017-06'],
            ['Transactions', 30, 200, 100, 400, 150, 250,]
        ]
    },
    axis: {
        x: {
            type: 'category',
            height: 50
        }
    }
});
