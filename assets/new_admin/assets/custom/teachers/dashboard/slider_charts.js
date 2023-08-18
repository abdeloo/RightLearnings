"use strict";
var PageMethods4 = function() {
    var handleProgress = () => {

        var target3 = document.querySelector("#chapters_progress");
        var blockUI3 = new KTBlockUI(target3);
               
        getChaptersProgress();
        
        function getChaptersProgress(){            
            $.ajax({
                url: URLBase + 'NewVersion_Teachers_Home/GetSectionsProgress',
                type: 'post',
                data: {},
                cache: false,
                dataType: "JSON",
                beforeSend: function () {
                    blockUI3.block();
                },
                complete: function () {
                    blockUI3.release();
                }, success: function (response) {
                    //var results = JSON.parse(response.results);
                    //console.log(response.sections_count);
                    $('#chapters_progress').html(response.html);  
                    $('#completed_percentage').text(response.completed_percentage);  
                    KTSlidersWidget4.init(response.sections_count);            
                }
            });
        }

       
    }
    return {
            // Public Functions
            init: function() {
                handleProgress();
            }
    };
}();


var KTSlidersWidget4 = function (sections_count) {
    var charts = [];

    // Private methods
    var initChart = function(chart, query, color, data) {
        var element = document.querySelector(query);

        if (!element) {
            return;
        }

        if (chart.rendered === true && element.classList.contains("initialized")) {
            return;
        }

        var height = parseInt(KTUtil.css(element, 'height'));
        var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
        var borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');
        var baseColor = KTUtil.getCssVariableValue('--bs-' + color);

        var options = {
            series: [{
                name: 'Lessons',
                data: data
            }],
            chart: {
                fontFamily: 'inherit',
                type: 'area',
                height: height,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {

            },
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0,
                    stops: [0, 80, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [baseColor]
            },
            xaxis: {
                categories: ['', 'Apr 05', 'Apr 06', 'Apr 07', 'Apr 08', 'Apr 09', 'Apr 11', 'Apr 12', 'Apr 14', 'Apr 15', 'Apr 16', 'Apr 17', 'Apr 18', ''],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                tickAmount: 6,
                labels: {
                    rotate: 0,
                    rotateAlways: true,
                    style: {
                        colors: labelColor,
                        fontSize: '12px'
                    }
                },
                crosshairs: {
                    position: 'front',
                    stroke: {
                        color: baseColor,
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                tickAmount: 4,
                max: 24,
                min: 10,
                labels: {
                    style: {
                        colors: labelColor,
                        fontSize: '12px'
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px'
                }
            },
            colors: [baseColor],
            grid: {
                borderColor: borderColor,
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            markers: {
                strokeColor: baseColor,
                strokeWidth: 3
            }
        };

        chart.self = new ApexCharts(element, options);
        chart.self.render();
        chart.rendered = true;

        element.classList.add('initialized');
    }

    // Public methods
    return {
        addCharts: function (chartData) {
            chartData.forEach(function (data) {
                var chart = {
                    self: null,
                    rendered: false
                };
                charts.push(chart);
                initChart(chart, '#' + data.id, data.color, data.data);
            });
        },
        init: function (sections_count) {
            var chartData = [];
            for (var i = 0; i < sections_count; i++) {
                var chart = {
                    id: 'kt_sliders_widget_3_chart_' + i,
                    color: i%2 == 1 ? 'danger' : 'primary',
                    data: [18, 22, 22, 20, 20, 18, 18, 20, 20, 18, 18, 20, 20, 22]
                };

                // for (var j = 0; j < 14; j++) {
                //     var value = Math.floor(Math.random() * (23 - 19 + 1)) + 19;
                //     chart.data.push(value);
                // }

                chartData.push(chart);
            }

            this.addCharts(chartData);
        }
    };
}();
// On document ready
KTUtil.onDOMContentLoaded(function () {
    PageMethods4.init();
});