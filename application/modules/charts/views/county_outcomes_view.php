<?php //echo"<pre>";print_r($outcomes); ?>
<div class="panel-body" id="county_summary">
	
</div>
<script type="text/javascript">
	$(function () {
			    $('#county_summary').highcharts({
			        chart: {
			            type: 'column'
			        },
			        title: {
			            text: ''
			        },
			        xAxis: {
			            categories: <?php echo json_encode($outcomes['categories']);?>
			        },
			        yAxis: {
			            min: 0,
			            title: {
			                text: 'Tests'
			            },
			            stackLabels: {
			            	rotation: -75,
			                enabled: true,
			                style: {
			                    fontWeight: 'bold',
			                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
			                },
			                y: -10
			            }
			        },
			        legend: {
			            align: 'right',
			            verticalAlign: 'bottom',
			            floating: false,
			            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
			            borderColor: '#CCC',
			            borderWidth: 1,
			            shadow: true
			        },
			        tooltip: {
			            headerFormat: '<b>{point.x}</b><br/>',
			            pointFormat: '{series.name}: {point.y}<br/>% contribution: {point.percentage:.1f}%'
			        },
			        plotOptions: {
			            column: {
			                stacking: 'normal',
			                dataLabels: {
			                    enabled: false,
			                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
			                    style: {
			                        textShadow: '0 0 3px black'
			                    }
			                }
			            }
			        },colors: [
				        '#F2784B',
				        '#1BA39C'
				    ],
			        series: <?php echo json_encode($outcomes['county_outcomes']);?>
			    });
			});
</script>
