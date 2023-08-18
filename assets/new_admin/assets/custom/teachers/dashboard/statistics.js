"use strict";
var PageMethods2 = function() {
    var handleStatistics = () => {

        var target2 = document.querySelector("#attendance_homework_statistics");
        var blockUI2 = new KTBlockUI(target2);
        var start = moment().subtract(29, 'days'); //"start";
        var end = moment();  //"end";
        var current = moment();
        cb(start,end);
        function cb(start, end) {
            if ( current.isSame(start, "day") && current.isSame(end, "day") ) {
                $("#kt_daterangepicker_4").html(start.format("YYYY-MM-DD"));
            } else {
                $("#kt_daterangepicker_4").html(start.format("YYYY-MM-DD") + " / " + end.format("YYYY-MM-DD"));
            } 
            getSectionStatistics(start.format("YYYY-MM-DD"),end.format("YYYY-MM-DD"));
        }

        $("#kt_daterangepicker_4").daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
              format: "YYYY-MM-DD"
            },
            ranges: {
                [Lang.Today]: [moment(), moment()],
                [Lang.Yesterday]: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                [Lang.Last_7_Days]: [moment().subtract(6, 'days'), moment()],
                [Lang.Last_30_Days]: [moment().subtract(29, 'days'), moment()],
                [Lang.This_Month]: [moment().startOf('month'), moment().endOf('month')],
                [Lang.Last_Month]: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
        
       
        //getSectionStatistics((new Date()).toISOString().split('T')[0]);
        // ('#kt_daterangepicker').bind('DOMSubtreeModified', function(){
        //     console.log($(this).text());
        //     //getSectionStatistics($(this).val());
        // })
        
        function getSectionStatistics(from_date,to_date){            
            $.ajax({
                url: URLBase + 'NewVersion_Teachers_Home/GetSectionStatistics',
                type: 'post',
                data: {from_date: from_date, to_date: to_date},
                cache: false,
                dataType: "JSON",
                beforeSend: function () {
                    blockUI2.block();
                },
                complete: function () {
                    blockUI2.release();
                    $("#kt_chart_widgets_22_tab_1").addClass("active");
                    $("#kt_chart_widgets_22_tab_2").removeClass("active");
                }, success: function (response) {
                    // The 'response' parameter contains the JSON-encoded response from the server
                    // You can parse the 'results' property of the response to get the original '$results' variable
                    var results = JSON.parse(response.results);

                    console.log(results["total_students"]);

                    $('#attendance_homework_statistics').html(response.html);


                    // Add response in Modal body
                    //$("#attendance_homework_statistics").html(response);
                    initChart('#kt_chart_widgets_22_tab_1', '#kt_chart_widgets_22_chart_1', results, true);
                    initChart('#kt_chart_widgets_22_tab_2', '#kt_chart_widgets_22_chart_2', [70, 13, 11, 2], false);              
                }
            });
        }

        var initChart = function(tabSelector, chartSelector, data, initByDefault) {
            var element = document.querySelector(chartSelector);        
    
            if (!element) {
                return;
            }  
              
            var height = parseInt(KTUtil.css(element, 'height'));
            
            var options = {
                series: data["total_students"],                 
                chart: {           
                    fontFamily: 'inherit', 
                    type: 'donut',
                    width: 250,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '50%',
                            labels: {
                                value: {
                                    fontSize: '10px'
                                }
                            }                        
                        }
                    }
                },
                colors: [
                    KTUtil.getCssVariableValue('--bs-info'), 
                    KTUtil.getCssVariableValue('--bs-success'), 
                    KTUtil.getCssVariableValue('--bs-primary'), 
                    KTUtil.getCssVariableValue('--bs-danger') 
                ],           
                stroke: {
                  width: 0
                },
                labels: data["sections_names"],
                legend: {
                    show: false,
                },
                fill: {
                    type: 'false',          
                }     
            };                     
    
            var chart = new ApexCharts(element, options);
    
            var init = false;
    
            var tab = document.querySelector(tabSelector);
            
            if (initByDefault === true) {
                chart.render();
                init = true;
            }        
    
            tab.addEventListener('shown.bs.tab', function (event) {
                if (init == false) {
                    chart.render();
                    init = true;
                }
            })
        }
    }
    return {
            // Public Functions
            init: function() {
                handleStatistics();
            }
    };
}();


// On document ready
KTUtil.onDOMContentLoaded(function () {
    PageMethods2.init();
});