//$(function () {
    function createHighchart(target, options)
    {
        var defaultOptions = {
            plotOptions: {
                area: {
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 2
                        }
                    },
                    marker: {
                        enabled: false,
                        states: {
                            hover: {
                                enabled: true,
                                radius: 4,
                                lineWidth: 1
                            }
                        }
                    }
                }
            },
            yAxis: {
                title: {
                    text: 'kW'
                },
                plotLines: [
                    {
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }
                ]
            },
            credits: {
                enabled: false
            },
            tooltip: {
                valueSuffix: 'kW',
                snap: 1
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            }
        };

        $.extend( options, defaultOptions ); // merge option objects
        $(target).highcharts(options);
    }
//}