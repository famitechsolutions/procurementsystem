const SampleColors = ["#A7001A", "#014687", "#EE8E06", "#00FFFF", "#FF00FF", "#C0C0C0", "#00CC00", "#3333CC", "#0d5a16", "#030000", "#ef0d5e", "#591f34", "#02d1ff", "#bc00ff", "#2100f2", "#2100f2", "#2100f2", "#99ac14", "#ff0000", "#058e29", "#FF0099"];
var returnColorCode = function (index) {
    var item_index = (index <= SampleColors.length) ? index : Math.round(Math.random() * SampleColors.length);
    return SampleColors[item_index];
};
$(function () {
    /* ChartJS
     * -------
     * Data and config for chartjs
     */

    var reqData = [], lpoData = [], bgColors1 = [], bgColors2 = [], labels = [];
    try {
        for (var i = 0; i < itemsObject.length; i++) {
            reqData.push(itemsObject[i].total_requisitions);
            bgColors1.push('rgba(39, 23, 201, 1)');
            lpoData.push(itemsObject[i].total_lpos);
            bgColors2.push('rgb(255,131,0)');
            labels.push(itemsObject[i].name);
        }
    } catch (ex) {

    }

    'use strict';
    var data = {
        labels: labels,
        datasets: [{
            label: 'Requisitions',
            data: reqData,
            backgroundColor: bgColors1,
            borderColor: bgColors1,
            borderWidth: 1,
            fill: false
        },{
            label: 'LPOs',
            data: lpoData,
            backgroundColor: bgColors2,
            borderColor: bgColors2,
            borderWidth: 1,
            fill: false
        },
        // {
        //     label: 'Expence',
        //     data: [4500, 3500, 4700, 3700, 4000],
        //     backgroundColor: [
        //         'rgba(92, 59, 196, 0.32)',
        //         'rgba(92, 59, 196, 0.32)',
        //         'rgba(92, 59, 196, 0.32)',
        //         'rgba(92, 59, 196, 0.32)',
        //         'rgba(92, 59, 196, 0.32)',
        //     ],
        //     borderColor: [
        //         'rgba(92, 59, 196, 0.32)',
        //         'rgba(92, 59, 196, 0.32)',
        //         'rgba(92, 59, 196, 0.32)',
        //         'rgba(92, 59, 196, 0.32)',
        //         'rgba(92, 59, 196, 0.32)',
        //     ],
        //     borderWidth: 1,
        //     fill: false
        // }
        ]
    };
    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                },
                gridLines: {
                    drawBorder: false,
                    display: true,
                    color: "rgba(135, 150, 165, 0.15)",
                },
            }],
            xAxes: [{
                ticks: {
                    beginAtZero: true
                },
                gridLines: {
                    drawBorder: false,
                    display: false,
                },
            }]
        },
        legend: {
            display: false
        },
        legendCallback: function (chart) {
            var text = [];
            text.push('<div class="row mt-2">');
            for (var i = 0; i < chart.data.datasets.length; i++) {
                text.push('<div class="col-sm-5 mr-3 ml-3 ml-sm-0 mr-sm-0 pr-md-0 mt-3"><div class="row align-items-center"><div class="col-2"><span class="legend-label" style="background-color:' + chart.data.datasets[i].backgroundColor[i] + '"></span></div><div class="col-9"><p class="text-dark m-0">' + chart.data.datasets[i].label + '</p></div></div>');
                text.push('</div>');
            }
            text.push('</div>');
            return text.join("");
        },
        elements: {
            point: {
                radius: 0
            }
        }

    };



    // Get context with jQuery - using jQuery's .get() method.
    if ($("#barChart").length) {
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: data,
            options: options
        });
        document.getElementById('chart-legendsBar').innerHTML = barChart.generateLegend();
    }
    if ($('#circleProgress1').length) {
        var bar = new ProgressBar.Circle(circleProgress1, {
            color: '#5c3bc4',
            strokeWidth: 10,
            trailWidth: 10,
            easing: 'easeInOut',
            duration: 1400,
            width: 78,
            trailColor: '#f4f4f4',
        });
        bar.animate(.18); // Number from 0.0 to 1.0
    }
    if ($('#circleProjectProgress').length) {
        var bar = new ProgressBar.Circle(circleProgress1, {
            color: '#5c3bc4',
            strokeWidth: 10,
            trailWidth: 10,
            easing: 'easeInOut',
            duration: 1400,
            width: 89,
            trailColor: '#f4f4f4',
        });
        bar.animate(.18); // Number from 0.0 to 1.0
    }
    if ($('#circleProgress2').length) {
        var bar = new ProgressBar.Circle(circleProgress2, {
            color: '#f2125e',
            strokeWidth: 10,
            trailWidth: 10,
            easing: 'easeInOut',
            duration: 1400,
            width: 42,

        });
        bar.animate(.36); // Number from 0.0 to 1.0
    }
    if ($('#circleProgressDark1').length) {
        var bar = new ProgressBar.Circle(circleProgressDark1, {
            color: '#5c3bc4',
            strokeWidth: 10,
            trailWidth: 10,
            easing: 'easeInOut',
            duration: 1400,
            width: 42,
            trailColor: '#878c9e',
        });
        bar.animate(.18); // Number from 0.0 to 1.0
    }
    if ($('#circleProgressDark2').length) {
        var bar = new ProgressBar.Circle(circleProgressDark2, {
            color: '#f2125e',
            strokeWidth: 10,
            trailWidth: 10,
            easing: 'easeInOut',
            duration: 1400,
            width: 42,
            trailColor: '#878c9e',

        });
        bar.animate(.36); // Number from 0.0 to 1.0
    }
});